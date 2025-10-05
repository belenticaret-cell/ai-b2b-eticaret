@extends('admin.layouts.app')

@section('title', 'Ürün Düzenle')
@section('page-title', 'Ürün Düzenle')

@section('content')
<div class="space-y-6" x-data="{ tab: 'temel', preview: '' }">
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded" role="alert">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Tabs -->
    <div class="bg-white rounded-lg shadow p-4 flex gap-2">
        <button @click="tab='temel'" :class="tab==='temel' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'" class="px-4 py-2 rounded">Temel Bilgiler</button>
        <button @click="tab='detay'" :class="tab==='detay' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'" class="px-4 py-2 rounded">Detay & SEO</button>
        <button @click="tab='magazalar'" :class="tab==='magazalar' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'" class="px-4 py-2 rounded">Mağazalar</button>
        <button @click="tab='bayi'" :class="tab==='bayi' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'" class="px-4 py-2 rounded">Bayi Fiyat Yönetimi</button>
    </div>

    <form method="POST" action="{{ route('admin.urun.update', $urun) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Temel Bilgiler -->
        <div x-show="tab==='temel'" class="bg-white rounded-lg shadow p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ad</label>
                        <input type="text" name="ad" value="{{ old('ad', $urun->ad) }}" class="w-full px-3 py-2 border rounded" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fiyat (₺)</label>
                        <input type="number" step="0.01" name="fiyat" value="{{ old('fiyat', $urun->fiyat) }}" class="w-full px-3 py-2 border rounded" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                        <input type="number" name="stok" value="{{ old('stok', $urun->stok) }}" class="w-full px-3 py-2 border rounded">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Barkod</label>
                        <input type="text" name="barkod" value="{{ old('barkod', $urun->barkod) }}" class="w-full px-3 py-2 border rounded">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select name="kategori_id" class="w-full px-3 py-2 border rounded">
                            <option value="">Seçiniz</option>
                            @foreach($kategoriler as $k)
                                <option value="{{ $k->id }}" @selected(old('kategori_id', $urun->kategori_id)==$k->id)>{{ $k->ad }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Marka</label>
                        <select name="marka_id" class="w-full px-3 py-2 border rounded">
                            <option value="">Seçiniz</option>
                            @foreach($markalar as $m)
                                <option value="{{ $m->id }}" @selected(old('marka_id', $urun->marka_id)==$m->id)>{{ $m->ad }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="aktif" id="aktif" value="1" @checked(old('aktif', $urun->aktif))>
                        <label for="aktif" class="text-sm text-gray-700">Aktif</label>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Görsel URL</label>
                    <input type="url" name="gorsel" value="{{ old('gorsel', $urun->gorsel) }}" class="w-full px-3 py-2 border rounded mb-3" placeholder="https://...">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Görsel Dosya</label>
                    <input type="file" name="gorsel_dosya" accept="image/*" class="w-full px-3 py-2 border rounded" x-on:change="preview = URL.createObjectURL($event.target.files[0])">
                    <div class="mt-3 space-y-2">
                        @if($urun->gorsel)
                            <div>
                                <div class="text-xs text-gray-500 mb-1">Mevcut Görsel</div>
                                <img src="{{ Str::startsWith($urun->gorsel, ['http://','https://']) ? $urun->gorsel : asset('storage/'.$urun->gorsel) }}" alt="{{ $urun->ad }}" class="rounded border max-h-32">
                            </div>
                        @endif
                        <template x-if="preview">
                            <div>
                                <div class="text-xs text-gray-500 mb-1">Yeni Önizleme</div>
                                <img :src="preview" alt="Önizleme" class="rounded border max-h-32">
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Açıklama</label>
                <textarea name="aciklama" rows="4" class="w-full px-3 py-2 border rounded">{{ old('aciklama', $urun->aciklama) }}</textarea>
            </div>
        </div>

        <!-- Detay & SEO -->
        <div x-show="tab==='detay'" class="bg-white rounded-lg shadow p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $urun->meta_title) }}" class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                    <input type="text" name="meta_description" value="{{ old('meta_description', $urun->meta_description) }}" class="w-full px-3 py-2 border rounded">
                </div>
            </div>
            <div class="text-sm text-gray-500">SEO alanları arama sonuçlarındaki görünürlüğü iyileştirir.</div>
        </div>

        <!-- Mağazalar -->
        <div x-show="tab==='magazalar'" class="bg-white rounded-lg shadow p-6 space-y-3">
            @php($secili = old('magazalar', isset($urun) ? \Illuminate\Support\Facades\DB::table('magaza_urun')->where('urun_id',$urun->id)->pluck('magaza_id')->toArray() : []))
            @if($magazalar->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach($magazalar as $magaza)
                        <label class="flex items-center gap-2 p-2 border rounded">
                            <input type="checkbox" name="magazalar[]" value="{{ $magaza->id }}" @checked(in_array($magaza->id, $secili))>
                            <span>{{ $magaza->ad }} @if($magaza->platform)<span class="text-gray-500 text-xs">({{ $magaza->platform }})</span>@endif</span>
                        </label>
                    @endforeach
                </div>
            @else
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded">Kayıtlı mağaza yok. Önce mağaza ekleyin.</div>
            @endif
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.urun.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded">Geri</a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Güncelle</button>
        </div>
    </form>

    <!-- Bayi Fiyat Yönetimi (ayrı form) -->
    <div x-show="tab==='bayi'" class="bg-white rounded-lg shadow p-6 space-y-4">
        <h3 class="text-lg font-semibold">Bayi Fiyatları</h3>
        <form method="POST" action="{{ route('admin.urun.bayi-fiyat.kaydet', $urun) }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            @csrf
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Bayi</label>
                <select name="bayi_id" class="w-full px-3 py-2 border rounded" required>
                    @foreach(\App\Models\Bayi::orderBy('ad')->get() as $bayi)
                        <option value="{{ $bayi->id }}">{{ $bayi->ad }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fiyat (₺)</label>
                <input type="number" step="0.01" name="fiyat" class="w-full px-3 py-2 border rounded" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">İskonto (%)</label>
                <input type="number" step="0.01" name="iskonto_orani" class="w-full px-3 py-2 border rounded">
            </div>
            <div class="md:col-span-2 grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Başlangıç</label>
                    <input type="date" name="baslangic_tarihi" class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bitiş</label>
                    <input type="date" name="bitis_tarihi" class="w-full px-3 py-2 border rounded">
                </div>
            </div>
            <div class="flex items-end">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Ekle/Güncelle</button>
            </div>
        </form>

        <div class="border-t pt-4">
            <h4 class="font-medium mb-2">Tanımlı Bayi Fiyatları</h4>
            @php($kayitlar = $urun->bayiFiyatlari()->with('bayi')->orderByDesc('id')->get())
            @if($kayitlar->count())
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left">Bayi</th>
                                <th class="px-4 py-2 text-left">Fiyat</th>
                                <th class="px-4 py-2 text-left">İskonto</th>
                                <th class="px-4 py-2 text-left">Başlangıç</th>
                                <th class="px-4 py-2 text-left">Bitiş</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kayitlar as $k)
                                <tr class="border-t">
                                    <td class="px-4 py-2">{{ $k->bayi->ad ?? ('#'.$k->bayi_id) }}</td>
                                    <td class="px-4 py-2">{{ number_format($k->fiyat, 2) }} ₺</td>
                                    <td class="px-4 py-2">{{ $k->iskonto_orani ? $k->iskonto_orani.'%' : '—' }}</td>
                                    <td class="px-4 py-2">{{ $k->baslangic_tarihi ? \Illuminate\Support\Carbon::parse($k->baslangic_tarihi)->format('Y-m-d') : '—' }}</td>
                                    <td class="px-4 py-2">{{ $k->bitis_tarihi ? \Illuminate\Support\Carbon::parse($k->bitis_tarihi)->format('Y-m-d') : '—' }}</td>
                                    <td class="px-4 py-2 text-right">
                                        <form method="POST" action="{{ route('admin.urun.bayi-fiyat.sil', [$urun, $k->id]) }}" onsubmit="return confirm('Kaydı silmek istiyor musunuz?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm" type="submit">Sil</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-gray-500">Tanımlı bayi fiyatı bulunmuyor.</div>
            @endif
        </div>
    </div>
</div>
@endsection
