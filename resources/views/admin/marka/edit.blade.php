@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
  <h1 class="text-2xl font-bold mb-4">Marka Düzenle</h1>
  <form action="{{ route('admin.marka.update', $marka) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-white p-4 rounded shadow">
    @csrf
    @method('PUT')
    <div>
      <label class="block text-sm mb-1">Ad</label>
      <input type="text" name="ad" value="{{ old('ad', $marka->ad) }}" class="w-full border rounded px-2 py-1" required>
    </div>
    <div>
      <label class="block text-sm mb-1">Logo URL</label>
      <input type="url" name="logo" value="{{ old('logo', $marka->logo) }}" class="w-full border rounded px-2 py-1">
    </div>
    <div class="md:col-span-2">
      <label class="block text-sm mb-1">Açıklama</label>
      <textarea name="aciklama" rows="3" class="w-full border rounded px-2 py-1">{{ old('aciklama', $marka->aciklama) }}</textarea>
    </div>
    <div>
      <label class="block text-sm mb-1">SEO Başlık</label>
      <input type="text" name="seo_baslik" value="{{ old('seo_baslik', $marka->seo_baslik) }}" class="w-full border rounded px-2 py-1">
    </div>
    <div>
      <label class="block text-sm mb-1">SEO Açıklama</label>
      <input type="text" name="seo_aciklama" value="{{ old('seo_aciklama', $marka->seo_aciklama) }}" class="w-full border rounded px-2 py-1">
    </div>
    <div>
      <label class="block text-sm mb-1">Durum</label>
      <label class="inline-flex items-center gap-2"><input type="checkbox" name="durum" {{ old('durum', $marka->durum) ? 'checked' : '' }}> Aktif</label>
    </div>
    <div class="md:col-span-2 flex gap-2">
      <button class="px-4 py-2 bg-blue-600 text-white rounded">Güncelle</button>
      <a href="{{ route('admin.marka.index') }}" class="px-4 py-2 border rounded">İptal</a>
    </div>
  </form>
</div>
@endsection
