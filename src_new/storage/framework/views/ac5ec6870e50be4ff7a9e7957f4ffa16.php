<?php $__env->startSection('content'); ?>
<div class="login-hero d-flex align-items-center justify-content-center min-vh-100 px-3">
  <div class="login-card p-4 rounded-4 shadow-lg">
    <div class="text-center mb-4">
      <i class="bi bi-person-circle fs-1 text-purple"></i>
      <h4 class="fw-bold mt-2 text-purple">Welcome Back!</h4>
      <p class="small text-secondary">Silakan login untuk melanjutkan</p>
    </div>

    <!-- Login Form -->
    <form action="<?php echo e(route('login')); ?>" method="POST">
      <?php echo csrf_field(); ?>
      <div class="mb-3">
        <label for="email" class="form-label small fw-semibold">Email</label>
        <input type="email" class="form-control custom-focus" id="email" name="email" placeholder="nama@email.com" required value="<?php echo e(old('email')); ?>">
        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
          <div class="text-danger small"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label small fw-semibold">Password</label>
        <input type="password" class="form-control custom-focus" id="password" name="password" placeholder="••••••••" required>
        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
          <div class="text-danger small"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>

      <button type="submit" class="btn w-100 fw-semibold text-white" style="background-color: #6b21a8;">Login</button>
    </form>

    <p class="text-center mt-3 small">Belum punya akun? <a href="<?php echo e(route('register')); ?>" class="text-decoration-none text-purple">Daftar</a></p>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/frontend/login.blade.php ENDPATH**/ ?>