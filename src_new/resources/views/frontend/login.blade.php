@extends('layouts.app')

@section('content')
<div class="login-hero d-flex align-items-center justify-content-center min-vh-100 px-3">
  <div class="login-card p-4 rounded-4 shadow-lg">
    <div class="text-center mb-4">
      <i class="bi bi-person-circle fs-1 text-purple"></i>
      <h4 class="fw-bold mt-2 text-purple">Welcome Back!</h4>
      <p class="small text-secondary">Silakan login untuk melanjutkan</p>
    </div>

    <!-- Login Form -->
    <form action="{{ route('login') }}" method="POST">
      @csrf
      <div class="mb-3">
        <label for="email" class="form-label small fw-semibold">Email</label>
        <input type="email" class="form-control custom-focus" id="email" name="email" placeholder="nama@email.com" required value="{{ old('email') }}">
        @error('email')
          <div class="text-danger small">{{ $message }}</div>
        @enderror
      </div>
      <div class="mb-3">
        <label for="password" class="form-label small fw-semibold">Password</label>
        <input type="password" class="form-control custom-focus" id="password" name="password" placeholder="••••••••" required>
        @error('password')
          <div class="text-danger small">{{ $message }}</div>
        @enderror
      </div>

      <button type="submit" class="btn w-100 fw-semibold text-white" style="background-color: #6b21a8;">Login</button>
    </form>

    <p class="text-center mt-3 small">Belum punya akun? <a href="{{ route('register') }}" class="text-decoration-none text-purple">Daftar</a></p>
  </div>
</div>
@endsection
