@extends('layouts.app')

@section('title', 'Özellikler ve Kullanım Videoları')
@section('meta_description', 'AI B2B E-Ticaret Platformu özellikleri ve kullanım videoları')

@section('content')
<div class="container mx-auto px-4 py-10">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Özellikler ve Kullanım Videoları</h1>
        <p class="text-gray-600 mb-10">Platformun öne çıkan özelliklerini ve kısa kullanım videolarını bu sayfada bulabilirsiniz. Daha detaylı dokümantasyon için README ve admin panel içindeki yardım bağlantılarına göz atın.</p>

        <!-- Özellikler Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            <!-- B2B Fiyatlandırma -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-2">B2B Fiyatlandırma</h3>
                <p class="text-gray-600 text-sm">Bayilere özel fiyat, iskonto ve toplu sipariş desteği; bayi paneli ve API’ler.</p>
            </div>
            <!-- Çoklu Mağaza Entegrasyonları -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-2">Çoklu Mağaza Entegrasyonları</h3>
                <p class="text-gray-600 text-sm">Trendyol, Hepsiburada, N11, Amazon vb. ile ürün, stok, fiyat ve sipariş senkronizasyonu.</p>
            </div>
            <!-- XML Import/Export -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-2">XML Import/Export</h3>
                <p class="text-gray-600 text-sm">Marka, kategori, resim ve dinamik özellik desteğiyle ürün içe/dışa aktarma.</p>
            </div>
            <!-- Kategori & Marka Yönetimi -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-2">Kategori & Marka Yönetimi</h3>
                <p class="text-gray-600 text-sm">Ağaç yapıda kategori, marka ve ürün özellikleri yönetimi.</p>
            </div>
            <!-- Sepet ve Vitrin -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-2">Sepet ve Vitrin</h3>
                <p class="text-gray-600 text-sm">Session tabanlı sepet, modern vitrin ve hızlı arama.</p>
            </div>
            <!-- Modül Tabanlı Yapı & Audit -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-2">Modül Tabanlı Yapı & Audit</h3>
                <p class="text-gray-600 text-sm">Modül aktif/pasif, yetkilendirme, audit log ve ayar yönetimi.</p>
            </div>
        </div>

        <!-- Kullanım Videoları (Embed) -->
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Kısa Kullanım Videoları</h2>
        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="aspect-video">
                    <iframe class="w-full h-full" src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="Admin Panel Hızlı Tur" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold">Admin Panel Hızlı Tur</h3>
                    <p class="text-sm text-gray-600">Yönetim panelindeki temel menüler ve kısa akışlar.</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="aspect-video">
                    <iframe class="w-full h-full" src="https://www.youtube.com/embed/oHg5SJYRHA0" title="Ürün İçe Aktarma" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                </div>
                <div class="p-4">
                    <h3 class="font-semibold">Ürün İçe Aktarma (XML)</h3>
                    <p class="text-sm text-gray-600">XML ile marka/kategori/özellik uyumlu ürün import akışı.</p>
                </div>
            </div>
        </div>

        <div class="mt-10">
            <a href="{{ route('admin.panel') }}" class="inline-block bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700">Admin Panele Git</a>
        </div>
    </div>
</div>
@endsection
