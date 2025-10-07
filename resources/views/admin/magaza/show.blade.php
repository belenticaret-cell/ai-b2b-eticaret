@extends('admin.layouts.app')

@section('title', 'Mağaza Detayları')
@section('page-title', 'Mağaza Detayları')

@section('content')
<div class="space-y-6">
    <!-- Mağaza Bilgileri -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold">{{ $magaza->ad }}</h2>
                    <p class="text-blue-100">{{ $magaza->platform }} Entegrasyonu</p>
                </div>
                <div class="flex space-x-2">
                    <button onclick="testConnection({{ $magaza->id }})" 
                            class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg transition">
                        🔍 Test Et
                    </button>
                    <label class="flex items-center bg-white/10 rounded-lg px-3 py-2 text-white/90">
                        <input id="queueSync" type="checkbox" class="mr-2">
                        Kuyruğa al
                    </label>
                    <button onclick="syncMagaza({{ $magaza->id }})" 
                            class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg transition">
                        🔄 Senkronize
                    </button>
                    <form action="{{ route('admin.magaza.katalog.cek', $magaza) }}" method="POST" onsubmit="return confirm('Uzak katalog çekilsin mi?')">
                        @csrf
                        <button type="submit" class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg transition">🗂️ Katalog Çek</button>
                    </form>
                    <a href="{{ route('admin.magaza.edit', $magaza) }}" 
                       class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg transition">
                        ✏️ Düzenle
                    </a>
                    <a href="{{ route('admin.magaza.index') }}" 
                       class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg transition">
                        ← Geri
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Performans Metrikleri -->
                <div class="lg:col-span-1 space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900">📊 Performans</h3>
                    
                    <div class="bg-green-50 border border-green-200 p-4 rounded-lg">
                        <label class="text-sm font-medium text-green-600">Toplam Ürün</label>
                        <p class="text-2xl font-bold text-green-700">{{ $performans['toplam_urun'] }}</p>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg">
                        <label class="text-sm font-medium text-blue-600">Aktif Ürün</label>
                        <p class="text-2xl font-bold text-blue-700">{{ $performans['aktif_urun'] }}</p>
                    </div>

                    <div class="bg-orange-50 border border-orange-200 p-4 rounded-lg">
                        <label class="text-sm font-medium text-orange-600">Son Senkron</label>
                        <p class="text-lg font-semibold text-orange-700">{{ $performans['son_senkron'] }}</p>
                    </div>

                    <div class="bg-purple-50 border border-purple-200 p-4 rounded-lg">
                        <label class="text-sm font-medium text-purple-600">API Durumu</label>
                        <p class="text-lg font-semibold text-purple-700">
                            @if($performans['api_durumu'] === 'online')
                                <span class="text-green-600">🟢 Online</span>
                            @elseif($performans['api_durumu'] === 'limited')
                                <span class="text-yellow-600">🟡 Sınırlı</span>
                            @else
                                <span class="text-red-600">🔴 Offline</span>
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Mağaza Detayları -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Platform Bilgileri -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">🔧 Platform Ayarları</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <label class="text-sm font-medium text-gray-600">Platform</label>
                                <p class="text-lg font-semibold">{{ $magaza->platform }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <label class="text-sm font-medium text-gray-600">Mağaza ID</label>
                                <p class="text-lg font-semibold">{{ $magaza->magaza_id ?? 'Belirtilmemiş' }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <label class="text-sm font-medium text-gray-600">API URL</label>
                                <p class="text-sm text-gray-700 break-all">{{ $magaza->api_url ?? 'Belirtilmemiş' }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <label class="text-sm font-medium text-gray-600">Komisyon Oranı</label>
                                <p class="text-lg font-semibold">{{ $magaza->komisyon_orani ?? 0 }}%</p>
                            </div>
                        </div>
                    </div>

                    <!-- Durum & Ayarlar -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">⚙️ Durum & Ayarlar</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <label class="text-sm font-medium text-gray-600">Durum</label>
                                <p class="text-lg font-semibold">
                                    @if($magaza->aktif ?? true)
                                        <span class="text-green-600">✅ Aktif</span>
                                    @else
                                        <span class="text-red-600">❌ Pasif</span>
                                    @endif
                                </p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <label class="text-sm font-medium text-gray-600">Otomatik Senkron</label>
                                <p class="text-lg font-semibold">
                                    @if($magaza->auto_senkron ?? false)
                                        <span class="text-green-600">✅ Açık</span>
                                    @else
                                        <span class="text-gray-600">❌ Kapalı</span>
                                    @endif
                                </p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <label class="text-sm font-medium text-gray-600">Test Modu</label>
                                <p class="text-lg font-semibold">
                                    @if($magaza->test_mode ?? false)
                                        <span class="text-orange-600">🧪 Test</span>
                                    @else
                                        <span class="text-blue-600">🚀 Production</span>
                                    @endif
                                </p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <label class="text-sm font-medium text-gray-600">Son Bağlantı Testi</label>
                                <p class="text-sm text-gray-700">
                                    {{ $magaza->son_baglanti_testi ? $magaza->son_baglanti_testi->diffForHumans() : 'Hiç test edilmemiş' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Açıklama -->
                    @if($magaza->aciklama)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">📝 Açıklama</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-700">{{ $magaza->aciklama }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Ürünler -->
    @if($urunler->count() > 0)
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-green-500 to-green-600 text-white">
            <h2 class="text-xl font-semibold">📦 Mağazadaki Ürünler ({{ $urunler->total() }})</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ürün</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fiyat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($urunler as $urun)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <img src="{{ $urun->gorsel ?? 'https://placehold.co/40x40?text=Ürün' }}" 
                                     class="w-10 h-10 rounded-lg object-cover mr-3" alt="Ürün">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ Str::limit($urun->ad, 30) }}</div>
                                    <div class="text-sm text-gray-500">{{ $urun->barkod ?? 'Barkod yok' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">{{ number_format($urun->fiyat, 0) }}₺</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $urun->stok ?? 0 }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($urun->aktif ?? true)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Pasif
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50">
            {{ $urunler->links() }}
        </div>
    </div>
    @endif

    <!-- Platform Ürünleri (Uzak Katalog) -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white">
            <h2 class="text-xl font-semibold">🗃️ Platform Ürünleri ({{ $platformUrunleri->total() }})</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Başlık</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fiyat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Eşleşme</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($platformUrunleri as $p)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $p->platform_sku ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ Str::limit($p->baslik, 50) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $p->fiyat ? number_format($p->fiyat, 2) . '₺' : '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $p->stok ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($p->urun)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Eşleşti ({{ $p->urun->ad }})
                                </span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Eşleşmemiş</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm space-x-2">
                            @if($p->urun)
                            <form action="{{ route('admin.magaza.urun.gonder', $magaza) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="platform_urun_id" value="{{ $p->id }}" />
                                <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded">Gönder</button>
                            </form>
                            @endif
                            <details>
                                <summary class="cursor-pointer text-indigo-600">Eşleştir</summary>
                                <div class="mt-2">
                                    <form action="{{ route('admin.magaza.urun.esle', $magaza) }}" method="POST" class="flex items-center space-x-2">
                                        @csrf
                                        <input type="hidden" name="platform_urun_id" value="{{ $p->id }}" />
                                        <select name="urun_id" class="border rounded px-2 py-1">
                                            @foreach(\App\Models\Urun::orderBy('ad')->limit(200)->get() as $u)
                                                <option value="{{ $u->id }}">{{ $u->ad }} ({{ $u->sku }})</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="px-3 py-1 bg-indigo-600 text-white rounded">Kaydet</button>
                                    </form>
                                </div>
                            </details>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Kayıt bulunamadı. Önce "Katalog Çek" butonunu kullanabilirsiniz.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50">
            {{ $platformUrunleri->links() }}
        </div>
    </div>
    <!-- Senkronizasyon Logları -->
    @if($senkronLoglar->count() > 0)
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-purple-500 to-purple-600 text-white">
            <h2 class="text-xl font-semibold">📋 Son Senkronizasyon Logları</h2>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @foreach($senkronLoglar as $log)
                <div class="flex items-center justify-between p-4 rounded-lg border {{ $log['durum'] === 'success' ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
                    <div>
                        <h4 class="font-medium {{ $log['durum'] === 'success' ? 'text-green-900' : 'text-red-900' }}">
                            {{ $log['islem'] }}
                        </h4>
                        <p class="text-sm {{ $log['durum'] === 'success' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $log['detay'] }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm {{ $log['durum'] === 'success' ? 'text-green-500' : 'text-red-500' }}">
                            {{ $log['tarih']->diffForHumans() }}
                        </p>
                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded {{ $log['durum'] === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $log['sonuc'] }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

<script>
function testConnection(magazaId) {
    fetch(`/admin/magaza/${magazaId}/test-connection`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ Bağlantı başarılı: ' + data.message);
        } else {
            alert('❌ Bağlantı hatası: ' + data.message);
        }
    })
    .catch(error => {
        alert('❌ Test sırasında hata: ' + error.message);
    });
}

function syncMagaza(magazaId) {
    if (!confirm('Senkronizasyon işlemi başlatılsın mı?')) return;
    const queue = document.getElementById('queueSync')?.checked ? 'true' : 'false';
    fetch(`/admin/magaza/${magazaId}/senkronize`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ queue })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ Senkronizasyon başarılı: ' + data.message);
            location.reload();
        } else {
            alert('❌ Senkronizasyon hatası: ' + data.message);
        }
    })
    .catch(error => {
        alert('❌ Senkronizasyon sırasında hata: ' + error.message);
    });
}
</script>
@endsection