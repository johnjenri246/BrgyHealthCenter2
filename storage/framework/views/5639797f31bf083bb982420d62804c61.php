<?php $__env->startSection('content'); ?>
<div class="w-full max-w-md bg-white p-8 rounded-lg shadow-lg">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Forgot Password?</h1>
        <p class="text-gray-500">Enter your email to receive a reset code</p>
    </div>

    <?php if($errors->any()): ?>
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <?php echo e($errors->first()); ?>

        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('password.email')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Email Address</label>
            <input type="email" name="email" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200">
            Send Reset Code
        </button>
    </form>

    <div class="mt-6 text-center">
        <a href="<?php echo e(route('login')); ?>" class="text-sm text-gray-500 hover:text-gray-800">Back to Login</a>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\brgyhealth-main\resources\views/auth/forgot-password.blade.php ENDPATH**/ ?>