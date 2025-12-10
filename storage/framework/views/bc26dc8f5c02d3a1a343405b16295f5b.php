<?php $__env->startSection('title', 'Make an Appointment'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto">
    <h2 class="text-4xl font-extrabold text-gray-800 mb-8">🏥 Clinic Appointment Booking</h2>

    <?php if(session('success')): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="bg-white p-8 rounded-xl shadow-2xl mb-12 border-t-4 border-blue-500">
        <h3 class="text-2xl font-semibold text-gray-700 mb-6">Schedule New Appointment</h3>
        
        <form action="<?php echo e(route('appointments.store')); ?>" method="POST">
            <?php echo csrf_field(); ?> <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <input type="date" name="appointmentDate" required class="w-full px-4 py-2 border rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Time</label>
                    <select name="appointmentTime" required class="w-full px-4 py-2 border rounded-lg">
                        <option value="09:00">09:00 AM</option>
                        <option value="10:00">10:00 AM</option>
                        <option value="13:00">01:00 PM</option>
                        </select>
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
                <textarea name="reason" rows="3" required class="w-full px-4 py-2 border rounded-lg"></textarea>
            </div>

            <div class="mt-8">
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg">
                    Book Appointment
                </button>
            </div>
        </form>
    </div>

    <h3 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">📅 Your Scheduled Appointments</h3>
    
    <div class="space-y-4">
        <?php $__empty_1 = true; $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="p-6 bg-white rounded-xl shadow-lg border-l-4 border-blue-500">
                <div class="flex justify-between">
                    <p class="text-lg font-bold text-gray-800"><?php echo e(\Carbon\Carbon::parse($appt->date)->format('M d, Y')); ?> at <?php echo e($appt->time); ?></p>
                    <span class="px-3 py-1 text-xs font-bold bg-yellow-100 text-yellow-800 rounded-full">
                        <?php echo e($appt->status); ?>

                    </span>
                </div>
                <p class="text-gray-600 mt-2"><?php echo e($appt->reason); ?></p>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="p-6 bg-gray-100 rounded-xl text-center text-gray-500">
                You have no upcoming appointments.
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\brgyhealth-main\resources\views/appointment.blade.php ENDPATH**/ ?>