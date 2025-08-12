<?php

namespace App\Http\Controllers;

use App\Models\Pengembalian;
use Illuminate\Support\Facades\Auth;

class PengembalianController extends Controller
{
    public function index()
{
    $user = Auth::user();

    $pengembalians = Pengembalian::with([
            'order.orderItems.kostums'   // <-- eager load
        ])
        ->whereHas('order', fn ($q) =>
            $q->where('profile_id', $user->profile->id)
              ->whereNotIn('status', ['Dibatalkan']))
        ->get();

    return view('frontend.pengembalian', compact('pengembalians'));
}

}
