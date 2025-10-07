<?php

use App\Models\Kullanici;
use App\Models\SayfaIcerik;

it('admin sayfa duzenle sayfasini gosterir', function () {
    $admin = Kullanici::factory()->admin()->create();
    $sayfa = SayfaIcerik::create([
        'baslik' => 'Test Sayfa',
        'slug' => 'test-sayfa',
        'icerik' => 'İçerik',
        'durum' => true,
        'sira' => 1,
        'tip' => 'sayfa',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.sayfalar.edit', $sayfa))
        ->assertStatus(200)
        ->assertSee('Sayfa Düzenle');
});

it('admin sayfayi guncelleyebilir', function () {
    $admin = Kullanici::factory()->admin()->create();
    $sayfa = SayfaIcerik::create([
        'baslik' => 'Test Sayfa',
        'slug' => 'test-sayfa',
        'icerik' => 'İçerik',
        'durum' => true,
        'sira' => 1,
        'tip' => 'sayfa',
    ]);

    $payload = [
        'baslik' => 'Güncellenmiş Başlık',
        'icerik' => 'Yeni içerik',
        'meta_baslik' => 'Meta',
        'meta_aciklama' => 'Açıklama',
        'durum' => 'on',
        'sira' => 2,
        'tip' => 'sayfa',
    ];

    $this->actingAs($admin)
        ->put(route('admin.sayfalar.update', $sayfa), $payload)
        ->assertRedirect(route('admin.sayfalar'));

    $sayfa->refresh();
    expect($sayfa->baslik)->toBe('Güncellenmiş Başlık');
    expect($sayfa->sira)->toBe(2);
});
