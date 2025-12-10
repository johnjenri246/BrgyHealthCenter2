<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Admin Panel'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex flex-col min-h-screen">
        <!-- Mobile top bar -->
        <header class="md:hidden sticky top-0 z-30 bg-gray-900 text-white flex items-center justify-between px-4 py-3 shadow">
            <div class="flex items-center gap-2">
                <button id="admin-mobile-toggle" class="p-2 rounded hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-red-400" aria-label="Open navigation">
                    <span class="block w-6 h-0.5 bg-white mb-1"></span>
                    <span class="block w-6 h-0.5 bg-white mb-1"></span>
                    <span class="block w-6 h-0.5 bg-white"></span>
                </button>
                <div>
                    <p class="text-sm font-semibold">Admin Panel</p>
                    <p class="text-xs text-gray-300">Barangay System</p>
                </div>
            </div>
            <div class="text-xs font-semibold"><?php echo e(Auth::user()->name); ?></div>
        </header>

        <div class="flex flex-1 overflow-hidden">
            <!-- Backdrop for mobile -->
            <div id="admin-sidebar-backdrop" class="fixed inset-0 bg-black/40 z-20 hidden md:hidden"></div>

            <aside id="admin-sidebar" class="fixed inset-y-0 left-0 w-64 bg-gray-900 text-white flex flex-col z-30 transform -translate-x-full md:translate-x-0 transition-transform duration-200 md:static md:h-auto md:flex-shrink-0">
                <div class="p-6 text-center border-b border-gray-800">
                    <h1 class="text-2xl font-bold text-red-500">ADMIN PANEL</h1>
                    <p class="text-xs text-gray-500 mt-1">Barangay System</p>
                </div>

                <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="block py-2.5 px-4 rounded hover:bg-gray-800 <?php echo e(request()->routeIs('admin.dashboard') ? 'bg-gray-700' : ''); ?>">
                        📊 Dashboard
                    </a>
                    <a href="<?php echo e(route('admin.residents')); ?>" class="block py-2.5 px-4 rounded hover:bg-gray-800 <?php echo e(request()->routeIs('admin.residents') ? 'bg-gray-700' : ''); ?>">
                        👥 Residents List
                    </a>
                    
                    
                    <a href="<?php echo e(route('admin.residents.archived')); ?>" class="block py-2.5 px-4 rounded hover:bg-gray-800 <?php echo e(request()->routeIs('admin.residents.archived') ? 'bg-gray-700' : ''); ?>">
                        📂 Archived Residents
                    </a>

                    <a href="<?php echo e(route('admin.appointments')); ?>" class="block py-2.5 px-4 rounded hover:bg-gray-800 <?php echo e(request()->routeIs('admin.appointments') ? 'bg-gray-700' : ''); ?>">
                        📅 Appointment Requests
                    </a>
                    <a href="<?php echo e(route('admin.requests')); ?>" class="block py-2.5 px-4 rounded hover:bg-gray-800 <?php echo e(request()->routeIs('admin.requests') ? 'bg-gray-700' : ''); ?>">
                        📋 Change Requests
                    </a>
                    <a href="<?php echo e(route('admin.inventory.index')); ?>" class="block py-2.5 px-4 rounded hover:bg-gray-800 <?php echo e(request()->routeIs('admin.inventory.*') ? 'bg-gray-700' : ''); ?>">
                        💊 Medicine Inventory
                    </a>
                    <a href="<?php echo e(route('admin.weather')); ?>" class="block py-2.5 px-4 rounded hover:bg-gray-800 <?php echo e(request()->routeIs('admin.weather') ? 'bg-gray-700' : ''); ?>">
                        🌤️ Weather
                    </a>
                </nav>

                <div class="p-4 border-t border-gray-800">
                    <div class="mb-4 text-sm text-gray-400">Admin: <?php echo e(Auth::user()->name); ?></div>
                    <form action="<?php echo e(route('logout')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button class="w-full bg-red-600 hover:bg-red-700 font-bold py-2 px-4 rounded">Logout</button>
                    </form>
                </div>
            </aside>

            <main class="flex-1 md:ml-64 p-4 md:p-8 overflow-y-auto">
                <div class="max-w-7xl mx-auto">
                    <?php if(session('success')): ?>
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6"><?php echo e(session('success')); ?></div>
                    <?php endif; ?>
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </main>
        </div>
    </div>
    <script>
        (() => {
            const sidebar = document.getElementById('admin-sidebar');
            const toggle = document.getElementById('admin-mobile-toggle');
            const backdrop = document.getElementById('admin-sidebar-backdrop');

            const closeSidebar = () => {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            };

            const openSidebar = () => {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            };

            toggle?.addEventListener('click', () => {
                if (sidebar.classList.contains('-translate-x-full')) {
                    openSidebar();
                } else {
                    closeSidebar();
                }
            });

            backdrop?.addEventListener('click', closeSidebar);

            window.addEventListener('resize', () => {
                if (window.innerWidth >= 768) {
                    sidebar.classList.remove('-translate-x-full');
                    backdrop.classList.add('hidden');
                }
            });
        })();
    </script>
    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html><?php /**PATH C:\xampp\htdocs\brgyhealth-main\resources\views/layouts/admin.blade.php ENDPATH**/ ?>