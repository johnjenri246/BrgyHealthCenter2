<?php $__env->startSection('content'); ?>
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-800">👥 Master Residents List</h2>
    <a href="<?php echo e(route('admin.residents.create')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
        + Add New Resident
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full leading-normal">
        <thead>
            <tr>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Name</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Age / Gender</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Health Status</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $residents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <div class="font-bold text-gray-900"><?php echo e($resident->name); ?></div>
                    <div class="text-xs text-gray-500"><?php echo e($resident->contact_number ?? 'No contact'); ?></div>
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <?php echo e($resident->age); ?> / <span class="capitalize"><?php echo e($resident->gender); ?></span>
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <?php if($resident->is_sick): ?> 
                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-bold">Sick</span> 
                    <?php endif; ?>
                    <?php if($resident->is_pregnant): ?> 
                        <span class="bg-pink-100 text-pink-800 px-2 py-1 rounded-full text-xs font-bold">Pregnant</span> 
                    <?php endif; ?>
                    <?php if(!$resident->is_sick && !$resident->is_pregnant): ?> 
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-bold">Healthy</span> 
                    <?php endif; ?>
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <div class="flex space-x-2">
                        
                        <a href="<?php echo e(route('admin.residents.show', $resident->id)); ?>" class="text-blue-600 hover:text-blue-900 font-medium">View</a>
                        
                        <span class="text-gray-300">|</span>
                        
                        
                        <a href="<?php echo e(route('admin.residents.edit', $resident->id)); ?>" class="text-yellow-600 hover:text-yellow-900 font-medium">Edit</a>
                        
                        <span class="text-gray-300">|</span>
                        
                        
                        <form action="<?php echo e(route('admin.residents.archive', $resident->id)); ?>" method="POST" onsubmit="return confirm('Are you sure you want to archive this resident?');">
                            <?php echo csrf_field(); ?>
                            <button class="text-red-600 hover:text-red-900 font-medium">Archive</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <div class="p-4"><?php echo e($residents->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\brgyhealth-main\resources\views/admin/residents.blade.php ENDPATH**/ ?>