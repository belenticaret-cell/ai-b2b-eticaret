@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold">Marka Yönetimi</h1>
    <a href="{{ route('admin.marka.create') }}" class="px-3 py-2 bg-blue-600 text-white rounded">Yeni Marka</a>
  </div>

  <form method="GET" class="mb-3">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ara..." class="border rounded px-2 py-1" />
    <button class="px-3 py-1 border rounded">Ara</button>
  </form>

  <div class="bg-white shadow rounded">
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-3 py-2 text-left">Logo</th>
            <th class="px-3 py-2 text-left">Ad</th>
            <th class="px-3 py-2">Durum</th>
            <th class="px-3 py-2 text-right"></th>
          </tr>
        </thead>
        <tbody>
          @forelse($markalar as $m)
          <tr class="border-t">
            <td class="px-3 py-2">
              @if($m->logo)
                <img src="{{ $m->logo }}" alt="{{ $m->ad }}" class="w-10 h-10 object-contain" />
              @else
                <div class="w-10 h-10 bg-gray-100" title="No logo"></div>
              @endif
            </td>
            <td class="px-3 py-2 font-medium">{{ $m->ad }}</td>
            <td class="px-3 py-2">
              <span class="px-2 py-1 rounded text-xs {{ $m->durum ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ $m->durum ? 'Aktif' : 'Pasif' }}</span>
            </td>
            <td class="px-3 py-2 text-right">
              <a href="{{ route('admin.marka.edit', $m) }}" class="px-3 py-1 border rounded">Düzenle</a>
              <form action="{{ route('admin.marka.destroy', $m) }}" method="POST" class="inline" onsubmit="return confirm('Silinsin mi?')">
                @csrf @method('DELETE')
                <button class="px-3 py-1 border rounded text-red-600">Sil</button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="4" class="px-3 py-6 text-center text-gray-500">Kayıt bulunamadı.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="p-3">{{ $markalar->links() }}</div>
  </div>
</div>
@endsection
