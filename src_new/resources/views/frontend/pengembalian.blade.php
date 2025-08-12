@extends('layouts.app')
@section('content')

@php
    /** hitung telat (bisa dipakai ulang) */
    function terlambat($tglBatas) {
        return now()->diffInDays(\Carbon\Carbon::parse($tglBatas), false) * -1;
    }
@endphp

<section class="hero-bg-wrapper py-5">
  <div class="container-fluid px-4">
    <div class="hero-card d-flex flex-column flex-md-row align-items-center
                justify-content-between gap-4 rounded-4 shadow p-4">

      {{-- GAMBAR KIRI --}}
      <div class="hero-image-wrapper d-flex justify-content-center align-items-end flex-shrink-0"
           style="max-width:350px">
        <img src="{{ asset('front/assets/anime.png') }}" alt="cosplayer"
             class="hero-image w-100" loading="lazy">
      </div>

      {{-- KONTEN KANAN --}}
      <div class="hero-text flex-grow-1 w-100" style="overflow-x:auto">
        <h1 class="fw-bold display-5 gradient-futuristic">Pengembalian</h1>
        <p class="mb-4">
            Berikut daftar penyewaan yang harus dikembalikan sebelum/sejak
            batas sewa.
        </p>

        {{-- ALAMAT TOKO --}}
        <div class="mb-4">
          <h6 class="fw-semibold mb-1">
            <i class="bi bi-geo-alt-fill me-1"></i>Alamat Toko:
          </h6>
          <p class="mb-2">
            Jl. Citra Raya Boulevard No.01 Blok S No.25, Panongan,
            Kab. Tangerang, Banten 15711
          </p>
          <a href="https://maps.app.goo.gl/m1haojAtcU1XHFWZ6"
             target="_blank" class="btn btn-sm but-alamat">
               <i class="bi bi-map-fill me-1"></i>Cek di Google Maps
          </a>
        </div>

        {{-- KARTU HORIZONTAL --}}
        @if($pengembalians->count())
        <div class="scrolling-wrapper d-flex flex-row gap-3" style="overflow-x:auto">

          @foreach ($pengembalians as $row)
            @php
                $order          = $row->order;
                $telatHari      = terlambat($order->tanggal_batas_sewa);
                $ringkasanKostum = $order->orderItems
                    ->map(fn($i)=>optional($i->kostums)->nama_kostum)
                    ->filter()
                    ->join(', ');
            @endphp

            <div class="card shadow-sm border-0 p-3 border-danger"
                 style="min-width:250px">
              <div class="card-body">

                <h6 class="fw-semibold mb-2">
                    ID: <span class="text-muted">#{{ $order->nomor_pesanan }}</span>
                </h6>

                <p class="mb-1 small">
                    <i class="bi bi-calendar-event me-1"></i>
                    Deadline:
                    {{ \Carbon\Carbon::parse($order->tanggal_batas_sewa)->translatedFormat('d F Y') }}
                </p>

                <p class="mb-1 small">
                    <i class="bi bi-archive me-1"></i>
                    {{ \Illuminate\Support\Str::limit($ringkasanKostum,30,'…') }}
                </p>

                <p class="mb-1">
                    <i class="bi bi-cash-coin me-1"></i>
                    <strong>Rp{{ number_format($order->total_harga,0,',','.') }}</strong>
                </p>

                @if ($telatHari > 0)
                  <p class="mb-1 small text-danger fw-semibold">
                    <i class="bi bi-clock-fill me-1"></i>
                    Terlambat {{ $telatHari }} hari
                  </p>
                @endif

                <span class="badge bg-danger">{{ $row->status }}</span><br>

                <button class="btn btn-sm btn-gradient-purple mt-3"
                        data-bs-toggle="modal"
                        data-bs-target="#modalPengembalian{{ $row->id }}">
                    Lihat Detail
                </button>
              </div>
            </div>


            {{-- ============ MODAL DETAIL ============ --}}
            <div class="modal fade" id="modalPengembalian{{ $row->id }}"
                 tabindex="-1" aria-hidden="true">
              <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content rounded-4 shadow border-0">

                  {{-- HEADER --}}
                  <div class="modal-header bg-gradient-ungu text-white rounded-top-4">
                    <h5 class="modal-title fw-bold">
                        Detail Pengembalian (#{{ $order->nomor_pesanan }})
                    </h5>
                    <button type="button" class="btn-close btn-close-white"
                            data-bs-dismiss="modal"></button>
                  </div>

                  {{-- BODY --}}
                  <div class="modal-body px-4 py-3">

                    {{-- Tabel kostum --}}
                    <table class="table table-sm align-middle mb-4">
                      <thead class="table-light">
                        <tr><th>Kostum</th><th>Ukuran</th><th>Durasi</th><th>Harga</th></tr>
                      </thead>
                      <tbody>
                        @foreach($order->orderItems as $it)
                          <tr>
                            <td>{{ $it->kostums->nama_kostum ?? '—' }}</td>
                            <td>{{ $it->ukuran }}</td>
                            <td>{{ $it->durasi_sewa }} hr</td>
                            <td>Rp{{ number_format($it->harga_sewa,0,',','.') }}</td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>

                    <div class="row gy-2 mb-2">
                      <div class="col-md-4"><strong>Tgl. Order:</strong><br>
                        <span class="text-muted">
                          {{ \Carbon\Carbon::parse($order->tanggal_order)->translatedFormat('d F Y') }}
                        </span>
                      </div>
                      <div class="col-md-4"><strong>Batas Sewa:</strong><br>
                        <span class="text-muted">
                          {{ \Carbon\Carbon::parse($order->tanggal_batas_sewa)->translatedFormat('d F Y') }}
                        </span>
                      </div>
                      <div class="col-md-4"><strong>Status:</strong><br>
                        <span class="badge bg-danger">{{ $row->status }}</span>
                      </div>
                      <div class="col-md-4"><strong>Total:</strong><br>
                        <span class="text-muted">
                          Rp{{ number_format($order->total_harga,0,',','.') }}
                        </span>
                      </div>
                      <div class="col-md-4"><strong>Denda:</strong><br>
                        <span class="text-muted">
                          Rp{{ number_format($row->denda ?? 0,0,',','.') }}
                        </span>
                      </div>
                    </div>

                    <h6 class="fw-bold mt-3">Alamat Toko:</h6>
                    <p class="text-muted mb-2">{{ $order->alamat_toko }}</p>
                  </div>

                  {{-- FOOTER --}}
                  <div class="modal-footer border-0 justify-content-between">
                    <span class="text-muted">
                      Hubungi admin jika sudah berada di toko.
                    </span>
                    <a href="https://wa.me/6282112125639?text=Halo%20admin%2C%20saya%20sudah%20di%20toko%20dan%20ingin%20mengembalikan%20kostum%20{{ $order->nomor_pesanan }}"
                       target="_blank" class="btn btn-success">
                        <i class="bi bi-whatsapp me-1"></i>Konfirmasi via WhatsApp
                    </a>
                  </div>
                </div>
              </div>
            </div>
            {{-- ============ /MODAL ============ --}}

          @endforeach
        </div><!-- /scrolling-wrapper -->
        @else
            <div class="alert alert-info">Tidak ada pengembalian yang aktif.</div>
        @endif

      </div><!-- /hero-text -->
    </div><!-- /hero-card -->
  </div>
</section>
@endsection
