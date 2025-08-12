<?php $__env->startSection('content'); ?>
<?php
  function fmt($d){ return \Carbon\Carbon::parse($d)->translatedFormat('d F Y'); }
?>

<section class="py-5">
  <div class="container d-flex justify-content-center">
   <div class="card shadow rounded-4 p-4" style="max-width:700px;width:100%">

    
    <div class="text-center mb-4">
      <div class="rounded-circle overflow-hidden mx-auto" style="width:120px;height:120px">
        <img src="<?php echo e($profile->avatar_url ? asset('storage/'.$profile->avatar_url)
                                           : asset('front/assets/default-avatar.png')); ?>"
             class="w-100 h-100" style="object-fit:cover">
      </div>
      <h4 class="mt-3 fw-semibold" style="color:#6b21a8"><?php echo e($profile->nama_lengkap); ?></h4>
    </div>

    
    <?php if($mode === 'view'): ?>
      <div class="mb-4">
        <div class="d-flex justify-content-between py-2 border-bottom">
          <span class="text-muted">Nama Lengkap</span><span><?php echo e($profile->nama_lengkap); ?></span>
        </div>
        <div class="d-flex justify-content-between py-2 border-bottom">
          <span class="text-muted">Tanggal Lahir</span><span><?php echo e(fmt($profile->tanggal_lahir)); ?></span>
        </div>
        <div class="d-flex justify-content-between py-2 border-bottom">
          <span class="text-muted">Jenis Kelamin</span><span><?php echo e($profile->jenis_kelamin); ?></span>
        </div>
        <div class="d-flex justify-content-between py-2 border-bottom">
          <span class="text-muted">Nomor Telepon</span><span><?php echo e($profile->nomor_telepon); ?></span>
        </div>

        
        <div class="d-flex justify-content-between py-2 border-bottom">
          <span class="text-muted">Instagram</span>
          <span>
            <?php if($profile->instagram): ?>
              <a href="https://instagram.com/<?php echo e(ltrim($profile->instagram,'@')); ?>" target="_blank">
                <?php echo e($profile->instagram); ?>

              </a>
            <?php else: ?>
              <em class="text-muted">–</em>
            <?php endif; ?>
          </span>
        </div>

        <div class="d-flex justify-content-between py-2 border-bottom">
          <span class="text-muted">Email</span><span><?php echo e(Auth::user()->email); ?></span>
        </div>
      </div>

      <div class="d-flex justify-content-between">
        <a href="<?php echo e(route('profile.edit')); ?>" class="btn btn-gradient-purple">
          <i class="bi bi-pencil-square me-1"></i>Edit Profil
        </a>
        <form action="<?php echo e(route('logout')); ?>" method="POST"><?php echo csrf_field(); ?>
          <button class="btn btn-outline-danger">
            <i class="bi bi-box-arrow-right me-1"></i>Logout
          </button>
        </form>
      </div>

    
    <?php else: ?>
      
      <?php if($errors->any()): ?>
        <div class="alert alert-danger">
          <ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
        </div>
      <?php endif; ?>

      <form action="<?php echo e(route('profile.update')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
        <div class="mb-3">
          <label class="form-label fw-semibold">Nama Lengkap</label>
          <input type="text" name="nama_lengkap"
                 value="<?php echo e(old('nama_lengkap',$profile->nama_lengkap)); ?>"
                 class="form-control <?php $__errorArgs = ['nama_lengkap'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Nomor Telepon</label>
          <input type="text" name="nomor_telepon"
                 value="<?php echo e(old('nomor_telepon',$profile->nomor_telepon)); ?>"
                 class="form-control <?php $__errorArgs = ['nomor_telepon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Tanggal Lahir</label>
          <input type="date" name="tanggal_lahir"
                 value="<?php echo e(old('tanggal_lahir',$profile->tanggal_lahir)); ?>"
                 class="form-control <?php $__errorArgs = ['tanggal_lahir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Jenis Kelamin</label>
          <select name="jenis_kelamin"
                  class="form-select <?php $__errorArgs = ['jenis_kelamin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
            <option value="Pria"   <?php echo e(old('jenis_kelamin',$profile->jenis_kelamin)=='Pria'?'selected':''); ?>>Pria</option>
            <option value="Wanita" <?php echo e(old('jenis_kelamin',$profile->jenis_kelamin)=='Wanita'?'selected':''); ?>>Wanita</option>
          </select>
        </div>

        
        <div class="mb-3">
          <label class="form-label fw-semibold">Instagram (opsional)</label>
          <input type="text" name="instagram"
                 placeholder="@username"
                 value="<?php echo e(old('instagram',$profile->instagram)); ?>"
                 class="form-control <?php $__errorArgs = ['instagram'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
        </div>

        <div class="mb-4">
          <label class="form-label fw-semibold">Ganti Foto (opsional)</label>
          <input type="file" name="avatar"
                 class="form-control <?php $__errorArgs = ['avatar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
        </div>

        <div class="d-flex justify-content-between">
          <a href="<?php echo e(route('profile.show')); ?>" class="btn btn-outline-secondary">Batal</a>
          <button class="btn btn-gradient-purple px-4">Simpan</button>
        </div>
      </form>
    <?php endif; ?>

   </div>
  </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/frontend/profile.blade.php ENDPATH**/ ?>