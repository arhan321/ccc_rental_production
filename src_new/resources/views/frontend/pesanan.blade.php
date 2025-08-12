{{-- resources/views/frontend/pesanan.blade.php --}}
@extends('layouts.app')

@section('content')
@php
    function badge($st) {
        return [
            'Menunggu'      => 'bg-secondary',
            'Diproses'      => 'bg-warning text-dark',
            'Siap Di Ambil' => 'bg-purple-booked',
            'Selesai'       => 'bg-success',
            'Dibatalkan'    => 'bg-danger',
        ][$st] ?? 'bg-secondary';
    }
@endphp

<section class="hero-bg-wrapper py-5">
  <div class="container-fluid px-4">
    <div class="hero-card d-flex flex-column flex-md-row align-items-center
                justify-content-between gap-4 rounded-4 shadow p-4">

      {{-- GAMBAR KIRI --}}
      <div class="hero-image-wrapper d-flex justify-content-center align-items-end
                  flex-shrink-0" style="max-width:350px">
        <img src="{{ asset('front/assets/anime.png') }}" alt="cosplayer"
             class="hero-image w-100" loading="lazy">
      </div>

      {{-- KONTEN KANAN --}}
      <div class="hero-text flex-grow-1 w-100">
        <h1 class="fw-bold display-5 gradient-futuristic">Cek Status Penyewaanmu!</h1>
        <p class="mb-4">
            Berikut adalah daftar pesananmu yang sedang dalam proses penyewaan.
            Scroll ke kanan untuk melihat semua pesanan.
        </p>

        {{-- ===== KARTU PESANAN ===== --}}
        @if ($orders->count())
        <div class="scrolling-wrapper d-flex flex-row gap-3" style="overflow-x:auto">

          @foreach ($orders as $order)
            @php
                $kostumList = $order->orderItems
                    ->map(fn($i) => optional($i->kostums)->nama_kostum)
                    ->filter()
                    ->join(', ');
            @endphp

            <div class="card shadow-sm border-0 p-3" style="min-width:250px">
              <div class="card-body">

                <h6 class="fw-semibold mb-2">
                  ID: <span class="text-muted">#{{ $order->nomor_pesanan }}</span>
                </h6>

                <p class="mb-1 small">
                  <i class="bi bi-calendar-event me-1"></i>
                  {{ \Carbon\Carbon::parse($order->tanggal_order)->translatedFormat('d F Y') }}
                </p>

                <p class="mb-1 small">
                  <i class="bi bi-archive me-1"></i>
                  {{ \Illuminate\Support\Str::limit($kostumList, 30, '…') }}
                  @if ($order->orderItems->count() > 1)
                    <span class="text-muted">
                      (+{{ $order->orderItems->count() - 1 }} lainnya)
                    </span>
                  @endif
                </p>

                <p class="mb-1">
                  <i class="bi bi-cash-coin me-1"></i>
                  <strong>Rp{{ number_format($order->total_harga,0,',','.') }}</strong>
                </p>

                <span class="badge {{ badge($order->status) }}">
                  {{ $order->status }}
                </span><br>

                {{-- ====== BAYAR LAGI (kartu) ====== --}}
                @if($order->status === 'Menunggu')
                  <button class="btn btn-sm btn-gradient-purple mt-3 pay-again"
                          data-order="{{ $order->id }}">
                    Bayar Lagi
                  </button>
                @endif

                <button class="btn btn-sm btn-outline-secondary mt-2"
                        data-bs-toggle="modal"
                        data-bs-target="#modalPesanan{{ $order->id }}">
                  Lihat Detail
                </button>
              </div>
            </div>

            {{-- ===== MODAL DETAIL PESANAN ===== --}}
            <div class="modal fade" id="modalPesanan{{ $order->id }}" tabindex="-1">
              <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content rounded-4 shadow border-0">

                  <div class="modal-header bg-gradient-ungu text-white rounded-top-4">
                    <h5 class="modal-title fw-bold">
                      Detail Pesanan (#{{ $order->nomor_pesanan }})
                    </h5>
                    <button type="button" class="btn-close btn-close-white"
                            data-bs-dismiss="modal"></button>
                  </div>

                  <div class="modal-body px-4 py-3">
                    {{-- tabel kostum --}}
                    <table class="table table-sm align-middle mb-4">
                      <thead class="table-light">
                        <tr><th>Kostum</th><th>Ukuran</th><th>Harga</th></tr>
                      </thead>
                      <tbody>
                        @foreach ($order->orderItems as $it)
                          <tr>
                            <td>{{ $it->kostums->nama_kostum ?? '—' }}</td>
                            <td>{{ $it->ukuran }}</td>
                            <td>Rp{{ number_format($it->harga_sewa,0,',','.') }}</td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>

                    {{-- RINGKASAN --}}
                    <div class="row gy-2 mb-3">
                      <div class="col-md-4"><strong>Tanggal Order:</strong><br>
                        <span class="text-muted">{{ \Carbon\Carbon::parse($order->tanggal_order)->translatedFormat('d F Y') }}</span>
                      </div>
                      <div class="col-md-4"><strong>Batas Sewa:</strong><br>
                        <span class="text-muted">{{ \Carbon\Carbon::parse($order->tanggal_batas_sewa)->translatedFormat('d F Y') }}</span>
                      </div>
                      <div class="col-md-4"><strong>Durasi Sewa:</strong><br>
                        <span class="text-muted">{{ $order->durasi_sewa }} hari</span>
                      </div>
                      <div class="col-md-4"><strong>Status:</strong><br>
                        <span class="badge {{ badge($order->status) }}">{{ $order->status }}</span>
                      </div>
                      <div class="col-md-4"><strong>Total Bayar:</strong><br>
                        <span class="text-muted">Rp{{ number_format($order->total_harga,0,',','.') }}</span>
                      </div>
                    </div>

                    <h6 class="fw-bold">Alamat Toko:</h6>
                    <p class="text-muted">{{ $order->alamat_toko }}</p>
                  </div>

                  <div class="modal-footer border-0">
                    {{-- ====== BAYAR LAGI (modal) ====== --}}
                    @if($order->status === 'Menunggu')
                      <button class="btn btn-gradient-purple pay-again"
                              data-order="{{ $order->id }}">
                        Bayar Lagi
                      </button>
                    @endif

                    <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">Tutup</button>
                  </div>

                </div>
              </div>
            </div>
            {{-- ===== END MODAL ===== --}}
          @endforeach
        </div>
        @else
          <div class="alert alert-info mt-3">Belum ada pesanan yang terdaftar.</div>
        @endif
      </div>
    </div>
  </div>
</section>
@endsection
