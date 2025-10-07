<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bayi Admin')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r hidden md:block">
            <div class="p-4 border-b">
                <a href="{{ route('bayi.panel') }}" class="text-lg font-bold">🏢 Bayi Admin</a>
                <p class="text-xs text-gray-500 mt-1">Kendi mağazanızı yönetin</p>
            </div>
            <nav class="p-4 space-y-1">
                <a href="{{ route('bayi.panel') }}" class="block px-3 py-2 rounded hover:bg-gray-100">📊 Kontrol Paneli</a>
                <a href="{{ route('bayi.urunler') }}" class="block px-3 py-2 rounded hover:bg-gray-100">📦 Ürünlerim</a>
                <a href="{{ route('bayi.siparisler') }}" class="block px-3 py-2 rounded hover:bg-gray-100">🧾 Siparişlerim</a>
                <a href="{{ route('bayi.cari') }}" class="block px-3 py-2 rounded hover:bg-gray-100">💳 Cari Hesap</a>
                <a href="{{ route('bayi.ayarlar') }}" class="block px-3 py-2 rounded hover:bg-gray-100">⚙️ Mağaza Ayarları</a>
                <a href="{{ route('bayi.profil') }}" class="block px-3 py-2 rounded hover:bg-gray-100">👤 Profil</a>
            </nav>
        </aside>

        <!-- Main -->
        <div class="flex-1 flex flex-col">
            <!-- Topbar -->
            <header class="bg-white border-b p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button class="md:hidden" onclick="document.getElementById('mobileMenu').classList.toggle('hidden')">☰</button>
                    <h1 class="text-xl font-semibold">@yield('page-title', 'Bayi Admin')</h1>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('vitrin.index') }}" target="_blank" class="text-sm text-blue-600 hover:underline">🌐 Vitrine Git</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-sm text-gray-600 hover:text-gray-900">Çıkış</button>
                    </form>
                </div>
            </header>

            <!-- Content -->
            <main class="p-4">
                @if(session('success'))
                    <div class="mb-4 px-4 py-2 rounded bg-green-100 text-green-800">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="mb-4 px-4 py-2 rounded bg-red-100 text-red-800">
                        @foreach($errors->all() as $e)
                            <div>{{ $e }}</div>
                        @endforeach
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile menu -->
    <div id="mobileMenu" class="fixed inset-0 bg-black bg-opacity-40 z-40 hidden md:hidden">
        <div class="absolute left-0 top-0 bottom-0 w-64 bg-white p-4">
            <div class="flex items-center justify-between border-b pb-3">
                <span class="font-semibold">Bayi Admin</span>
                <button onclick="document.getElementById('mobileMenu').classList.add('hidden')">✕</button>
            </div>
            <nav class="py-3 space-y-1">
                <a href="{{ route('bayi.panel') }}" class="block px-3 py-2 rounded hover:bg-gray-100">📊 Kontrol Paneli</a>
                <a href="{{ route('bayi.urunler') }}" class="block px-3 py-2 rounded hover:bg-gray-100">📦 Ürünlerim</a>
                <a href="{{ route('bayi.siparisler') }}" class="block px-3 py-2 rounded hover:bg-gray-100">🧾 Siparişlerim</a>
                <a href="{{ route('bayi.cari') }}" class="block px-3 py-2 rounded hover:bg-gray-100">💳 Cari Hesap</a>
                <a href="{{ route('bayi.ayarlar') }}" class="block px-3 py-2 rounded hover:bg-gray-100">⚙️ Mağaza Ayarları</a>
                <a href="{{ route('bayi.profil') }}" class="block px-3 py-2 rounded hover:bg-gray-100">👤 Profil</a>
            </nav>
        </div>
    </div>
</body>
</html>
