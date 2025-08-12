<?php

namespace App\Http\Controllers;

use App\Models\Kostum;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\CustomRequest;
use Illuminate\Support\Facades\Storage;

class FrontendController extends Controller
{
    public function index(Request $request)
    {
        // Fetch categories from the database
    $categories = Category::all();

    // If a category is selected, filter the kostums
    $kostums = Kostum::with('category');

    // Filter by selected category if provided
    if ($request->has('filterCategory') && $request->filterCategory != 'Semua') {
        $kostums = $kostums->where('category_id', $request->filterCategory);
    }

    // Get the filtered kostums
    $kostums = $kostums->get();

    

        // Pass the data to the view
        return view('frontend.index', compact('kostums', 'categories'));
    }

    public function showFormPengajuan()
{
    return view('frontend.ajukan-kostum'); // pastikan file ajukan-kostum.blade.php sudah ada di resources/views
}

public function storePengajuanKostum(Request $request)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'telepon' => 'required|string|max:20',
        'referensi' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        'ukuran' => 'required|string',
        'template' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
        'catatan' => 'nullable|string',
    ]);

    // Simpan file referensi
    $referensiPath = $request->file('referensi')->store('referensi-kostum', 'public');

    // Simpan file template ukuran (jika ada)
    $templatePath = null;
    if ($request->hasFile('template')) {
        $templatePath = $request->file('template')->store('template-ukuran', 'public');
    }

    CustomRequest::create([
        'nama' => $request->nama,
        'telepon' => $request->telepon,
        'referensi' => $referensiPath,
        'ukuran' => $request->ukuran,
        'template' => $templatePath,
        'catatan' => $request->catatan,
        'status' => 'Menunggu',
        'profile_id' => auth()->user()->profile->id,
    ]);

    return redirect()->back()->with('success', 'Pengajuan kostum berhasil dikirim!');
}


public function riwayatCustom()
{
    $profile = auth()->user()->profile;

    if (!$profile) {
        return redirect()->route('frontend.index')->with('error', 'Profil tidak ditemukan.');
    }

    $requests = CustomRequest::where('profile_id', $profile->id)
        ->latest()
        ->get();

    return view('frontend.pengajuan', compact('requests'));
}
}
