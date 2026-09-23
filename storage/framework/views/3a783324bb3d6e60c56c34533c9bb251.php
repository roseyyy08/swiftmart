<?php $__env->startSection('title', 'Login Admin - SwiftMart'); ?>

<?php $__env->startSection('content'); ?>
    <div class="guest-card text-start">
        <h5 class="mb-4 text-center" style="font-family:'Playfair Display',serif; font-weight:700;">Login Admin</h5>

        <form method="POST" action="<?php echo e(route('login')); ?>">
            <?php echo csrf_field(); ?>

            <div class="mb-3">
                <label for="email" class="form-label"><?php echo e(__('Email Address')); ?></label>
                <input id="email" type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="email" value="<?php echo e(old('email')); ?>" required autocomplete="email" autofocus>
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="invalid-feedback d-block" role="alert"><strong><?php echo e($message); ?></strong></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label"><?php echo e(__('Password')); ?></label>
                <input id="password" type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="password" required autocomplete="current-password">
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="invalid-feedback d-block" role="alert"><strong><?php echo e($message); ?></strong></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>
                <label class="form-check-label" for="remember"><?php echo e(__('Remember Me')); ?></label>
            </div>

            <button type="submit" class="btn-swift-primary w-100 mb-2"><?php echo e(__('Login')); ?></button>

            <?php if(Route::has('password.request')): ?>
                <div class="text-center">
                    <a href="<?php echo e(route('password.request')); ?>" class="text-muted small"><?php echo e(__('Forgot Your Password?')); ?></a>
                </div>
            <?php endif; ?>
        </form>
    </div>

    <div class="mt-3">
        <a href="<?php echo e(url('/')); ?>" class="text-muted small">&larr; Kembali ke Halaman Utama</a>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\swiftmart\resources\views/auth/login.blade.php ENDPATH**/ ?>