@extends('admin.layouts.app')

@section('title', 'Vitrin (Pazarlama) Yönetimi')
@section('page-title', 'Vitrin (Pazarlama) Yönetimi')

@section('content')
<div x-data="vitrinYonetimApp()" class="space-y-8">
    <!-- Header Card -->
    <div class="bg-gradient-to-r from-green-600 via-blue-600 to-purple-800 rounded-2xl p-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">🌟 Vitrin (Pazarlama) Sayfası Yönetimi</h1>
                <p class="text-blue-100 text-lg">Ana sayfa (/) pazarlama içeriklerini yönetin ve ziyaretçileri e-ticaret sitesine yönlendirin</p>
            </div>
            
            <!-- Vitrin Status Toggle -->
            <div class="text-center">
                <div class="mb-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" 
                               x-model="vitrinAktif" 
                               @change="toggleVitrin()"
                               class="sr-only">
                        <div class="relative">
                            <div class="w-20 h-10 bg-gray-300 rounded-full shadow-inner transition-colors duration-300"
                                 :class="vitrinAktif ? 'bg-green-500' : 'bg-gray-400'"></div>
                            <div class="absolute w-8 h-8 bg-white rounded-full shadow top-1 left-1 transition-transform duration-300"
                                 :class="vitrinAktif ? 'transform translate-x-10' : ''"></div>
                        </div>
                    </label>
                </div>
                <div class="text-sm font-semibold">
                    <span x-text="vitrinAktif ? '🟢 Vitrin Aktif' : '🔴 Vitrin Pasif'"></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Bu Ay Ziyaret</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['ziyaret_sayisi'] ?? 1247 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Mağazaya Yönlendirme</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['magaza_yonlendirme'] ?? 892 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Dönüşüm Oranı</p>
                    <p class="text-2xl font-bold text-gray-900">%{{ $stats['donusum_orani'] ?? 71.6 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 bg-orange-100 rounded-full">
                    <svg class="w-8 h-8 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Popülerlik Skoru</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['populerlik_skoru'] ?? 94 }}/100</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Vitrin Content Management -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Hero Section Settings -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    🎯 Hero Section (Ana Banner)
                </h3>

                <form action="{{ route('admin.vitrin.guncelle') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ana Başlık</label>
                        <input type="text" name="hero_baslik" 
                               value="{{ $ayarlar['hero_baslik'] ?? 'Modern B2B E-Ticaret Çözümü' }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alt Başlık</label>
                        <textarea name="hero_alt_baslik" rows="3" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Hero section açıklama metni...">{{ $ayarlar['hero_alt_baslik'] ?? 'İşinizi büyütmek için ihtiyacınız olan tüm araçlar tek platformda' }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">CTA Buton Metni</label>
                            <input type="text" name="hero_cta_text" 
                                   value="{{ $ayarlar['hero_cta_text'] ?? 'Mağazayı Keşfet' }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">CTA Link</label>
                            <select name="hero_cta_link" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="/magaza" {{ ($ayarlar['hero_cta_link'] ?? '/magaza') === '/magaza' ? 'selected' : '' }}>E-Ticaret Mağazası</option>
                                <option value="/iletisim" {{ ($ayarlar['hero_cta_link'] ?? '') === '/iletisim' ? 'selected' : '' }}>İletişim Sayfası</option>
                                <option value="/hakkimizda" {{ ($ayarlar['hero_cta_link'] ?? '') === '/hakkimizda' ? 'selected' : '' }}>Hakkımızda</option>
                            </select>
                        </div>
                    </div>

                    <!-- Özellikler Section -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-4">🚀 Ana Özellikler (3 Adet)</label>
                        
                        @for($i = 1; $i <= 3; $i++)
                        <div class="border border-gray-200 rounded-lg p-4 mb-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Özellik {{ $i }} - Başlık</label>
                                    <input type="text" name="ozellik_{{ $i }}_baslik" 
                                           value="{{ $ayarlar['ozellik_'.$i.'_baslik'] ?? 'Özellik '.$i }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Özellik {{ $i }} - Icon</label>
                                    <input type="text" name="ozellik_{{ $i }}_icon" 
                                           value="{{ $ayarlar['ozellik_'.$i.'_icon'] ?? '🚀' }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"
                                           placeholder="🚀">
                                </div>
                            </div>
                            <div class="mt-2">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Özellik {{ $i }} - Açıklama</label>
                                <textarea name="ozellik_{{ $i }}_aciklama" rows="2" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"
                                          placeholder="Bu özellik hakkında kısa açıklama...">{{ $ayarlar['ozellik_'.$i.'_aciklama'] ?? 'Bu özellik hakkında açıklama' }}</textarea>
                            </div>
                        </div>
                        @endfor
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                                class="bg-gradient-to-r from-green-500 to-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:shadow-lg transition-all transform hover:scale-105">
                            💾 Vitrin Ayarlarını Kaydet
                        </button>
                    </div>
                </form>
            </div>

            <!-- Platform Integration Section -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    🔗 Platform Entegrasyonları
                </h3>

                <div class="grid grid-cols-3 gap-4">
                    @php
                        $platformlar = [
                            'trendyol' => ['name' => 'Trendyol', 'color' => 'orange'],
                            'hepsiburada' => ['name' => 'Hepsiburada', 'color' => 'orange'],
                            'n11' => ['name' => 'N11', 'color' => 'purple'],
                            'amazon' => ['name' => 'Amazon', 'color' => 'yellow'],
                            'pazarama' => ['name' => 'Pazarama', 'color' => 'blue'],
                            'gittigidiyor' => ['name' => 'GittiGidiyor', 'color' => 'yellow']
                        ];
                    @endphp

                    @foreach($platformlar as $key => $platform)
                    <div class="text-center p-4 border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                        <div class="text-2xl mb-2">🏪</div>
                        <div class="text-sm font-medium text-gray-900">{{ $platform['name'] }}</div>
                        <div class="text-xs text-green-600 mt-1">✅ Aktif</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Side Panel -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">⚡ Hızlı İşlemler</h3>
                
                <div class="space-y-3">
                    <a href="{{ route('vitrin.index') }}" target="_blank"
                       class="w-full bg-green-50 text-green-700 px-4 py-3 rounded-lg text-sm font-medium hover:bg-green-100 transition-colors flex items-center">
                        🌐 Vitrin Sayfasını Görüntüle
                        <svg class="w-4 h-4 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                    </a>
                    
                    <a href="{{ route('vitrin.magaza') }}" target="_blank"
                       class="w-full bg-blue-50 text-blue-700 px-4 py-3 rounded-lg text-sm font-medium hover:bg-blue-100 transition-colors flex items-center">
                        🛍️ E-Ticaret Mağazasını Görüntüle
                        <svg class="w-4 h-4 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                    </a>
                    
                    <a href="{{ route('admin.site-ayar.index') }}"
                       class="w-full bg-purple-50 text-purple-700 px-4 py-3 rounded-lg text-sm font-medium hover:bg-purple-100 transition-colors flex items-center">
                        ⚙️ E-Ticaret Site Yönetimi
                        <svg class="w-4 h-4 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Current Status -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">📊 Mevcut Durum</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">Vitrin Durumu</span>
                        <span class="text-sm font-bold text-green-600">🟢 Aktif</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">Hero Section</span>
                        <span class="text-sm font-bold text-blue-600">✅ Ayarlandı</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">Platform Entegrasyonu</span>
                        <span class="text-sm font-bold text-purple-600">6 Platform</span>
                    </div>
                </div>
            </div>

            <!-- Tips -->
            <div class="bg-gradient-to-br from-green-50 to-blue-50 rounded-2xl shadow-lg border border-green-200 p-6">
                <h3 class="text-lg font-bold text-green-800 mb-4">💡 Vitrin Optimizasyon İpuçları</h3>
                
                <div class="space-y-3 text-sm text-green-700">
                    <p>• Hero section'da net ve çekici başlık kullanın</p>
                    <p>• CTA butonunu "Mağazayı Keşfet" şeklinde yönlendirin</p>
                    <p>• Platform logolarını öne çıkarın</p>
                    <p>• Özellikler bölümünde 3 ana değer önerisi yazın</p>
                    <p>• Dönüşüm oranlarını takip edin</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function vitrinYonetimApp() {
    return {
        vitrinAktif: true,
        
        toggleVitrin() {
            // Vitrin aktif/pasif toggle logic
            console.log('Vitrin durumu:', this.vitrinAktif ? 'Aktif' : 'Pasif');
        }
    }
}
</script>
@endsection