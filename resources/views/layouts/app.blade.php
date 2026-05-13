<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - SITSFOR Kegiatan</title>
    <meta name="description" content="Sistem Informasi Kegiatan Luar Kantor">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-slate-50 font-sans antialiased min-h-screen">
    <div class="flex min-h-screen" id="app-layout">
        <!-- Sidebar -->
        @include('components.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col lg:ml-72">
            <!-- Topbar -->
            @include('components.topbar')

            <!-- Page Content -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 flex items-center gap-3 animate-slide-down" id="flash-success">
                        <i class="fas fa-check-circle text-lg"></i>
                        <span>{{ session('success') }}</span>
                        <button onclick="this.parentElement.remove()" class="ml-auto hover:text-emerald-900"><i class="fas fa-times"></i></button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="mb-6 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-700 flex items-center gap-3 animate-slide-down">
                        <i class="fas fa-exclamation-triangle text-lg"></i>
                        <span>{{ session('warning') }}</span>
                        <button onclick="this.parentElement.remove()" class="ml-auto hover:text-amber-900"><i class="fas fa-times"></i></button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="mb-6 p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-700 flex items-center gap-3 animate-slide-down">
                        <i class="fas fa-info-circle text-lg"></i>
                        <span>{{ session('info') }}</span>
                        <button onclick="this.parentElement.remove()" class="ml-auto hover:text-blue-900"><i class="fas fa-times"></i></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 animate-slide-down">
                        <div class="flex items-center gap-3 mb-2">
                            <i class="fas fa-exclamation-circle text-lg"></i>
                            <span class="font-semibold">Terjadi Kesalahan</span>
                        </div>
                        <ul class="list-disc list-inside text-sm space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        // Auto-hide flash messages
        setTimeout(() => {
            document.querySelectorAll('#flash-success').forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(-10px)';
                setTimeout(() => el.remove(), 300);
            });
        }, 5000);
    </script>
    <!-- Biodata Reminder Modal -->
    @include('components.biodata-modal')
    @stack('scripts')
</body>
</html>
