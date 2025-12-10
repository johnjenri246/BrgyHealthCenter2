<?php $__env->startSection('title', 'Health Records | Barangay Health System'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto">

    <?php if(session('success')): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if($healthProfile): ?>
    
        
        
        
    
        
        
        
             
        
        
        
        <?php echo $__env->make('partials.health_profile_dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?> 

    <?php else: ?>

        
        
        <div class="max-w-3xl mx-auto mt-6">
            <div class="bg-white p-8 rounded-xl shadow-2xl border-t-4 border-blue-600">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-extrabold text-gray-800">Complete Your Profile</h2>
                    <p class="text-gray-500 mt-2">Please fill out your details to be added to the barangay resident list.</p>
                </div>

                <form action="<?php echo e(route('health_profile.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Full Name</label>
                            
                            <input type="text" name="full_name" value="<?php echo e(Auth::user()->name); ?>" required 
                                   class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Gender</label>
                            <select name="gender" required class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="" disabled selected>Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                    </div>

                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Age</label>
                            <input type="number" name="age" required class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Height (cm)</label>
                            <input type="number" step="0.01" id="height" name="height" required class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="170">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Weight (kg)</label>
                            <input type="number" step="0.01" id="weight" name="weight" required class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="65">
                        </div>
                    </div>

                    
                    <div class="mb-6 p-3 bg-blue-50 rounded-lg text-center hidden" id="bmiPreview">
                        <span class="text-sm text-gray-600">Estimated BMI:</span>
                        <span class="font-bold text-blue-700 text-lg" id="bmiValue">--</span>
                    </div>

                    <script>
                        const hInput = document.getElementById('height');
                        const wInput = document.getElementById('weight');
                        const preview = document.getElementById('bmiPreview');
                        const val = document.getElementById('bmiValue');

                        function calcBMI() {
                            const h = parseFloat(hInput.value) / 100; // cm to m
                            const w = parseFloat(wInput.value);
                            if(h > 0 && w > 0) {
                                const bmi = (w / (h * h)).toFixed(1);
                                val.innerText = bmi;
                                preview.classList.remove('hidden');
                            }
                        }
                        hInput.addEventListener('input', calcBMI);
                        wInput.addEventListener('input', calcBMI);
                    </script>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Blood Type</label>
                        <select name="blood_type" required class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="" disabled selected>Select Blood Type</option>
                            <option value="Unknown">I don't know</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Allergies (Optional)</label>
                        <input type="text" name="allergies" placeholder="e.g. Peanuts, Penicillin, Dust"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 bg-gray-50 p-4 rounded-lg">
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" name="is_pregnant" value="1" class="w-5 h-5 text-pink-600 border-gray-300 rounded focus:ring-pink-500">
                            <span class="text-gray-700 font-medium">Are you pregnant?</span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" name="is_sick" value="1" class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500">
                            <span class="text-gray-700 font-medium">Do you currently have an illness?</span>
                        </label>
                    </div>

                    <div class="mb-8 flex items-center">
                        <input type="checkbox" id="critical" name="critical_allergies" value="1" 
                               class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="critical" class="ml-2 text-sm text-gray-700">
                            Are any of your allergies <span class="font-bold text-red-600">life-threatening?</span>
                        </label>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">PhilHealth Number (Optional)</label>
                        <input type="text" name="philhealth_number" placeholder="XX-XXXXXXXXX-X"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg mb-6 border border-gray-200">
                        <h4 class="text-sm font-bold text-gray-700 mb-4 uppercase">In Case of Emergency</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Contact Name</label>
                                <input type="text" name="emergency_contact_name" required placeholder="Full Name"
                                       class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                                <input type="text" name="emergency_contact_phone" required placeholder="09XXXXXXXXX"
                                       class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg shadow transition duration-200">
                        Save & Sync to Residents
                    </button>
                </form>
            </div>
        </div>

    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\brgyhealth-main\resources\views/request_changes.blade.php ENDPATH**/ ?>