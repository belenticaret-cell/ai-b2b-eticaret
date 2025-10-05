@extends('admin.layouts.app')

@section('title', 'Mağazalar')
@section('page-title', 'Mağaza Yönetimi')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold">Mağazalar</h2>
        <a href="{{ route('admin.magaza.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Yeni Mağaza</a>
    </div>

    @if (session('status'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('status') }}</div>
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
                        <td class="px-4 py-2 text-right">
                            <a href="{{ route('admin.magaza.edit', $m) }}" class="px-3 py-1 bg-white border rounded hover:bg-gray-50 text-sm">Düzenle</a>
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
