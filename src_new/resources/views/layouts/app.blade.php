<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>CCC RENTAL | Sewa Kostum Cosplay</title>
  <!-- Leaflet CSS -->
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
 <link href="https://fonts.googleapis.com/css2?family=Mona+Sans:wght@400;600&display=swap" rel="stylesheet">


  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('front/style/style.css') }}">
</head>
<body>



<!-- Navbar -->
<nav class="navbar navbar-expand-lg shadow-sm sticky-top" style="background-color: #f4f0ff;">
  <div class="container px-3 d-flex justify-content-between align-items-center">

    <!-- LOGO -->
    <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
    <img src="{{ asset('front/assets/logo.png') }}"
         alt="CCC Rental Logo" style="height:48px; width:auto;">
    <span class="fw-bold ms-2" style="color:#6b21a8;">CCC RENTAL</span>
</a>

    <!-- HAMBURGER (mobile) -->
    <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <i class="bi bi-list fs-2" style="color: #6b21a8;"></i>
    </button>

    <!-- MENU NAV -->
    <div class="collapse navbar-collapse order-lg-0" id="navbarNav">
      <ul class="navbar-nav ms-auto text-center text-lg-start w-100 justify-content-end gap-1 align-items-center">
        <li class="nav-item">
          <a class="nav-link px-2 py-1 fw-normal small" href="/" style="color: #6b21a8;">Home</a>
        </li>

        <li class="nav-item">
          <a class="nav-link px-2 py-1 fw-normal small" href="/" style="color: #6b21a8;">Product</a>
        </li>
        @auth
        <li class="nav-item">
          <a class="nav-link px-2 py-1 fw-normal small" href="{{ route('riwayat.custom') }}" style="color: #6b21a8;">Pengajuan</a>
        </li>
        @endauth
        @auth
        <li class="nav-item">
          <a class="nav-link px-2 py-1 fw-normal small" href="{{ route('orders.index') }}" style="color: #6b21a8;">Pesanan</a>
        </li>
        @endauth
        @auth
        <li class="nav-item">
          <a class="nav-link px-2 py-1 fw-normal small" href="{{ route('pengembalian.index') }}" style="color: #6b21a8;">Pengembalian</a>
        </li>
        @endauth
        @auth
        <li class="nav-item">
          <a class="nav-link px-2 py-1 fw-normal small" href="{{ route('frontend.histori') }}" style="color: #6b21a8;">History Order</a>
        </li>
        @endauth
        

          @auth
@php($p = Auth::user()->profile)
<li class="nav-item d-flex align-items-center gap-1 px-2">
    <a href="{{ route('profile.show') }}" class="nav-link p-0">
        <i class="bi bi-person-fill fs-5" style="color:#6b21a8"></i>
        <span class="fw-normal small" style="color:#6b21a8">
            {{ $p?->nama_lengkap ?? Auth::user()->name }}
        </span>
    </a>
</li>
@endauth
      
      <!-- Cart -->
      <li class="nav-item w-30 text-center">
        <div class="d-flex justify-content-center">
          <a href="#" class="btn btn-light border d-flex align-items-center justify-content-center px-2 py-1 position-relative" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart">
            <i class="bi bi-cart fs-6 me-1" style="color: #6b21a8;"></i>
            <span class="fw-semibold small" style="color: #6b21a8;">My Cart</span>
            <span id="cartBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none; font-size: 0.65rem;">0</span>
          </a>
        </div>
      </li>

        <!-- Login -->
        @guest
            <li class="nav-item">
                <a href="{{ route('login') }}" class="but-log btn btn-sm d-flex align-items-center gap-1 px-2 py-1">
                    <i class="bi bi-person-fill text-white fs-6"></i>
                    <span class="fw-semibold small text-white">Login</span>
                </a>
            </li>
        @endguest
      </ul>
    </div>
  </div>
</nav>

<!-- Offcanvas Cart sidebar -->
  <div class="offcanvas offcanvas-end" data-bs-scroll="true" data-bs-backdrop="false" id="offcanvasCart">
    <div class="offcanvas-header text-white" style="background: linear-gradient(135deg, #6f42c1, #000000);">
      <h5 class="offcanvas-title d-flex align-items-center">
        <i class="bi bi-disc me-2"></i> CCC RENTAL
      </h5>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body p-3 d-flex flex-column h-100" style="background: #f8f5ff; color: #1e1b4b;">
      <h5 class="mb-3 fw-semibold text-purple">Keranjang Saya</h5>

    <!-- Alert Informasi -->
  <div class="alert alert-warning py-2 px-3 mb-3 rounded-3" role="alert" style="font-size: 0.85rem;">
    ⏱️ Durasi sewa minimal adalah <strong>3 hari</strong> dan hanya bisa ditambah per <strong>3 hari</strong>.<br>
    Jika dikurangi di bawah 3 hari, produk akan otomatis dihapus dari keranjang.
  </div>

  <!-- Dinamis isi produk -->
  <div id="cartItems"></div>
    

      <!-- Spacer agar total tetap di bawah -->
      <div style="flex-grow: 1;"></div>

      <!-- Total -->
      <div class="border-top pt-3 mt-3">
        <div class="d-flex justify-content-between mb-1">
          <span>Subtotal</span>
          <span id="subtotal">Rp0</span>
        </div>
        <div class="d-flex justify-content-between fw-bold text-purple">
          <span>Total</span>
          <span id="total">Rp0</span>
        </div>
        <button id="checkoutBtn"  class="btn w-100 mt-3 text-white fw-semibold" style="background-color: #9333ea;">Checkout Sekarang</button>
      </div>
    </div>
  </div>
<!-- Overlay untuk efek gelap cart -->
<div id="overlay-dark" class="overlay-dark"></div>

 


@yield('content')



<!-- Footer -->
<footer class="text-light pt-5 pb-4" style="background: linear-gradient(135deg, #6f42c1, #000000); border-top-left-radius: 12px; border-top-right-radius: 12px;">
  <div class="container">
    <div class="row gy-4">

      <!-- Brand -->
      <div class="col-md-4">
        <h4 class="d-flex align-items-center mb-3 fw-bold">
          <i class="bi bi-disc-fill fs-3 me-2 text-white"></i> CCC RENTAL
        </h4>
        <p class="text-white-50 small">
          CCC RENTAL adalah solusi terbaik untuk kebutuhan kostum Anda. Kami menyediakan berbagai pilihan kostum berkualitas untuk segala acara. Nikmati kemudahan penyewaan dengan proses cepat, harga terjangkau, dan layanan terpercaya.
        </p>
        <div class="d-flex gap-3 mt-3">
          {{-- <a href="#" class="text-white-50 fs-5"><i class="bi bi-facebook"></i></a> --}}
          {{-- <a href="#" class="text-white-50 fs-5"><i class="bi bi-twitter"></i></a> --}}
          <a href="https://www.instagram.com/ccc_rental/" class="text-white-50 fs-5"><i class="bi bi-instagram"></i></a>
          {{-- <a href="#" class="text-white-50 fs-5"><i class="bi bi-linkedin"></i></a> --}}
        </div>
      </div>

      <!-- Links -->
      <div class="col-md-2">
        <h6 class="fw-semibold mb-3">Company</h6>
        <ul class="list-unstyled small text-white-50">
           <li><a href="{{ url('/') }}" class="text-decoration-none text-white-50">Home</a></li>
    <li><a href="{{ url('/') }}" class="text-decoration-none text-white-50">Product</a></li>
    @auth
    <li><a href="{{ route('riwayat.custom') }}" class="text-decoration-none text-white-50">Pengajuan</a></li>
    <li><a href="{{ route('orders.index') }}" class="text-decoration-none text-white-50">Pesanan</a></li>
    <li><a href="{{ route('pengembalian.index') }}" class="text-decoration-none text-white-50">Pengembalian</a></li>
    <li><a href="{{ route('frontend.histori') }}" class="text-decoration-none text-white-50">History Order</a></li>
    @endauth
        </ul>
      </div>

      <div class="col-md-2">
        <h6 class="fw-semibold mb-3">Customer Services</h6>
        <ul class="list-unstyled small text-white-50">
          <li><a href="#" class="text-decoration-none text-white-50">My Account</a></li>

        </ul>
      </div>

      

      <div class="col-md-2">
        <h6 class="fw-semibold mb-3">Info kontak</h6>
        <ul class="list-unstyled small text-white-50">
          <li><i class="bi bi-telephone me-2"></i> +62 851-6100-9881</li>
          {{-- <li><i class="bi bi-envelope me-2"></i> itachi@gmail.com</li> --}}
          <li><i class="bi bi-geo-alt me-2"></i> Graha Sevilla Blok T15/03 , RT02 RW07, Kecamatan Panongan, Kabupaten Tangerang, Banten</li>
        </ul>
      </div>
    </div>

    <hr class="border-white mt-5 mb-3" style="opacity: 0.1;">
    <p class="text-center small text-white-50 mb-0">CCC RENTAL</p>
  </div>
</footer>


<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.clientKey') }}"></script>
<!-- Bootstrap & JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('front/js/script.js') }}"></script>
<script src="{{ asset('front/js/product.js') }}?v={{ filemtime(public_path('front/js/product.js')) }}"></script>

</body>
</html>
