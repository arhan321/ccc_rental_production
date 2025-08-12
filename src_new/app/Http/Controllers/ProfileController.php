<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Profile;

class ProfileController extends Controller
{
    /* ==========================================================
       1) Tampilan profil
       ========================================================== */
    public function show()
    {
        $profile = Profile::where('users_id', Auth::id())->firstOrFail();

        return view('frontend.profile', [
            'profile' => $profile,
            'mode'    => 'view',
        ]);
    }

    /* ==========================================================
       2) Form edit profil
       ========================================================== */
    public function edit()
    {
        $profile = Profile::where('users_id', Auth::id())->firstOrFail();

        return view('frontend.profile', [
            'profile' => $profile,
            'mode'    => 'edit',
        ]);
    }

    /* ==========================================================
       3) Simpan perubahan
       ========================================================== */
    public function update(Request $request)
    {
        $profile = Profile::where('users_id', Auth::id())->firstOrFail();

        $validated = $request->validate([
            'nama_lengkap'   => ['required', 'string', 'max:255'],
            'nomor_telepon'  => ['required', 'string', 'max:30'],
            'tanggal_lahir'  => ['required', 'date'],
            'jenis_kelamin'  => ['required', 'in:Pria,Wanita'],

            // ─── Instagram ─────────────────────────────────────
            //   • opsional, max 30 (batas IG) + “@” di depan boleh tidak
            //   • hanya huruf, angka, titik, dan underscore
            'instagram'      => ['nullable', 'string', 'max:255',
                                 'regex:/^@?[A-Za-z0-9._]{1,30}$/'],

            // ─── Foto ──────────────────────────────────────────
            'avatar'         => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'instagram.regex' => 'Format Instagram tidak valid.',
        ]);

        /* ── Normalisasi handle IG: buang "@" di depan ───────── */
        if (isset($validated['instagram'])) {
            $validated['instagram'] = ltrim($validated['instagram'], '@');
        }

        /* ── Upload avatar (jika ada) ────────────────────────── */
        if ($request->hasFile('avatar')) {
            if ($profile->avatar_url && Storage::disk('public')->exists($profile->avatar_url)) {
                Storage::disk('public')->delete($profile->avatar_url);
            }
            $validated['avatar_url'] = $request->file('avatar')
                                              ->store('avatars', 'public');
        }

        /* ── Simpan ke database ─────────────────────────────── */
        $profile->update($validated);

        return redirect()
            ->route('profile.show')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
