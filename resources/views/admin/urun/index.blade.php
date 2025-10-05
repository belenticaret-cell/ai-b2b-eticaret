@extends('admin.layouts.app')

@section('title', 'Ürünler')
@section('page-title', 'Ürün Yönetimi')

@section('content')
<div class="space-y-6">
    <!-- Üst İstatistikler -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-500">Toplam Ürün</div>
            <div class="text-2xl font-bold">{{ $istatistikler['toplam_urun'] ?? '-' }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-500">Düşük Stok</div>
            <div class="text-2xl font-bold">{{ $istatistikler['dusuk_stok'] ?? '-' }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-500">Tükenen</div>
            <div class="text-2xl font-bold">{{ $istatistikler['tukenen_urun'] ?? '-' }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-500">Stok Toplam Değer</div>
            <div class="text-2xl font-bold">{{ number_format($istatistikler['toplam_deger'] ?? 0, 2) }} ₺</div>
        </div>
    </div>

    <!-- Filtreler / Aksiyonlar -->
    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-3">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Arama</label>
                <input type="text" name="search" value="{{ request('search') }}" class="w-full px-3 py-2 border rounded" placeholder="Ad, barkod, açıklama">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Kategori</label>
                <select name="kategori_id" class="w-full px-3 py-2 border rounded">
                    <option value="">Tümü</option>
                    @foreach($kategoriler as $k)
                        <option value="{{ $k->id }}" @selected(request('kategori_id')==$k->id)>{{ $k->ad }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Marka</label>
                <select name="marka_id" class="w-full px-3 py-2 border rounded">
                    <option value="">Tümü</option>
                    @foreach($markalar as $m)
                        <option value="{{ $m->id }}" @selected(request('marka_id')==$m->id)>{{ $m->ad }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Stok</label>
                <select name="stok_durumu" class="w-full px-3 py-2 border rounded">
                    <option value="">Tümü</option>
                    <option value="dusuk" @selected(request('stok_durumu')=='dusuk')>Düşük (<=5)</option>
                    <option value="tukendi" @selected(request('stok_durumu')=='tukendi')>Tükendi</option>
                    <option value="normal" @selected(request('stok_durumu')=='normal')>Normal</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button class="bg-blue-600 text-white px-4 py-2 rounded">Filtrele</button>
                <a href="{{ route('admin.urun.index') }}" class="bg-gray-200 text-gray-700 px-3 py-2 rounded">Temizle</a>
                <a href="{{ route('admin.urun.create') }}" class="bg-green-600 text-white px-4 py-2 rounded">Yeni Ürün</a>
            </div>
        </form>
    </div>

    <!-- Liste -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">#</th>
                    <th class="px-4 py-2 text-left">Görsel</th>
                    <th class="px-4 py-2 text-left">Ad</th>
                    <th class="px-4 py-2 text-left">SKU</th>
                    <th class="px-4 py-2 text-left">Fiyat</th>
                    <th class="px-4 py-2 text-left">Bayi Fiyat</th>
                    <th class="px-4 py-2 text-left">Stok</th>
                    <th class="px-4 py-2 text-left">Durum</th>
                    <th class="px-4 py-2 text-left">Mağazalar</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($urunler as $urun)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $urun->id }}</td>
                        <td class="px-4 py-2">
                            @if($urun->gorsel)
                                <img src="{{ Str::startsWith($urun->gorsel, ['http://','https://']) ? $urun->gorsel : asset('storage/'.$urun->gorsel) }}" alt="{{ $urun->ad }}" class="h-10 w-10 object-cover rounded">
                            @else
                                <div class="h-10 w-10 bg-gray-100 rounded"></div>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            <div class="font-medium">{{ $urun->ad }}</div>
                            <div class="text-gray-500 text-xs">{{ $urun->kategori->ad ?? '-' }} • {{ $urun->marka->ad ?? '-' }}</div>
                        </td>
                        <td class="px-4 py-2">{{ $urun->sku ?? '—' }}</td>
                        <td class="px-4 py-2">{{ number_format($urun->fiyat, 2) }} ₺</td>
                        <td class="px-4 py-2">
                            @php($bf = $urun->bayi_fiyat)
                            <div>{{ $bf ? number_format($bf, 2).' ₺' : '—' }}</div>
                            @if($urun->bayi_fiyatlari_count > 0)
                                <div class="text-xs text-gray-500">({{ $urun->bayi_fiyatlari_count }} özel)</div>
                            @endif
                        </td>
                        <td class="px-4 py-2">{{ $urun->stok ?? 0 }}</td>
                        <td class="px-4 py-2">
                            @if($urun->aktif)
                                <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-700 rounded text-xs">Aktif</span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs">Pasif</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            @php($ms = $urunMagazalari[$urun->id] ?? [])
                            @if(empty($ms))
                                <span class="text-gray-400">—</span>
                            @else
                                <div class="flex flex-wrap gap-1">
                                    @foreach($ms as $m)
                                        <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded">{{ $m['ad'] }}@if(!empty($m['platform'])) <span class="text-gray-500">({{ $m['platform'] }})</span>@endif</span>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.urun.edit', $urun) }}" class="px-3 py-1 bg-white border rounded hover:bg-gray-50 text-sm">Düzenle</a>
                                <form action="{{ route('admin.urun.destroy', $urun) }}" method="POST" onsubmit="return confirm('Bu ürünü silmek istediğinize emin misiniz?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm" type="submit">Sil</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="px-4 py-8 text-center text-gray-500">Kayıt bulunamadı.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $urunler->links() }}
    </div>
</div>
@endsection
