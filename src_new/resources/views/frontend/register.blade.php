@extends('layouts.app')
@section('content')

<div class="register-hero d-flex align-items-center justify-content-center py-5 px-3">
  <div class="register-card p-4 rounded-4 shadow-lg">
    <div class="text-center mb-4">
      <i class="bi bi-person-plus-fill fs-1 text-purple"></i>
      <h4 class="fw-bold mt-2 text-purple">Buat Akun Baru</h4>
      <p class="small text-secondary">Silakan daftar untuk melanjutkan</p>
    </div>

    <!-- Registration Form -->
    <form action="{{ route('register') }}" method="POST">
      @csrf
      <div class="mb-3">
        <label for="name" class="form-label small fw-semibold">Nama Lengkap</label>
        <input type="text" class="form-control custom-focus" id="name" name="name" placeholder="Nama Lengkap" required>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label small fw-semibold">Email</label>
        <input type="email" class="form-control custom-focus" id="email" name="email" placeholder="nama@email.com" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label small fw-semibold">Password</label>
        <input type="password" class="form-control custom-focus" id="password" name="password" placeholder="••••" required>
      </div>

      <div class="mb-3">
        <label for="confirmPassword" class="form-label small fw-semibold">Konfirmasi Password</label>
        <input type="password" class="form-control custom-focus" id="confirmPassword" name="password_confirmation" placeholder="••••" required>
      </div>

      <div class="mb-3">
        <label for="registrationDate" class="form-label small fw-semibold">Tanggal Lahir</label>
        <input type="date" class="form-control custom-focus" id="registrationDate" name="tanggal_lahir" required>
      </div>

      <div class="mb-3">
        <label for="gender" class="form-label small fw-semibold">Jenis Kelamin</label>
        <select class="form-select custom-focus" id="gender" name="jenis_kelamin" required>
          <option selected disabled value="">Pilih...</option>
          <option value="Laki-laki">Laki-laki</option>
          <option value="Perempuan">Perempuan</option>
          <option value="Lainnya">Lainnya</option>
        </select>
      </div>

      <div class="mb-3">
        <label for="phone" class="form-label small fw-semibold">Nomor Telepon</label>
        <input type="text" class="form-control custom-focus" id="phone" name="nomor_telepon" placeholder="" required>
      </div>

      <button type="submit" class="btn w-100 fw-semibold text-white" style="background-color: #6b21a8;">Daftar</button>
    </form>

    @if($errors->any())
          <div class="alert alert-danger small">
            <ul class="mb-0">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

    <p class="text-center mt-3 small">Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none text-purple">Login</a></p>
  </div>
</div>
@endsection
