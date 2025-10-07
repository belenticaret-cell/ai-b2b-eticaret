<?php
// Apache ve PHP test dosyası
echo "✅ Apache ve PHP çalışıyor!<br>";
echo "📁 Dosya konumu: " . __FILE__ . "<br>";
echo "🌐 HTTP Host: " . ($_SERVER['HTTP_HOST'] ?? 'bilinmiyor') . "<br>";
echo "📋 Request URI: " . ($_SERVER['REQUEST_URI'] ?? 'bilinmiyor') . "<br>";
echo "🔧 Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'bilinmiyor') . "<br>";

// Laravel bootstrap test
$bootstrapPath = __DIR__ . '/../bootstrap/app.php';
if (file_exists($bootstrapPath)) {
    echo "✅ Laravel bootstrap dosyası bulundu<br>";
    
    // Laravel uygulamasını başlat
    try {
        require_once $bootstrapPath;
        echo "✅ Laravel başarıyla yüklendi<br>";
    } catch (Exception $e) {
        echo "❌ Laravel yüklenirken hata: " . $e->getMessage() . "<br>";
    }
} else {
    echo "❌ Laravel bootstrap dosyası bulunamadı: " . $bootstrapPath . "<br>";
}

// Route listesi
echo "<br><h3>Test Linkleri:</h3>";
echo '<a href="/ai-b2b/admin/test">Admin Test</a><br>';
echo '<a href="/ai-b2b/admin/dashboard">Admin Dashboard</a><br>';
echo '<a href="/ai-b2b/">Ana Sayfa</a><br>';
?>