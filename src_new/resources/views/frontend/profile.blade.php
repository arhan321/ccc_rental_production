@extends('layouts.app')

@section('content')
@php
  function fmt($d){ return \Carbon\Carbon::parse($d)->translatedFormat('d F Y'); }
@endphp

<section class="py-5">
  <div class="container d-flex justify-content-center">
   <div class="card shadow rounded-4 p-4" style="max-width:700px;width:100%">

    {{-- ========= HEADER (foto + nama) ========= --}}
    <div class="text-center mb-4">
      <div class="rounded-circle overflow-hidden mx-auto" style="width:120px;height:120px">
        <img src="{{ $profile->avatar_url ? asset('storage/'.$profile->avatar_url)
                                           : asset('front/assets/default-avatar.png') }}"
             class="w-100 h-100" style="object-fit:cover">
      </div>
      <h4 class="mt-3 fw-semibold" style="color:#6b21a8">{{ $profile->nama_lengkap }}</h4>
    </div>

    {{-- ========= MODE LIHAT ========= --}}
    @if($mode === 'view')
      <div class="mb-4">
        <div class="d-flex justify-content-between py-2 border-bottom">
          <span class="text-muted">Nama Lengkap</span><span>{{ $profile->nama_lengkap }}</span>
        </div>
        <div class="d-flex justify-content-between py-2 border-bottom">
          <span class="text-muted">Tanggal Lahir</span><span>{{ fmt($profile->tanggal_lahir) }}</span>
        </div>
        <div class="d-flex justify-content-between py-2 border-bottom">
          <span class="text-muted">Jenis Kelamin</span><span>{{ $profile->jenis_kelamin }}</span>
        </div>
        <div class="d-flex justify-content-between py-2 border-bottom">
          <span class="text-muted">Nomor Telepon</span><span>{{ $profile->nomor_telepon }}</span>
        </div>

        {{-- ★ Instagram (view) --}}
        <div class="d-flex justify-content-between py-2 border-bottom">
          <span class="text-muted">Instagram</span>
          <span>
            @if($profile->instagram)
              <a href="https://instagram.com/{{ ltrim($profile->instagram,'@') }}" target="_blank">
                {{ $profile->instagram }}
              </a>
            @else
              <em class="text-muted">–</em>
            @endif
          </span>
        </div>

        <div class="d-flex justify-content-between py-2 border-bottom">
          <span class="text-muted">Email</span><span>{{ Auth::user()->email }}</span>
        </div>
      </div>

      <div class="d-flex justify-content-between">
        <a href="{{ route('profile.edit') }}" class="btn btn-gradient-purple">
          <i class="bi bi-pencil-square me-1"></i>Edit Profil
        </a>
        <form action="{{ route('logout') }}" method="POST">@csrf
          <button class="btn btn-outline-danger">
            <i class="bi bi-box-arrow-right me-1"></i>Logout
          </button>
        </form>
      </div>

    {{-- ========= MODE EDIT ========= --}}
    @else
      {{-- tampil error validasi --}}
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
      @endif

      <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="mb-3">
          <label class="form-label fw-semibold">Nama Lengkap</label>
          <input type="text" name="nama_lengkap"
                 value="{{ old('nama_lengkap',$profile->nama_lengkap) }}"
                 class="form-control @error('nama_lengkap') is-invalid @enderror">
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Nomor Telepon</label>
          <input type="text" name="nomor_telepon"
                 value="{{ old('nomor_telepon',$profile->nomor_telepon) }}"
                 class="form-control @error('nomor_telepon') is-invalid @enderror">
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Tanggal Lahir</label>
          <input type="date" name="tanggal_lahir"
                 value="{{ old('tanggal_lahir',$profile->tanggal_lahir) }}"
                 class="form-control @error('tanggal_lahir') is-invalid @enderror">
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Jenis Kelamin</label>
          <select name="jenis_kelamin"
                  class="form-select @error('jenis_kelamin') is-invalid @enderror">
            <option value="Pria"   {{ old('jenis_kelamin',$profile->jenis_kelamin)=='Pria'?'selected':'' }}>Pria</option>
            <option value="Wanita" {{ old('jenis_kelamin',$profile->jenis_kelamin)=='Wanita'?'selected':'' }}>Wanita</option>
          </select>
        </div>

        {{-- ★ Instagram (edit) --}}
        <div class="mb-3">
          <label class="form-label fw-semibold">Instagram (opsional)</label>
          <input type="text" name="instagram"
                 placeholder="@username"
                 value="{{ old('instagram',$profile->instagram) }}"
                 class="form-control @error('instagram') is-invalid @enderror">
        </div>

        <div class="mb-4">
          <label class="form-label fw-semibold">Ganti Foto (opsional)</label>
          <input type="file" name="avatar"
                 class="form-control @error('avatar') is-invalid @enderror">
        </div>

        <div class="d-flex justify-content-between">
          <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">Batal</a>
          <button class="btn btn-gradient-purple px-4">Simpan</button>
        </div>
      </form>
    @endif

   </div>
  </div>
</section>
@endsection
