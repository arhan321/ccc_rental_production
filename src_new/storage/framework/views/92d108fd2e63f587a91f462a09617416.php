<?php $__env->startSection('content'); ?>
<?php
    /** util badge status (jika suatu saat ingin dipakai) */
    function badgeHist($st)
    {
        return [
            'Selesai' => 'bg-success',
            'Dibatalkan' => 'bg-danger',
        ][$st] ?? 'bg-secondary';
    }
?>

<section class="hero-bg-wrapper py-5">
    <div class="container-fluid px-4">

        <div class="hero-card d-flex flex-column flex-md-row align-items-center
                    justify-content-between gap-4 rounded-4 shadow p-4">

            
            <div class="hero-image-wrapper d-flex justify-content-center align-items-end flex-shrink-0"
                 style="max-width:350px">
                <img src="<?php echo e(asset('front/assets/anime.png')); ?>" alt="history image"
                     class="hero-image w-100" loading="lazy">
            </div>

            
            <div class="hero-text flex-grow-1 w-100" style="overflow-x:auto">
                <h1 class="fw-bold display-5 gradient-futuristic">Riwayat Penyewaanmu</h1>
                <p class="mb-4">
                    Berikut adalah daftar penyewaan yang telah kamu selesaikan.
                    Klik <em>Lihat Detail</em> untuk melihat rincian produk yang disewa.
                </p>

                
                <?php if($orders->count()): ?>
                <div class="scrolling-wrapper d-flex flex-row gap-3" style="overflow-x:auto">

                    <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $kostumList = $order->orderItems
                                ->map(fn ($i) => optional($i->kostums)->nama_kostum)
                                ->filter()
                                ->join(', ');
                        ?>

                        <div class="card shadow-sm border-0 p-3" style="min-width:250px">
                            <div class="card-body">
                                <h6 class="fw-semibold mb-2">
                                    ID: <span class="text-muted">#<?php echo e($order->nomor_pesanan); ?></span>
                                </h6>

                                <p class="mb-1 small">
                                    <i class="bi bi-calendar-check me-1"></i>
                                    <?php echo e(\Carbon\Carbon::parse($order->tanggal_order)->translatedFormat('d F Y')); ?>

                                </p>

                                <p class="mb-1 small">
                                    <i class="bi bi-archive me-1"></i>
                                    <?php echo e(\Illuminate\Support\Str::limit($kostumList, 30, '…')); ?>

                                    <?php if($order->orderItems->count() > 1): ?>
                                        <span class="text-muted">
                                            (+
                                            <?php echo e($order->orderItems->count() - 1); ?>

                                            lainnya)
                                        </span>
                                    <?php endif; ?>
                                </p>

                                <p class="mb-1">
                                    <i class="bi bi-cash-stack me-1"></i>
                                    <strong>
                                        Rp<?php echo e(number_format($order->total_harga,0,',','.')); ?>

                                    </strong>
                                </p>

                                <span class="badge bg-success">Selesai</span><br>

                                <button class="btn btn-sm btn-gradient-purple mt-3"
                                        data-bs-toggle="modal"
                                        data-bs-target="#detailOrder<?php echo e($order->id); ?>">
                                    Lihat Detail
                                </button>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div><!-- /scrolling-wrapper -->
                <?php else: ?>
                    <div class="alert alert-info mt-3">
                        Belum ada riwayat penyewaan.
                    </div>
                <?php endif; ?>
            </div><!-- /hero-text -->
        </div><!-- /hero-card -->
    </div>
</section>



<?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="detailOrder<?php echo e($order->id); ?>" tabindex="-1"
     aria-labelledby="detailOrderLabel<?php echo e($order->id); ?>" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content rounded-4 shadow border-0">
      
      <div class="modal-header bg-gradient-ungu text-white rounded-top-4">
        <h5 class="modal-title fw-bold" id="detailOrderLabel<?php echo e($order->id); ?>">
            Detail Pesanan (#<?php echo e($order->nomor_pesanan); ?>)
        </h5>
        <button type="button" class="btn-close btn-close-white"
                data-bs-dismiss="modal"></button>
      </div>

      
      <div class="modal-body px-4 py-3">
        
        <table class="table table-sm align-middle mb-4">
          <thead class="table-light">
            <tr>
              <th>Kostum</th><th>Ukuran</th><th>Durasi (hari)</th><th>Harga</th>
            </tr>
          </thead>
          <tbody>
            <?php $__currentLoopData = $order->orderItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                  <td><?php echo e($it->kostums->nama_kostum ?? '—'); ?></td>
                  <td><?php echo e($it->ukuran); ?></td>
                  <td><?php echo e($it->durasi_sewa); ?></td>
                  <td>Rp<?php echo e(number_format($it->harga_sewa,0,',','.')); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
        </table>

        
        <div class="row gy-2 mb-3">
          <div class="col-md-4"><strong>Tanggal Order:</strong><br>
            <span class="text-muted">
              <?php echo e(\Carbon\Carbon::parse($order->tanggal_order)->translatedFormat('d F Y')); ?>

            </span>
          </div>
          <div class="col-md-4"><strong>Batas Sewa:</strong><br>
            <span class="text-muted">
              <?php echo e(\Carbon\Carbon::parse($order->tanggal_batas_sewa)->translatedFormat('d F Y')); ?>

            </span>
          </div>
          <div class="col-md-4"><strong>Status:</strong><br>
            <span class="badge bg-success">Selesai</span>
          </div>
        </div>

        <h6 class="fw-bold">Alamat Toko:</h6>
        <p class="text-muted"><?php echo e($order->alamat_toko); ?></p>
      </div>

      
      <div class="modal-footer border-0">
        <div class="me-auto fw-bold">
          Total Pembayaran:
          <span class="text-success">
            Rp<?php echo e(number_format($order->total_harga,0,',','.')); ?>

          </span>
        </div>
        <button type="button" class="btn btn-gradient-purple px-4"
                data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/frontend/histori.blade.php ENDPATH**/ ?>