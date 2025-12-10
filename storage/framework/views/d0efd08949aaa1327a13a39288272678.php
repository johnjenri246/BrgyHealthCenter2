<?php $__env->startSection('content'); ?>
<h2 class="text-2xl font-bold mb-6 text-gray-800">📋 Pending Change Requests</h2>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full leading-normal">
        <thead>
            <tr>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">User</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Type</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Details</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Document</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <p class="text-gray-900 whitespace-no-wrap font-bold"><?php echo e($req->user->name); ?></p>
                    <p class="text-gray-600 text-xs"><?php echo e($req->user->email); ?></p>
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-bold">
                        <?php echo e($req->request_type); ?>

                    </span>
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <p class="text-gray-900 whitespace-no-wrap"><?php echo e(Str::limit($req->details, 50)); ?></p>
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <?php if($req->document_path): ?>
                        <a href="<?php echo e(asset('storage/' . $req->document_path)); ?>" target="_blank" class="text-blue-600 hover:underline flex items-center">
                            📄 View File
                        </a>
                    <?php else: ?>
                        <span class="text-gray-400">No document</span>
                    <?php endif; ?>
                </td>
                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                    <form action="<?php echo e(route('admin.requests.approve', $req->id)); ?>" method="POST" onsubmit="return confirm('Approve this request?');">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-1 px-3 rounded text-xs">
                            Approve
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="5" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
                    No pending requests found.
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\brgyhealth-main\resources\views/admin/requests.blade.php ENDPATH**/ ?>