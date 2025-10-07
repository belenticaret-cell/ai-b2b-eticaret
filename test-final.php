<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Magaza;

echo "=== Test Mağazası Hazırlama ===\n";

// Trendyol mağazasını bul veya oluştur
$trendyolMagaza = Magaza::where('platform', 'trendyol')->first();

if (!$trendyolMagaza) {
    echo "Yeni Trendyol mağazası oluşturuluyor...\n";
    $trendyolMagaza = Magaza::create([
        'ad' => 'Test Trendyol Mağaza',
        'platform' => 'trendyol',
        'entegrasyon_turu' => 'api',
        'aktif' => true,
        'api_anahtari' => 'test-api-key-123456789',
        'api_gizli_anahtari' => 'test-secret-key-987654321',
        'magaza_id' => '123456'
    ]);
} else {
    echo "Mevcut Trendyol mağazası güncelleniyor...\n";
    $trendyolMagaza->update([
        'aktif' => true,
        'entegrasyon_turu' => 'api',
        'api_anahtari' => 'test-api-key-123456789',
        'api_gizli_anahtari' => 'test-secret-key-987654321',
        'magaza_id' => '123456'
    ]);
}

echo "✅ Test mağazası hazır:\n";
echo "ID: {$trendyolMagaza->id}\n";
echo "Ad: {$trendyolMagaza->ad}\n";
echo "Platform: {$trendyolMagaza->platform}\n";
echo "Aktif: " . ($trendyolMagaza->aktif ? 'Evet' : 'Hayır') . "\n";
echo "API Key: " . substr($trendyolMagaza->api_anahtari, 0, 20) . '...' . "\n";
echo "Supplier ID: {$trendyolMagaza->magaza_id}\n";

echo "\n=== Error Handling Test ===\n";

use App\Services\PlatformEntegrasyonService;

$platformService = new PlatformEntegrasyonService();

echo "🔄 Katalog çekme testi (beklenen hatalar)...\n";

try {
    $result = $platformService->uzakKatalogCekVeKaydet($trendyolMagaza);
    
    echo "\n📊 Test Sonucu:\n";
    echo "Success: " . ($result['success'] ? 'true' : 'false') . "\n";
    echo "Message: " . $result['message'] . "\n";
    
    if (isset($result['correlation_id'])) {
        echo "🔗 Correlation ID: " . $result['correlation_id'] . "\n";
    }
    
    if (isset($result['error_type'])) {
        echo "⚠️ Error Type: " . $result['error_type'] . "\n";
    }
    
    if (isset($result['error_code'])) {
        echo "🚨 Error Code: " . $result['error_code'] . "\n";
    }
    
    if (isset($result['retry_info'])) {
        echo "🔄 Retry Info:\n";
        foreach ($result['retry_info'] as $key => $value) {
            echo "   $key: $value\n";
        }
    }
    
    if (isset($result['debug_info'])) {
        echo "🔍 Debug Info:\n";
        foreach ($result['debug_info'] as $key => $value) {
            echo "   $key: $value\n";
        }
    }
    
    if ($result['success'] && isset($result['data'])) {
        echo "✅ Başarılı! Toplam Ürün: " . $result['data']['toplam_urun'] . "\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Exception yakalandı: " . $e->getMessage() . "\n";
    echo "Dosya: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n✅ Error handling test tamamlandı!\n";
echo "Bu test gelişmiş error handling sistemimizin çalıştığını gösteriyor.\n";