<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $kegiatan->nama_kegiatan }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-slate-950 text-white">

<div class="max-w-6xl mx-auto p-6">

    <!-- BACK -->
    <a href="{{ route('landing') }}" class="text-emerald-400 mb-6 inline-block">
        ← Kembali ke Beranda
    </a>

    <!-- TITLE -->
    <h1 class="text-3xl font-bold mb-2">
        {{ $kegiatan->nama_kegiatan }}
    </h1>

    <p class="text-slate-400 mb-8">
        {{ $kegiatan->deskripsi }}
    </p>

    <!-- GRID FOTO (RAPI) -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">

        @foreach($kegiatan->dokumentasi as $dok)
            <div class="w-full aspect-square overflow-hidden rounded-xl">
                
                <img 
                    src="{{ asset('storage/' . $dok->file_path) }}"
                    class="w-full h-full object-cover"
                >

            </div>
        @endforeach

    </div>

</div>

</body>
</html>