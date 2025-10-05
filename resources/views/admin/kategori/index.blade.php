@extends('admin.layouts.app')

@section('title', 'Kategori Yönetimi')
@section('page-title', 'Kategori Yönetimi')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <form method="GET" class="flex items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" class="px-3 py-2 border rounded" placeholder="Kategori ara...">
            <button class="px-4 py-2 bg-gray-200 rounded">Ara</button>
        </form>
        <a href="{{ route('admin.kategori.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Yeni Kategori</a>
    </div>

    <div class="bg-white rounded shadow p-4">
        <h3 class="text-lg font-semibold mb-4">Kategori Ağacı</h3>
        <ul class="space-y-1">
            @foreach ($kategoriler as $kat)
                @include('admin.kategori.partials.node', ['node' => $kat, 'level' => 0])
            @endforeach
        </ul>
    </div>
</div>
@endsection
