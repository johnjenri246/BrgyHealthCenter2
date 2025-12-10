<?php $__env->startSection('content'); ?>
<h2 class="text-2xl font-bold mb-6">📅 Appointment Requests</h2>
<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full leading-normal">
        <thead>
            <tr>
                <th class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">User</th>
                <th class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Date/Time</th>
                <th class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Reason</th>
                <th class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                <th class="px-5 py-3 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td class="px-5 py-5 border-b bg-white text-sm"><?php echo e($appt->user->name); ?></td>
                <td class="px-5 py-5 border-b bg-white text-sm"><?php echo e($appt->date); ?> <br> <?php echo e($appt->time); ?></td>
                <td class="px-5 py-5 border-b bg-white text-sm"><?php echo e($appt->reason); ?></td>
                <td class="px-5 py-5 border-b bg-white text-sm">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($appt->status === 'Approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'); ?>">
                        <?php echo e($appt->status); ?>

                    </span>
                </td>
                <td class="px-5 py-5 border-b bg-white text-sm">
                    <?php if($appt->status !== 'Approved'): ?>
                    <form action="<?php echo e(route('admin.appointments.approve', $appt->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button class="text-green-600 hover:text-green-900 font-bold">Approve</button>
                    </form>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\brgyhealth-main\resources\views/admin/appointments.blade.php ENDPATH**/ ?>