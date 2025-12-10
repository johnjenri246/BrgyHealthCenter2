<?php $__env->startSection('content'); ?>
<div class="w-full max-w-md bg-white p-8 rounded-lg shadow-lg">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Verify OTP</h1>
        <p class="text-gray-500">We sent a code to your email</p>
    </div>

    <?php if($errors->any()): ?>
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <?php echo e($errors->first()); ?>

        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('otp.check')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Enter OTP Code</label>
            <input type="text" name="otp" placeholder="123456" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-center text-2xl tracking-widest" required>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200">
            Verify & Login
        </button>
    </form>

    <div class="mt-6 text-center">
        <a href="<?php echo e(route('login')); ?>" class="text-sm text-gray-500 hover:text-gray-800">Cancel</a>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\brgyhealth-main\resources\views/auth/otp.blade.php ENDPATH**/ ?>