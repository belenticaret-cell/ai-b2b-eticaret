<?php

use App\Models\Kullanici;
use App\Models\Magaza;
use Illuminate\Support\Facades\Http;

it('hepsiburada katalog cekme magaza_id olmadan uyarir', function () {
    Http::fake();
    $admin = Kullanici::factory()->create(['rol' => 'admin']);
    $this->actingAs($admin);

    $magaza = Magaza::create([
        'ad' => 'HB Test',
        'platform' => 'Hepsiburada',
        'entegrasyon_turu' => 'hepsiburada',
        'aktif' => true,
        'test_mode' => true,
    ]);

    $resp = $this->post(route('admin.magaza.katalog.cek', $magaza));
    $resp->assertRedirect();
    $resp->assertSessionHas('error', fn($m) => str_contains($m, 'Mağaza kimliği (magaza_id) tanımlı değil'));
});

it('hepsiburada katalog cekme magaza_id ile calisir ve upsert sayisini dondurur', function () {
    $admin = Kullanici::factory()->create(['rol' => 'admin']);
    $this->actingAs($admin);

    $magaza = Magaza::create([
        'ad' => 'HB Test',
        'platform' => 'Hepsiburada',
        'entegrasyon_turu' => 'hepsiburada',
        'aktif' => true,
        'test_mode' => true,
        'magaza_id' => 'TESTMERCHANTID',
        'api_anahtari' => 'user',
        'api_gizli_anahtari' => 'pass',
    ]);

    Http::fake([
        'https://listing-external-sit.hepsiburada.com/*' => Http::response([
            'items' => [
                ['sku' => 'ABC-1', 'title' => 'Ürün A', 'price' => 10.5, 'quantity' => 5],
                ['sku' => 'ABC-2', 'title' => 'Ürün B', 'price' => 20, 'quantity' => 2],
            ]
        ], 200),
    ]);

    $resp = $this->post(route('admin.magaza.katalog.cek', $magaza));
    $resp->assertRedirect();
    $resp->assertSessionHas('success', fn($m) => str_contains(mb_strtolower($m), 'katalog çekildi'));
});
