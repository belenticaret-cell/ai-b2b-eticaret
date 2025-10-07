@extends('admin.layouts.app')

@section('title', 'Platform Katalogu')
@section('page-title', 'Platform Katalogu')

@section('content')
<div class="space-y-6">
  @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
  @endif
  @if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">{{ session('error') }}</div>
  @endif
  <div class="bg-white rounded-lg shadow p-4">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
      <div>
        <label class="text-xs text-gray-600">Mağaza</label>
        <select name="magaza_id" class="w-full border rounded px-2 py-1">
          <option value="">Seçiniz</option>
          @foreach($magazalar as $m)
            <option value="{{ $m->id }}" {{ (int)$magazaId === (int)$m->id ? 'selected' : '' }}>{{ $m->ad }} ({{ $m->platform }})</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="text-xs text-gray-600">Ara</label>
        <input type="text" name="q" value="{{ $q }}" class="w-full border rounded px-2 py-1" placeholder="SKU veya başlık">
      </div>
      <div>
        <label class="text-xs text-gray-600">Durum</label>
        <select name="durum" class="w-full border rounded px-2 py-1">
          <option value="">Hepsi</option>
          <option value="eslesmis" {{ $durum==='eslesmis' ? 'selected' : '' }}>Eşleşmiş</option>
          <option value="eslesmemis" {{ $durum==='eslesmemis' ? 'selected' : '' }}>Eşleşmemiş</option>
        </select>
      </div>
      <div class="flex items-end justify-end gap-2">
        <button class="px-3 py-2 bg-blue-600 text-white rounded" type="submit">Filtrele</button>
      </div>
    </form>
    @if($magaza)
    <form method="POST" action="{{ route('admin.magaza.katalog.cek', $magaza) }}" class="mt-3">
      @csrf
      <button class="px-3 py-2 bg-indigo-600 text-white rounded" onclick="return confirm('Seçilen mağazadan katalog çekilsin mi?')">Katalog Çek</button>
    </form>
    @endif
  </div>

  <div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-2 text-left">SKU</th>
          <th class="px-4 py-2 text-left">Başlık</th>
          <th class="px-4 py-2 text-left">Fiyat</th>
          <th class="px-4 py-2 text-left">Stok</th>
          <th class="px-4 py-2 text-left">Yerel</th>
        </tr>
      </thead>
      <tbody>
        @forelse($platformUrunleri as $p)
          <tr class="border-t">
            <td class="px-4 py-2">{{ $p->platform_sku }}</td>
            <td class="px-4 py-2">{{ Str::limit($p->baslik, 80) }}</td>
            <td class="px-4 py-2">{{ $p->fiyat ? number_format($p->fiyat, 2) . ' ₺' : '-' }}</td>
            <td class="px-4 py-2">{{ $p->stok ?? '-' }}</td>
            <td class="px-4 py-2">
              @if($p->urun)
                <span class="inline-flex items-center px-2 py-1 text-xs rounded bg-green-100 text-green-800">Eşleşti: {{ Str::limit($p->urun->ad, 24) }}</span>
              @else
                <span class="inline-flex items-center px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800">Eşleşmemiş</span>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="px-4 py-8 text-center text-gray-500">Kayıt bulunamadı. Üstten mağaza seçin ve “Katalog Çek” yapın.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($platformUrunleri instanceof \Illuminate\Contracts\Pagination\Paginator || $platformUrunleri instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
    <div>
      {{ $platformUrunleri->links() }}
    </div>
  @endif
</div>
@endsection
