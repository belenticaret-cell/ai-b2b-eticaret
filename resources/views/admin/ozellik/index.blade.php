@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold">Özellik Yönetimi</h1>
    <a href="{{ route('admin.ozellik.create') }}" class="px-3 py-2 bg-blue-600 text-white rounded">Yeni Özellik</a>
  </div>
  <form class="mb-3 grid grid-cols-1 md:grid-cols-3 gap-2">
    <input type="text" name="search" placeholder="Ad veya Değer ara" value="{{ request('search') }}" class="border rounded px-2 py-1">
    <select name="urun_id" class="border rounded px-2 py-1">
      <option value="">Tüm Ürünler</option>
      @foreach($urunler as $u)
        <option value="{{ $u->id }}" @selected(request('urun_id')==$u->id)>{{ $u->ad }}</option>
      @endforeach
    </select>
    <button class="px-3 py-1 border rounded">Filtrele</button>
  </form>

  <form method="POST" action="{{ route('admin.ozellik.bulk-delete') }}">
    @csrf
    <div class="bg-white shadow rounded overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-3 py-2"><input type="checkbox" x-data @click="$root.querySelectorAll('input[name=ids\\[\\]]').forEach(cb=>cb.checked=$event.target.checked)"></th>
            <th class="px-3 py-2 text-left">Ürün</th>
            <th class="px-3 py-2 text-left">Ad</th>
            <th class="px-3 py-2 text-left">Değer</th>
            <th class="px-3 py-2 text-left">Birim</th>
            <th class="px-3 py-2 text-right">Sıra</th>
            <th class="px-3 py-2 text-right"></th>
          </tr>
        </thead>
        <tbody>
          @forelse($ozellikler as $o)
          <tr class="border-t">
            <td class="px-3 py-2"><input type="checkbox" name="ids[]" value="{{ $o->id }}"></td>
            <td class="px-3 py-2">{{ $o->urun?->ad ?? '-' }}</td>
            <td class="px-3 py-2 font-medium">{{ $o->ad }}</td>
            <td class="px-3 py-2">{{ $o->deger }}</td>
            <td class="px-3 py-2">{{ $o->birim }}</td>
            <td class="px-3 py-2 text-right">{{ $o->sira }}</td>
            <td class="px-3 py-2 text-right">
              <a href="{{ route('admin.ozellik.edit', $o) }}" class="px-3 py-1 border rounded">Düzenle</a>
              <form action="{{ route('admin.ozellik.destroy', $o) }}" method="POST" class="inline" onsubmit="return confirm('Silinsin mi?')">
                @csrf @method('DELETE')
                <button class="px-3 py-1 border rounded text-red-600">Sil</button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="7" class="px-3 py-6 text-center text-gray-500">Kayıt bulunamadı.</td></tr>
          @endforelse
        </tbody>
      </table>
      <div class="p-3">{{ $ozellikler->links() }}</div>
    </div>
    <div class="mt-3">
      <button class="px-3 py-2 border rounded" onclick="return confirm('Seçili özellikler silinsin mi?')">Seçiliyi Sil</button>
    </div>
  </form>
</div>
@endsection
