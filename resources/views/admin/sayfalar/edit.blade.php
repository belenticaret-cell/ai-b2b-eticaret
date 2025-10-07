@extends('admin.layouts.app')

@section('title', 'Sayfa Düzenle')
@section('page-title', 'Sayfa Düzenle')

@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('admin.sayfalar.update', $sayfa) }}">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Başlık</label>
                    <input type="text" name="baslik" value="{{ old('baslik', $sayfa->baslik) }}" class="w-full px-3 py-2 border rounded" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tip</label>
                    <select name="tip" class="w-full px-3 py-2 border rounded" required>
                        <option value="sayfa" {{ $sayfa->tip==='sayfa' ? 'selected' : '' }}>Sayfa</option>
                        <option value="blog" {{ $sayfa->tip==='blog' ? 'selected' : '' }}>Blog</option>
                        <option value="duyuru" {{ $sayfa->tip==='duyuru' ? 'selected' : '' }}>Duyuru</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sıra</label>
                    <input type="number" name="sira" value="{{ old('sira', $sayfa->sira) }}" class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label class="inline-flex items-center mt-6">
                        <input type="checkbox" name="durum" class="rounded" {{ old('durum', $sayfa->durum) ? 'checked' : '' }}>
                        <span class="ml-2">Aktif</span>
                    </label>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">İçerik</label>
                    <textarea name="icerik" rows="10" class="w-full px-3 py-2 border rounded" required>{{ old('icerik', $sayfa->icerik) }}</textarea>
                </div>
                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Meta Başlık</label>
                        <input type="text" name="meta_baslik" value="{{ old('meta_baslik', $sayfa->meta_baslik) }}" class="w-full px-3 py-2 border rounded">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Meta Açıklama</label>
                        <input type="text" name="meta_aciklama" value="{{ old('meta_aciklama', $sayfa->meta_aciklama) }}" class="w-full px-3 py-2 border rounded">
                    </div>
                </div>
            </div>
            <div class="flex justify-between mt-6">
                <a href="{{ route('sayfa.goster', $sayfa->slug) }}" target="_blank" class="text-blue-600">Önizleme</a>
                <div>
                    <a href="{{ route('admin.sayfalar') }}" class="mr-3 px-4 py-2 border rounded">İptal</a>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Güncelle</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
