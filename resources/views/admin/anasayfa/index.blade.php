@extends('admin.layouts.app')

@section('title', 'Anasayfa Yönetimi')
@section('page-title', 'Anasayfa Yönetimi')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Hero ve Banner</h2>
        <form method="POST" action="{{ route('admin.anasayfa.guncelle') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hero Başlık</label>
                    <input type="text" name="anasayfa_hero_baslik" value="{{ $ayarlar['anasayfa_hero_baslik'] ?? '' }}" class="w-full px-3 py-2 border rounded" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hero Alt Başlık</label>
                    <input type="text" name="anasayfa_hero_altbaslik" value="{{ $ayarlar['anasayfa_hero_altbaslik'] ?? '' }}" class="w-full px-3 py-2 border rounded" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hero Buton Yazısı</label>
                    <input type="text" name="anasayfa_hero_buton_yazi" value="{{ $ayarlar['anasayfa_hero_buton_yazi'] ?? '' }}" class="w-full px-3 py-2 border rounded" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hero Buton Linki</label>
                    <input type="text" name="anasayfa_hero_buton_link" value="{{ $ayarlar['anasayfa_hero_buton_link'] ?? '' }}" class="w-full px-3 py-2 border rounded" />
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Üst Duyuru (opsiyonel)</label>
                    <input type="text" name="anasayfa_duyuru" value="{{ $ayarlar['anasayfa_duyuru'] ?? '' }}" class="w-full px-3 py-2 border rounded" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Üst Banner Resmi (URL)</label>
                    <input type="text" name="anasayfa_ust_banner_resmi" value="{{ $ayarlar['anasayfa_ust_banner_resmi'] ?? '' }}" class="w-full px-3 py-2 border rounded" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alt Banner Resmi (URL)</label>
                    <input type="text" name="anasayfa_alt_banner_resmi" value="{{ $ayarlar['anasayfa_alt_banner_resmi'] ?? '' }}" class="w-full px-3 py-2 border rounded" />
                </div>
            </div>

            <h2 class="text-lg font-medium text-gray-900 mt-8 mb-4">Öne Çıkanlar</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Öne Çıkan Kategoriler</label>
                    @php($seciliKats = array_filter(explode(',', $ayarlar['anasayfa_onecikan_kategoriler'] ?? '')))
                    <select name="anasayfa_onecikan_kategoriler[]" multiple class="w-full px-3 py-2 border rounded min-h-[160px]">
                        @foreach($kategoriler as $k)
                            <option value="{{ $k->id }}" @if(in_array((string)$k->id, $seciliKats)) selected @endif>{{ $k->ad }}</option>
                        @endforeach
                    </select>
                    <small class="text-gray-500">Ctrl/Command ile birden fazla seçebilirsiniz.</small>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Öne Çıkan Ürünler</label>
                    @php($seciliUrun = array_filter(explode(',', $ayarlar['anasayfa_onecikan_urunler'] ?? '')))
                    <select name="anasayfa_onecikan_urunler[]" multiple class="w-full px-3 py-2 border rounded min-h-[160px]">
                        @foreach($urunler as $u)
                            <option value="{{ $u->id }}" @if(in_array((string)$u->id, $seciliUrun)) selected @endif>{{ $u->ad }}</option>
                        @endforeach
                    </select>
                    <small class="text-gray-500">Ctrl/Command ile birden fazla seçebilirsiniz.</small>
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition">Kaydet</button>
            </div>
        </form>
    </div>
</div>
@endsection
