@extends('admin.layouts.app')

@section('title', 'Mağaza Listesi')
@section('page-title', 'Mağaza Listesi')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold">Mağaza Listesi</h2>
        <a href="{{ route('admin.magaza.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Yeni Mağaza</a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-2">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-2">{{ session('error') }}</div>
    @endif
    @if (session('warning'))
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded mb-2">{{ session('warning') }}</div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">#</th>
                    <th class="px-4 py-2 text-left">Ad</th>
                    <th class="px-4 py-2 text-left">Platform</th>
                    <th class="px-4 py-2 text-left">API Anahtarı</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($magazalar as $m)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $m->id }}</td>
                        <td class="px-4 py-2">{{ $m->ad }}</td>
                        <td class="px-4 py-2">{{ $m->platform ?? '—' }}</td>
                        <td class="px-4 py-2"><code>{{ $m->api_anahtari ? Str::limit($m->api_anahtari, 20) : '—' }}</code></td>
                        <td class="px-4 py-2 text-right space-x-2">
                            <a href="{{ route('admin.magaza.edit', $m) }}" class="px-3 py-1 bg-white border rounded hover:bg-gray-50 text-sm">Düzenle</a>
                            <form action="{{ route('admin.magaza.destroy', $m) }}" method="POST" class="inline-block" onsubmit="return confirm('Bu mağaza silinsin mi? Bu işlem geri alınamaz.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm">Sil</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">Kayıt bulunamadı.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $magazalar->links() }}
    </div>
</div>
@endsection
