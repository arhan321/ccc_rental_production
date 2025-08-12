<?php

namespace App\Http\Controllers;

use Midtrans\Snap;
use Midtrans\Config;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\{Order, OrderItem, Kostum, ProductSchedule};

class OrderController extends Controller
{
    /* ────────────────────────────────────────────────
       MIDTRANS INIT
    ───────────────────────────────────────────────── */
    public function __construct()
    {
        Config::$serverKey    = config('midtrans.serverKey');
        Config::$isProduction = config('midtrans.isProduction');
        Config::$isSanitized  = config('midtrans.isSanitized');
        Config::$is3ds        = config('midtrans.is3ds');
    }

    /* ────────────────────────────────────────────────
       1.  CHECKOUT  →  /checkout   (POST, auth:user)
    ───────────────────────────────────────────────── */
    public function checkout(Request $request): JsonResponse
    {
        /* 0. auth & profil */
        $user = Auth::user();
        if (! $user->profile) {
            return response()->json(['message'=>'Lengkapi profil terlebih dahulu'], 403);
        }

        /* 1. validasi */
        $data = $request->validate([
            'tanggal_mulai'      => 'required|date',
            'durasi'             => 'required|integer|min:3',
            'total'              => 'required|integer|min:1',
            'alamat_toko'        => 'required|string|max:255',
            'items'              => 'required|array|min:1',
            'items.*.kostums_id' => 'required|exists:kostums,id',
            'items.*.ukuran'     => 'required|in:S,M,L,XL',
            'items.*.price'      => 'required|integer|min:1',
            'items.*.durasi'     => 'required|integer|min:3',
        ]);

        /* 2. header order */
        $order = Order::create([
            'profile_id'         => $user->profile->id,
            'nomor_pesanan'      => 'ORDER-'.Str::upper(Str::random(8)),
            'tanggal_order'      => now(),
            'tanggal_batas_sewa' => now()->addDays($data['durasi']),
            'durasi_sewa'        => $data['durasi'],
            'total_harga'        => $data['total'],
            'status'             => 'Menunggu',
            'alamat_toko'        => $data['alamat_toko'],
        ]);

        /* 3. detail +  schedule + lock kostum */
        foreach ($data['items'] as $it) {
            $item = OrderItem::create([
                'order_id'    => $order->id,
                'kostums_id'  => $it['kostums_id'],
                'ukuran'      => $it['ukuran'],
                'durasi_sewa' => $it['durasi'],
                'harga_sewa'  => $it['price'],
            ]);

            // 3.a  catat jadwal
            ProductSchedule::create([
                'order_item_id'   => $item->id,
                'kostums_id'      => $item->kostums_id,
                'order_id'        => $order->id,
                'tanggal_mulai'   => $data['tanggal_mulai'],
                'tanggal_selesai' => now()->addDays($it['durasi'] - 1),
            ]);

            // 3.b  ubah status kostum
            Kostum::whereKey($item->kostums_id)->update(['status' => 'Terbooking']);
        }

        /* 4. item detail Midtrans  (price × paket) */
        $midItems = collect($data['items'])->map(function ($it) {
            $paket = $it['durasi'] / 3;          // 3-hari = 1 paket
            return [
                'id'       => $it['kostums_id'],
                'price'    => $it['price'] * $paket,
                'quantity' => 1,
                'name'     => 'Sewa Kostum',
            ];
        })->toArray();

        /* 5. Snap */
        $orderIdFull = $order->nomor_pesanan.'-'.now()->timestamp;
        $snapToken   = Snap::getSnapToken([
            'transaction_details' => [
                'order_id'     => $orderIdFull,
                'gross_amount' => $order->total_harga,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email'      => $user->email,
                'phone'      => $user->profile->nomor_telepon ?? '',
            ],
            'item_details'      => $midItems,
        ]);

        $order->update(['snap_token'=>$snapToken]);

        return response()->json([
            'success'       => true,
            'snap_token'    => $snapToken,
            'order_id_full' => $orderIdFull,
        ]);
    }

    /* ────────────────────────────────────────────────
       2.  CALLBACK FRONT  →  /checkout/status
    ───────────────────────────────────────────────── */
    public function checkoutStatus(Request $request): JsonResponse
    {
        $data = $request->validate([
            'order_id_full'      => 'required|string',
            'transaction_status' => 'required|string',
        ]);

        // ORDER-XXXXXX-<timestamp>
        $baseId = Str::beforeLast($data['order_id_full'], '-');

        $order = Order::where('nomor_pesanan',$baseId)->first();
        if (! $order) return response()->json(['message'=>'Not found'],404);

        switch ($data['transaction_status']) {
            case 'capture':
            case 'settlement':
                $order->status = 'Diproses';
                break;

            case 'deny':
            case 'cancel':
            case 'expire':
                $order->status = 'Dibatalkan';

                // ➜ buka kembali kostum + hapus jadwal
                foreach ($order->orderItems as $it) {
                    Kostum::whereKey($it->kostums_id)->update(['status'=>'Tersedia']);
                    ProductSchedule::where('order_item_id',$it->id)->delete();
                }
                break;

            default:                                    // pending, etc.
                $order->status = 'Menunggu';
        }
        $order->save();

        return response()->json(['status'=>$order->status]);
    }

    /* ────────────────────────────────────────────────
       3.  LIST & HISTORI
    ───────────────────────────────────────────────── */
    public function index()
    {
        $orders = Order::with('orderItems.kostums')
                 ->where('profile_id', Auth::user()->profile->id)
                 ->whereIn('status',['Menunggu','Diproses','Siap Di Ambil'])
                 ->latest('tanggal_order')->get();

        return view('frontend.pesanan', compact('orders'));
    }

    public function history()
    {
        $orders = Order::with('orderItems.kostums')
                 ->where('profile_id', Auth::user()->profile->id)
                 ->where('status','Selesai')
                 ->latest('tanggal_order')->get();

        return view('frontend.histori', compact('orders'));
    }

    public function payAgain(Order $order): JsonResponse
{
    // verifikasi: hanya pemilik & status masih Menunggu
    if ($order->profile_id !== auth()->user()->profile->id) {
        return response()->json(['message'=>'Forbidden'], Response::HTTP_FORBIDDEN);
    }
    if ($order->status !== 'Menunggu') {
        return response()->json(['message'=>'Pembayaran sudah diproses'], 422);
    }

    // buat Snap token baru
    $orderIdFull = $order->nomor_pesanan . '-' . now()->timestamp;

    $snapToken = \Midtrans\Snap::getSnapToken([
        'transaction_details' => [
            'order_id'     => $orderIdFull,
            'gross_amount' => $order->total_harga,
        ],
        'customer_details' => [
            'first_name' => $order->profile->nama_lengkap,
            'email'      => $order->profile->user->email,
            'phone'      => $order->profile->nomor_telepon,
        ],
    ]);

    $order->update(['snap_token' => $snapToken]); // opsional

    return response()->json([
        'snap_token'    => $snapToken,
        'order_id_full' => $orderIdFull,
    ]);
}
}
