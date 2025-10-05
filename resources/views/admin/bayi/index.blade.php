@extends('admin.layouts.app')

@section('title', 'Bayiler')
@section('page-title', 'Bayi Yönetimi')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <form method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Bayi adı/email/telefon" class="px-3 py-2 border rounded">
            <button class="px-4 py-2 bg-blue-600 text-white rounded">Ara</button>
        </form>
        <a href="{{ route('admin.bayi.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Yeni Bayi</a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">#</th>
                    <th class="px-4 py-2 text-left">Ad</th>
                    <th class="px-4 py-2 text-left">Email</th>
                    <th class="px-4 py-2 text-left">Telefon</th>
                    <th class="px-4 py-2 text-left">Kullanıcı</th>
                    <th class="px-4 py-2 text-left">Özel Fiyat</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($bayiler as $b)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $b->id }}</td>
                        <td class="px-4 py-2">{{ $b->ad }}</td>
                        <td class="px-4 py-2">{{ $b->email ?? '—' }}</td>
                        <td class="px-4 py-2">{{ $b->telefon ?? '—' }}</td>
                        <td class="px-4 py-2">{{ $b->kullanici?->ad ?? '—' }}</td>
                        <td class="px-4 py-2">{{ $b->fiyatlar_count }} kayıt</td>
                        <td class="px-4 py-2 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.bayi.show', $b) }}" class="px-3 py-1 bg-white border rounded hover:bg-gray-50 text-sm">Görüntüle</a>
                                <a href="{{ route('admin.bayi.edit', $b) }}" class="px-3 py-1 bg-white border rounded hover:bg-gray-50 text-sm">Düzenle</a>
                                <form method="POST" action="{{ route('admin.bayi.destroy', $b) }}" onsubmit="return confirm('Bu bayiyi silmek istiyor musunuz?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm">Sil</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-8 text-center text-gray-500">Kayıt bulunamadı.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $bayiler->links() }}
    </div>
</div>
@endsection
