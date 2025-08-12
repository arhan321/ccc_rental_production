@extends('layouts.app')


@section('content')



<section id="home" class="hero-bg-wrapper py-5">
  <div class="container px-4">
    <div class="hero-card d-flex flex-column flex-md-row align-items-center justify-content-between gap-4 rounded-4 shadow p-4">

      <!-- TEKS -->
      <div class="hero-text flex-grow-1 text-md-start text-start w-100">
        <h1 class="fw-bold display-4 text-md-start text-start gradient-futuristic">
          Sewa Baju Cosplay Kerenmu Sekarang & Tampil Percaya Diri!
        </h1>

        <!-- Toggle Info -->
         <button class="btn-toggle-info mt-3 text-md-start text-start"
                type="button" data-bs-toggle="collapse" data-bs-target="#infoSewa" aria-expanded="false">
          <i class="bi bi-info-circle-fill me-2 fs-5"></i> Bagaimana cara sewa?
        </button> 

        <!-- Info Penyewaan -->
      <div class="collapse mt-3 transition-collapse" id="infoSewa">
        <ul class="list-unstyled ps-0">
          <li class="d-flex gap-2 mb-2">
            <i class="bi bi-person-check-fill text-purple-light mt-1"></i>
            <div>Daftar terlebih dahulu dan login melalui menu <span class="fw-semibold">Login</span>.</div>
          </li>
          <li class="d-flex gap-2 mb-2">
            <i class="bi bi-cart-check-fill text-purple-light mt-1"></i>
            <div>Kamu bisa menyewa langsung dari produk yang tersedia, atau <span class="fw-semibold">ajukan kostum custom</span> sesuai keinginanmu.</div>
          </li>
          <li class="d-flex gap-2 mb-2">
            <i class="bi bi-send-check-fill text-purple-light mt-1"></i>
            <div>Setelah pengajuan disetujui, pesanan akan langsung diproses</div>
          </li>
          <li class="d-flex gap-2">
            <i class="bi bi-clock-fill text-purple-light mt-1"></i>
            <div>Durasi minimal sewa adalah <span class="fw-semibold">3 hari</span>.</div>
          </li>
        </ul>
      </div>

      <p class="mt-3 mb-2 text-secondary">
        Ingin kostum karakter favoritmu yang belum ada di katalog? Isi formulir berikut dan ajukan kostum custom sesuai kebutuhanmu!
      </p>


        <!-- CTA -->
        @auth
        <a href="{{ url('/ajukan-kostum') }}" class="btn text-white mt-3" style="background-color: #a259ff;"> Ajukan Kostum Custom Sekarang</a>
        @endauth
        @guest
        <a href="{{ route('login') }}" class="btn text-white mt-3" style="background-color: #a259ff;">
            Ajukan Kostum Custom Sekarang
        </a>
        @endguest
      </div>

      <!-- GAMBAR -->
      <div class="hero-image-wrapper d-flex justify-content-center align-items-end flex-shrink-0" style="max-width: 350px;">
        <img src="{{ asset('front/assets/her.png') }}" alt="cosplayer" class="hero-image w-100" style="object-fit: contain;">
      </div>
      

    </div>
  </div>
</section>


<!-- Rules penyewaan -->
<section id="Rules" class="py-5 px-3" style="background: linear-gradient(135deg, #1f0036, #2a004d);">
  <div class="container">

    <div class="row">
      <!-- Left Content -->
      <div class="col-12 col-md-6 text-white mb-3">
        <h2 class="fw-bold">Rules Penyewaan</h2>
        <p class="text-white-50">Silakan baca aturan dan proses sebelum menyewa kostum cosplay.</p>

        <!-- Toggle Button -->
  <button class="btn btn-outline-light fw-bold mt-3 d-flex align-items-center gap-2" 
          type="button" 
          data-bs-toggle="collapse" 
          data-bs-target="#alurPenyewaan" 
          aria-expanded="false" 
          aria-controls="alurPenyewaan">
    Lihat Alur Penyewaan Kostum
    <i class="bi bi-chevron-down transition" id="arrowIcon"></i>
  </button>
        </div>
      </div>

    <div class="collapse" id="alurPenyewaan">
     
      <div class="mt-4 pt-2">
        <div class="row gx-3 gy-4">
        
          <!-- STEP 1 -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="step-box text-white p-4 rounded-4 shadow h-100" style="background-color: #6b21a8;">
              <div class="d-flex align-items-center mb-3">
                <div class="rounded-circle bg-white text-purple fw-bold d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">1</div>
                <i class="bi bi-person-plus fs-4 text-white"></i>
              </div>
              <h6 class="fw-bold">Login / Register</h6>
              <p class="small text-white-50 mb-0">Buat akun atau masuk untuk mulai menyewa kostum cosplay.</p>
            </div>
          </div>

          <!-- STEP 2 -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="step-box text-white p-4 rounded-4 shadow h-100" style="background-color: #6b21a8;">
              <div class="d-flex align-items-center mb-3">
                <div class="rounded-circle bg-white text-purple fw-bold d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">2</div>
                <i class="bi bi-search fs-4 text-white"></i>
              </div>
              <h6 class="fw-bold">Lihat & Pilih Kostum</h6>
              <p class="small text-white-50 mb-0">Jelajahi katalog produk dan pilih kostum favoritmu.</p>
            </div>
          </div>


          <!-- STEP 4 -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="step-box text-white p-4 rounded-4 shadow h-100" style="background-color: #6b21a8;">
              <div class="d-flex align-items-center mb-3">
                <div class="rounded-circle bg-white text-purple fw-bold d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">3</div>
                <i class="bi bi-cart-plus fs-4 text-white"></i>
              </div>
              <h6 class="fw-bold">Keranjang & Qty</h6>
              <p class="small text-white-50 mb-0">Atur durasi penyewaan minimal 3 hari dan jika ingin tambah maka kelipatan 3</p>
            </div>
          </div>

          <!-- STEP 5 -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="step-box text-white p-4 rounded-4 shadow h-100" style="background-color: #6b21a8;">
              <div class="d-flex align-items-center mb-3">
                <div class="rounded-circle bg-white text-purple fw-bold d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">4</div>
                <i class="bi bi-credit-card fs-4 text-white"></i>
              </div>
              <h6 class="fw-bold">Checkout & Bayar</h6>
              <p class="small text-white-50 mb-0">Lakukan pembayaran untuk konfirmasi pesanan kostummu.</p>
            </div>
          </div>

          <!-- STEP 6 -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="step-box text-white p-4 rounded-4 shadow h-100" style="background-color: #6b21a8;">
              <div class="d-flex align-items-center mb-3">
                <div class="rounded-circle bg-white text-purple fw-bold d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">5</div>
                <i class="bi bi-box-seam fs-4 text-white"></i>
              </div>
              <h6 class="fw-bold">Persiapan Pesanan</h6>
              <p class="small text-white-50 mb-0">Admin akan menyiapkan produkmu setelah pembayaran terkonfirmasi.</p>
            </div>
          </div>

          <!-- STEP 7 -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="step-box text-white p-4 rounded-4 shadow h-100" style="background-color: #6b21a8;">
              <div class="d-flex align-items-center mb-3">
                <div class="rounded-circle bg-white text-purple fw-bold d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">6</div>
                <i class="bi bi-geo-alt fs-4 text-white"></i>
              </div>
              <h6 class="fw-bold">Siap Diambil</h6>
              <p class="small text-white-50 mb-0">Kostum siap diambil di store kami. Alamat akan muncul di halaman pesanan.</p>
            </div>
          </div>

        </div>
      </div>
    </div>

  </div>
</section>
<!-- Rules penyewaan -->


<!-- Product -->

<section id="product" class="py-5 bg-light">
    <div class="container px-4">
        <div class="row mb-4">
            <!-- Sidebar Filter -->
            <div class="col-md-3">
                <h5 class="fw-bold">Filter Kostum</h5>
                <div class="border border-2 rounded-4 p-3 mb-4" style="border-radius: 20px !important;">
                    <h6 class="fw-semibold mb-3">Kategori</h6>
                    <form method="GET" action="{{ route('home') }}">
                        <div class="form-check custom-radio-purple mb-2">
                            <input class="form-check-input" type="radio" name="filterCategory" id="Semua" value="Semua" {{ request('filterCategory') == 'Semua' ? 'checked' : '' }}>
                            <label class="form-check-label" for="Semua">Semua</label>
                        </div>
                        @foreach($categories as $category)
                            <div class="form-check custom-radio-purple mb-2">
                                <input class="form-check-input" type="radio" name="filterCategory" id="{{ $category->id }}" value="{{ $category->id }}" {{ request('filterCategory') == $category->id ? 'checked' : '' }}>
                                <label class="form-check-label" for="{{ $category->id }}">{{ $category->name }}</label>
                            </div>
                        @endforeach
                        <button type="submit" class="btn btn-primary mt-3">Filter</button>
                    </form>
                </div>
            </div>

            <!-- Produk -->
            <div class="col-md-9">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-column flex-md-row gap-3">
                    <div class="text-muted" id="productCount">Menampilkan <strong>{{ count($kostums) }}</strong> produk</div>
                </div>
                <div class="row g-4" id="productList">
                    @foreach($kostums as $kostum)
                    @php
                    // Kostum sedang terbooking?
                    $locked = $kostum->status === 'Terbooking';
                @endphp
                <div class="col-12 col-sm-6 col-lg-4" 
     data-kostums-id="{{ $kostum->id }}"
     data-category="{{ $kostum->category->name }}" 
     data-price="{{ $kostum->harga_sewa }}" 
     data-size="{{ $kostum->ukuran }}" 
     data-status="{{ $locked ? 'booked' : 'available' }}">
    <div class="card h-100 shadow-sm">
        <img src="{{ asset('storage/' . $kostum->image) }}" class="card-img-top" alt="{{ $kostum->nama_kostum }}">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="card-title fw-semibold mb-0">{{ $kostum->nama_kostum }}</h6>
                <span class="badge {{ $kostum->status == 'Tersedia' ? 'bg-purple-available' : 'bg-purple-booked' }}">
                    {{ $kostum->status }}
                </span>
            </div>
            <p class="mb-1 text-muted small">Kategori: <strong>{{ $kostum->category->name }}</strong></p>
            <p class="card-text small text-muted mb-1 product-size">Ukuran: {{ $kostum->ukuran }}</p>
            <p class="card-text small text-muted">{{ $kostum->deskripsi }}</p>
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-bold text-purple">Rp{{ number_format($kostum->harga_sewa, 0, ',', '.') }}</span>
                <button
        class="btn btn-outline-dark btn-sm
               {{ $locked ? 'disabled opacity-50 cursor-not-allowed' : '' }}"
        {{ $locked ? 'disabled' : '' }}>
        Sewa Sekarang
    </button>
            </div>
        </div>
    </div>
</div>
                    @endforeach
                </div> <!-- end row -->
            </div>
        </div>
    </div>
</section>





<!-- Product -->
 @endsection