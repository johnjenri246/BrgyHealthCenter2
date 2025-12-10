<?php $__env->startSection('content'); ?>
<div class="w-full max-w-md bg-white p-8 rounded-lg shadow-lg">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Welcome Back</h1>
        <p class="text-gray-500">Sign in to access resident records</p>
    </div>

    <?php if($errors->has('email') || $errors->has('password')): ?>
        <div class="mb-4 px-3 py-2 rounded border border-red-200 bg-red-50 text-red-700 text-sm">
            <?php echo e($errors->first('email') ?: $errors->first('password')); ?>

        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('login')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Email Address</label>
            <input type="email" name="email" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
            <div class="relative">
                <input id="password" type="password" name="password" class="w-full px-3 py-2 pr-16 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <button type="button" id="togglePassword" class="absolute inset-y-0 right-2 my-auto px-3 py-1 text-sm text-gray-600 rounded hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg id="iconShow" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <svg id="iconHide" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.45 10.45 0 002.458 12C3.732 16.057 7.523 19 12 19c.993 0 1.953-.13 2.863-.372M6.228 6.228A10.45 10.45 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.45 10.45 0 01-1.248 2.527M6.228 6.228L3 3m3.228 3.228l3.181 3.181m8.363 8.363L21 21m-3.228-3.228l-3.181-3.181m0 0a3 3 0 10-4.243-4.243m4.243 4.243L9.88 9.88" />
                    </svg>
                </button>
            </div>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200">
            Login
        </button>
        <div class="flex justify-end mt-1">
        <a href="<?php echo e(route('password.request')); ?>" class="text-xs text-blue-600 hover:underline">Forgot Password?</a>
    </div>
</div>
    </form>

    <div class="mt-6 text-center">
        <a href="<?php echo e(route('home')); ?>" class="text-sm text-gray-500 hover:text-gray-800">← Back to Home</a>
        <span class="mx-2 text-gray-300">|</span>
        <a href="<?php echo e(route('register')); ?>" class="text-sm text-blue-500 hover:text-blue-700">Create Account</a>
    </div>
</div>

<script>
    const passwordInput = document.getElementById('password');
    const toggleButton = document.getElementById('togglePassword');

    if (passwordInput && toggleButton) {
        toggleButton.addEventListener('click', () => {
            const isHidden = passwordInput.type === 'password';
            passwordInput.type = isHidden ? 'text' : 'password';
            document.getElementById('iconShow')?.classList.toggle('hidden', !isHidden);
            document.getElementById('iconHide')?.classList.toggle('hidden', isHidden);
        });
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\brgyhealth-main\resources\views/auth/login.blade.php ENDPATH**/ ?>