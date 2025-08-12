<?php $__env->startSection('content'); ?>
<div class="container py-5">
  <div class="mx-auto" style="max-width: 720px;">
    <div class="card border-0 shadow rounded-4" style="background-color: #f7f3ff;">
      <div class="card-body p-5">

        <h2 class="mb-4 text-center fw-bold text-purple">Formulir Custom Kostum</h2>

        <form enctype="multipart/form-data" method="POST" action="<?php echo e(route('custom-request.store')); ?>">
          <?php echo csrf_field(); ?>

          <!-- Nama -->
          <div class="mb-3">
            <label for="nama" class="form-label fw-semibold">Nama Lengkap</label>
            <input type="text" class="form-control rounded-3 shadow-sm" id="nama" name="nama" placeholder="Nama lengkap Anda" required>
          </div>

          <!-- Nomor Telepon -->
          <div class="mb-3">
                <label for="telepon" class="form-label fw-semibold">Nomor Telepon / WhatsApp</label>
                <input type="text" class="form-control rounded-3 shadow-sm" id="telepon" name="telepon" placeholder="0812xxxxxxx" required>
            </div>

                <div class="mb-4">
                <label for="referensi" class="form-label fw-semibold text-dark">Upload Referensi Kostum (Gambar)</label>

                <div 
                    class="custom-file-wrapper rounded-3 shadow-sm px-4 py-3 bg-white border border-purple d-flex align-items-center gap-3" 
                    onclick="document.getElementById('referensi').click()" 
                    style="cursor: pointer;">
                    <i class="bi bi-image fs-4 text-purple"></i>
                    <span class="text-muted" id="file-name-label">Pilih file gambar (jpg/png)...</span>
                </div>

                <input 
                    type="file" 
                    class="d-none" 
                    id="referensi" 
                    name="referensi" 
                    accept="image/*"
                    required
                    onchange="showFileName(event)">

                    <small class="text-muted d-block mt-2">Format diperbolehkan: JPG, PNG. Max: 2MB</small>
                </div>


          <!-- Ukuran Kostum -->
          <div class="mb-3">
            <label for="ukuran" class="form-label fw-semibold">Ukuran Kostum</label>
            <select class="form-select rounded-3 shadow-sm" id="ukuran" name="ukuran" onchange="toggleCustomUpload()" required>
              <option value="">-- Pilih Ukuran --</option>
              <option value="S">S</option>
              <option value="M">M</option>
              <option value="L">L</option>
              <option value="XL">XL</option>
              <option value="Custom">Custom</option>
            </select>
          </div>

          <!-- Upload Template Ukuran -->
          <div class="mb-4 d-none" id="upload-template">
            <label for="template" class="form-label fw-semibold text-dark">Upload Template Ukuran</label>

            <div class="custom-file-wrapper rounded-3 shadow-sm px-4 py-3 bg-white border border-purple d-flex align-items-center gap-3" onclick="document.getElementById('template').click()" style="cursor: pointer;">
                <i class="bi bi-file-earmark-arrow-up fs-4 text-purple"></i>
                <span class="text-muted" id="template-file-label">Pilih file ukuran khusus...</span>
            </div>

            <input 
                type="file" 
                class="d-none" 
                id="template" 
                name="template" 
                accept=".pdf,.doc,.docx,.jpg,.png"
                onchange="showTemplateName(event)">

            <small class="text-muted d-block mt-2">Upload file ukuran khusus (PDF, DOC, JPG, PNG)</small>


            <div class="mt-3">
              <a href="<?php echo e(asset('template-ukuran.pdf')); ?>" class="btn btn-sm btn-outline-purple rounded-3" download>
                <i class="bi bi-download me-1"></i> Download Template Ukuran
              </a>
            </div>
          </div>

          <!-- Catatan -->
          <div class="mb-4">
            <label for="catatan" class="form-label fw-semibold">Catatan Tambahan <span class="text-muted">(Opsional)</span></label>
            <textarea class="form-control rounded-3 shadow-sm" id="catatan" name="catatan" rows="3" placeholder="Misal: Nama, Anime / Asal Karakter"></textarea>
          </div>

          <!-- Tombol Submit -->
          <div class="text-end">
            <button type="submit" class="btn btn-purple text-white px-4 py-2 rounded-3">
              <i class="bi bi-send-fill me-1"></i> Kirim Pengajuan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Alert -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 border-0 shadow" style="background-color: #f0f9f4;">
      <div class="modal-body text-center py-5">
        <i class="bi bi-check-circle-fill fs-1 text-success mb-3"></i>
        <h5 class="fw-bold text-success mb-2">Pengajuan Terkirim!</h5>
        <p class="text-muted mb-0">Pengajuan kostum berhasil dikirim. Silakan cek riwayat pengajuan Anda.</p>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    // Toggle field upload template berdasarkan ukuran
    const ukuranSelect = document.getElementById("ukuran");
    const templateSection = document.getElementById("upload-template");

    function toggleCustomUpload() {
      const selected = ukuranSelect.value;
      templateSection.classList.toggle("d-none", selected !== "Custom");
    }

    ukuranSelect.addEventListener("change", toggleCustomUpload);
    toggleCustomUpload(); // panggil awal untuk kondisi default

    // Tampilkan nama file referensi gambar
    window.showFileName = function (event) {
      const file = event.target.files[0];
      const label = document.getElementById("file-name-label");

      if (file) {
        label.textContent = file.name;
        label.classList.remove("text-muted");
        label.classList.add("text-dark");
      }
    };

    // Tampilkan nama file template
    window.showTemplateName = function (event) {
      const file = event.target.files[0];
      const label = document.getElementById("template-file-label");

      if (file) {
        label.textContent = file.name;
        label.classList.remove("text-muted");
        label.classList.add("text-dark");
      }
    };

    // Tampilkan modal sukses jika ada session
    <?php if(session('success')): ?>
      const successModal = new bootstrap.Modal(document.getElementById('successModal'));
      successModal.show();
    <?php endif; ?>
  });
</script>


<style>
  .btn-purple {
    background-color: #a259ff;
    border: none;
  }

  .btn-purple:hover {
    background-color: #893ef5;
  }

  .text-purple {
    color: #a259ff;
  }

  .form-control:focus, .form-select:focus {
    border-color: #a259ff;
    box-shadow: 0 0 0 0.2rem rgba(162, 89, 255, 0.25);
  }
  .border-purple {
    border: 2px dashed #a259ff;
  }

  .custom-file-wrapper {
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
  }

  .custom-file-wrapper:hover {
    border-color: #893ef5;
    box-shadow: 0 0 0 0.2rem rgba(162, 89, 255, 0.15);
  }

  .cursor-pointer {
    cursor: pointer;
  }

  .btn-outline-purple {
    color: #a259ff;
    border: 1.8px solid #a259ff;
    background-color: transparent;
    transition: all 0.3s ease;
  }

  .btn-outline-purple:hover {
    background-color: #a259ff;
    color: #fff;
    border-color: #893ef5;
    box-shadow: 0 0 10px rgba(162, 89, 255, 0.3);
  }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/frontend/ajukan-kostum.blade.php ENDPATH**/ ?>