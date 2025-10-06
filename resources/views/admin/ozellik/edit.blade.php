@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
  <h1 class="text-2xl font-bold mb-4">Özellik Düzenle</h1>
  <form action="{{ route('admin.ozellik.update', $ozellik) }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-white p-4 rounded shadow">
    @csrf
    @method('PUT')
    <div>
      <label class="block text-sm mb-1">Ürün</label>
      <select name="urun_id" class="w-full border rounded px-2 py-1" required>
        @foreach($urunler as $u)
          <option value="{{ $u->id }}" @selected(old('urun_id',$ozellik->urun_id)==$u->id)>{{ $u->ad }}</option>
        @endforeach
      </select>
    </div>
    <div>
      <label class="block text-sm mb-1">Ad</label>
      <input type="text" name="ad" value="{{ old('ad',$ozellik->ad) }}" class="w-full border rounded px-2 py-1" required>
    </div>
    <div>
      <label class="block text-sm mb-1">Değer</label>
      <input type="text" name="deger" value="{{ old('deger',$ozellik->deger) }}" class="w-full border rounded px-2 py-1">
    </div>
    <div>
      <label class="block text-sm mb-1">Birim</label>
      <input type="text" name="birim" value="{{ old('birim',$ozellik->birim) }}" class="w-full border rounded px-2 py-1" placeholder="Örn: cm, kg">
    </div>
    <div>
      <label class="block text-sm mb-1">Sıra</label>
      <input type="number" name="sira" value="{{ old('sira',$ozellik->sira) }}" class="w-full border rounded px-2 py-1" min="0">
    </div>
    <div class="md:col-span-2 flex gap-2">
      <button class="px-4 py-2 bg-blue-600 text-white rounded">Güncelle</button>
      <a href="{{ route('admin.ozellik.index') }}" class="px-4 py-2 border rounded">İptal</a>
    </div>
  </form>
</div>
@endsection
