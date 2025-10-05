@extends('admin.layouts.app')

@section('title', 'Yeni Ürün')
@section('page-title', 'Yeni Ürün Ekle')

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
    </div>

    <form method="POST" action="{{ route('admin.urun.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Temel Bilgiler -->
        <div x-show="tab==='temel'" class="bg-white rounded-lg shadow p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ad</label>
                        <input type="text" name="ad" value="{{ old('ad') }}" class="w-full px-3 py-2 border rounded" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fiyat (₺)</label>
                        <input type="number" step="0.01" name="fiyat" value="{{ old('fiyat') }}" class="w-full px-3 py-2 border rounded" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
                        <input type="number" name="stok" value="{{ old('stok') }}" class="w-full px-3 py-2 border rounded">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Barkod</label>
                        <input type="text" name="barkod" value="{{ old('barkod') }}" class="w-full px-3 py-2 border rounded">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select name="kategori_id" class="w-full px-3 py-2 border rounded">
                            <option value="">Seçiniz</option>
                            @foreach($kategoriler as $k)
                                <option value="{{ $k->id }}" @selected(old('kategori_id')==$k->id)>{{ $k->ad }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Marka</label>
                        <select name="marka_id" class="w-full px-3 py-2 border rounded">
                            <option value="">Seçiniz</option>
                            @foreach($markalar as $m)
                                <option value="{{ $m->id }}" @selected(old('marka_id')==$m->id)>{{ $m->ad }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="aktif" id="aktif" value="1" @checked(old('aktif'))>
                        <label for="aktif" class="text-sm text-gray-700">Aktif</label>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Görsel URL</label>
                    <input type="url" name="gorsel" value="{{ old('gorsel') }}" class="w-full px-3 py-2 border rounded mb-3" placeholder="https://...">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Görsel Dosya</label>
                    <input type="file" name="gorsel_dosya" accept="image/*" class="w-full px-3 py-2 border rounded" onchange="(e)=>{}" x-on:change="preview = URL.createObjectURL($event.target.files[0])">
                    <div class="mt-3">
                        <template x-if="preview">
                            <img :src="preview" alt="Önizleme" class="rounded border max-h-48">
                        </template>
                    </div>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Açıklama</label>
                <textarea name="aciklama" rows="4" class="w-full px-3 py-2 border rounded">{{ old('aciklama') }}</textarea>
            </div>
        </div>

        <!-- Detay & SEO -->
        <div x-show="tab==='detay'" class="bg-white rounded-lg shadow p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title') }}" class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                    <input type="text" name="meta_description" value="{{ old('meta_description') }}" class="w-full px-3 py-2 border rounded">
                </div>
            </div>
            <div class="text-sm text-gray-500">SEO alanları arama sonuçlarındaki görünürlüğü iyileştirir.</div>
        </div>

        <!-- Mağazalar -->
        <div x-show="tab==='magazalar'" class="bg-white rounded-lg shadow p-6 space-y-3">
            @if($magazalar->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach($magazalar as $magaza)
                        <label class="flex items-center gap-2 p-2 border rounded">
                            <input type="checkbox" name="magazalar[]" value="{{ $magaza->id }}" @checked(in_array($magaza->id, old('magazalar', [])))>
                            <span>{{ $magaza->ad }} @if($magaza->platform)<span class="text-gray-500 text-xs">({{ $magaza->platform }})</span>@endif</span>
                        </label>
                    @endforeach
                </div>
            @else
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded">Kayıtlı mağaza yok. Önce mağaza ekleyin.</div>
            @endif
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-2">
            <a href="{{ route('admin.urun.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded">Vazgeç</a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Kaydet</button>
        </div>
    </form>
</div>
@endsection
