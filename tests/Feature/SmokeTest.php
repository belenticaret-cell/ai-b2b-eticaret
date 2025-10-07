<?php

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Kullanici;

it('vitrin ana sayfa 200 doner', function() {
    $this->get(route('vitrin.index'))->assertStatus(200);
});

it('ozellikler ve videolar sayfasi 200 doner', function() {
    $this->get(route('sayfa.ozellikler'))->assertStatus(200);
});

it('admin anasayfa ayarlari kayit edilir', function() {
    $admin = Kullanici::factory()->create(['rol' => 'admin']);
    $this->actingAs($admin);

    $resp = $this->post(route('admin.anasayfa.guncelle'), [
        'theme_header_aktif' => '1',
        'theme_footer_aktif' => '1',
        'theme_logo_position' => 'left',
        'theme_logo_max_h' => '40',
    ]);
    $resp->assertRedirect();
});

it('logo yukleme sahte dosya ile calisir', function() {
    Storage::fake('public');
    $admin = Kullanici::factory()->create(['rol' => 'admin']);
    $this->actingAs($admin);
    $file = UploadedFile::fake()->create('logo.png', 10, 'image/png');

    $resp = $this->post(route('admin.anasayfa.guncelle'), [
        'theme_header_aktif' => '1',
        'theme_footer_aktif' => '1',
        'theme_logo_position' => 'left',
        'theme_logo_max_h' => '40',
        'theme_logo_dosya' => $file,
    ]);
    $resp->assertRedirect();
});
