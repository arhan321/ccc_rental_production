@extends('layouts.app')

@section('content')
<section class="hero-bg-wrapper py-5">
  <div class="container-fluid px-4">
    <div class="hero-card d-flex flex-column flex-md-row align-items-center justify-content-between gap-4 rounded-4 shadow p-4">

      <!-- GAMBAR KIRI -->
      <div class="hero-image-wrapper d-flex justify-content-center align-items-end flex-shrink-0" style="max-width: 350px;">
        <img src="{{ asset('front/assets/anime.png') }}" alt="pengajuan custom" class="hero-image w-100" loading="lazy">
      </div>

      <!-- KONTEN KANAN -->
      <div class="hero-text flex-grow-1 w-100" style="overflow-x: auto;">
        <h1 class="fw-bold display-5 gradient-futuristic">Riwayat Pengajuan Custom</h1>
        <p class="mb-4">Berikut adalah daftar pengajuan kostum custom milikmu. Scroll ke kanan untuk melihat semua riwayat.</p>

        <div class="scrolling-wrapper d-flex flex-row gap-3" style="overflow-x: auto;">

          @foreach ($requests as $item)
          <div class="card shadow-sm border-0 p-3" style="min-width: 250px;">
            <div class="card-body">
              <p class="mb-1"><strong>Nama:</strong><br><span class="text-muted">{{ $item->nama }}</span></p>
              <p class="mb-1"><i class="bi bi-phone me-1"></i>{{ $item->telepon }}</p>
              <p class="mb-1"><i class="bi bi-rulers me-1"></i>Ukuran: <strong>{{ $item->ukuran }}</strong></p>
              <span class="badge 
                @if($item->status === 'Menunggu') bg-secondary
                @elseif($item->status === 'Diproses') bg-warning text-dark
                @elseif($item->status === 'Selesai') bg-success
                @elseif($item->status === 'Ditolak') bg-danger
                @endif">
                {{ $item->status }}
              </span><br>
              <button class="btn btn-sm btn-gradient-purple mt-3" data-bs-toggle="modal" data-bs-target="#modalCustom{{ $item->id }}">Lihat Detail</button>
            </div>
          </div>
          @endforeach

        </div>
      </div>
    </div>
  </div>
</section>

<!-- MODAL DETAIL CUSTOM -->
@foreach ($requests as $item)
<div class="modal fade" id="modalCustom{{ $item->id }}" tabindex="-1" aria-labelledby="modalCustomLabel{{ $item->id }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content rounded-4 shadow border-0">
      <div class="modal-header bg-gradient-ungu text-white rounded-top-4">
        <h5 class="modal-title fw-bold" id="modalCustomLabel{{ $item->id }}">Detail Pengajuan Custom</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body px-4 py-3">
        <div class="row mb-3">
          <div class="col-md-6">
            <strong>Nama:</strong><br>
            <span class="text-muted">{{ $item->nama }}</span>
          </div>
          <div class="col-md-6">
            <strong>No. Telepon:</strong><br>
            <span class="text-muted">{{ $item->telepon }}</span>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <strong>Ukuran:</strong><br>
            <span class="text-muted">{{ $item->ukuran }}</span>
          </div>
          <div class="col-md-6">
            <strong>Status:</strong><br>
            <span class="badge bg-purple-booked">{{ $item->status }}</span>
          </div>
        </div>

        @if ($item->referensi)
        <div class="mb-3">
          <strong>Referensi Desain:</strong><br>
          <a href="{{ asset('storage/' . $item->referensi) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-1">
            <i class="bi bi-image"></i> Lihat Gambar
          </a>
        </div>
        @endif

        @if ($item->template)
        <div class="mb-3">
          <strong>File Template:</strong><br>
          <a href="{{ asset('storage/' . $item->template) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-1">
            <i class="bi bi-file-earmark"></i> Lihat File
          </a>
        </div>
        @endif

        @if ($item->catatan)
        <div class="mb-3">
          <strong>Catatan Tambahan:</strong><br>
          <p class="text-muted">{{ $item->catatan }}</p>
        </div>
        @endif

        <div>
          <strong>Tanggal Pengajuan:</strong><br>
          <span class="text-muted">{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y H:i') }}</span>
        </div>
      </div>
    </div>
  </div>
</div>
@endforeach

@endsection