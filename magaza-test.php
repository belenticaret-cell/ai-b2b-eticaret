<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Magaza;
use App\Services\PlatformEntegrasyonService;

echo "=== Mağaza Bilgileri ===\n";

$magazalar = Magaza::all();
foreach ($magazalar as $magaza) {
    echo "ID: {$magaza->id}\n";
    echo "Ad: {$magaza->ad}\n";
    echo "Platform: {$magaza->platform}\n";
    echo "Aktif: " . ($magaza->aktif ? 'Evet' : 'Hayır') . "\n";
    echo "API Key: " . ($magaza->api_key ? substr($magaza->api_key, 0, 20) . '...' : 'YOK') . "\n";
    echo "Supplier ID: " . ($magaza->supplier_id ?? 'YOK') . "\n";
    echo "---\n";
}

echo "\n=== Trendyol Katalog Test ===\n";

$trendyolMagaza = Magaza::where('platform', 'trendyol')->where('aktif', true)->first();

if (!$trendyolMagaza) {
    echo "❌ Aktif Trendyol mağazası bulunamadı!\n";
    exit(1);
}

echo "🏪 Test Mağazası: {$trendyolMagaza->ad} (ID: {$trendyolMagaza->id})\n";

$platformService = new PlatformEntegrasyonService();

echo "🔄 Katalog çekme başlıyor...\n";

try {
    $result = $platformService->trendyolKatalogCek($trendyolMagaza->id, 1, 5);
    
    echo "\n📊 Sonuç:\n";
    echo "Success: " . ($result['success'] ? 'true' : 'false') . "\n";
    echo "Message: " . $result['message'] . "\n";
    
    if (isset($result['correlation_id'])) {
        echo "Correlation ID: " . $result['correlation_id'] . "\n";
    }
    
    if (isset($result['error_type'])) {
        echo "Error Type: " . $result['error_type'] . "\n";
    }
    
    if (isset($result['retry_info'])) {
        echo "Retry Info: " . json_encode($result['retry_info'], JSON_PRETTY_PRINT) . "\n";
    }
    
    if ($result['success'] && isset($result['data'])) {
        echo "Toplam Ürün: " . $result['data']['toplam_urun'] . "\n";
        echo "Sayfa: " . $result['data']['sayfa'] . "/" . $result['data']['toplam_sayfa'] . "\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Exception: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}