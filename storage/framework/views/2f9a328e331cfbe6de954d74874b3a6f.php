<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Inventory Item</h2>

    <form action="<?php echo e(route('admin.inventory.update', $medicine->id)); ?>" method="POST">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
        
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Medicine Name</label>
                <input type="text" name="name" value="<?php echo e($medicine->name); ?>" required class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Category</label>
                <input type="text" name="category" value="<?php echo e($medicine->category); ?>" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Stock Quantity</label>
                <input type="number" name="stock" value="<?php echo e($medicine->stock); ?>" required min="0" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Expiration Date</label>
                <input type="date" name="expiration_date" value="<?php echo e(\Carbon\Carbon::parse($medicine->expiration_date)->format('Y-m-d')); ?>" required class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
            <textarea name="description" rows="3" class="w-full border rounded px-3 py-2"><?php echo e($medicine->description); ?></textarea>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="<?php echo e(route('admin.inventory.index')); ?>" class="bg-gray-500 text-white font-bold py-2 px-4 rounded hover:bg-gray-600">Cancel</a>
            <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-6 rounded hover:bg-blue-700">Update Item</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\brgyhealth-main\resources\views/admin/inventory/edit.blade.php ENDPATH**/ ?>