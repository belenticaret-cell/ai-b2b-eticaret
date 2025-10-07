<?php

namespace App\Services;

use App\Models\Magaza;
use Illuminate\Support\Facades\Log;

class MockPlatformEntegrasyonService extends PlatformEntegrasyonService
{
    private bool $mockMode = true;
    
    public function enableMockMode(bool $enabled = true): void
    {
        $this->mockMode = $enabled;
    }
    
    protected function trendyolKatalogCek(Magaza $magaza, int $sayfa = 1, int $size = 50): array
    {
        if (!$this->mockMode) {
            return parent::trendyolKatalogCek($magaza, $sayfa, $size);
        }
        
        // Mock successful response
        return $this->mockTrendyolSuccess($magaza, $sayfa, $size);
    }
    
    private function mockTrendyolSuccess(Magaza $magaza, int $sayfa, int $size): array
    {
        $correlationId = 'mock-' . uniqid();
        
        Log::info("🎭 MOCK: Trendyol katalog çekme simülasyonu", [
            'magaza_id' => $magaza->id,
            'sayfa' => $sayfa,
            'size' => $size,
            'correlation_id' => $correlationId
        ]);
        
        // Mock products data
        $mockProducts = [
            [
                'id' => 'mock-001',
                'title' => 'Test Ürün 1 - Mock Data',
                'barcode' => '1234567890123',
                'price' => 99.90,
                'stockQuantity' => 50,
                'categoryName' => 'Test Kategori',
                'brand' => 'Test Marka',
                'description' => 'Bu mock test ürünüdür.',
                'images' => [
                    ['url' => 'https://via.placeholder.com/400x400?text=Mock+Product+1']
                ]
            ],
            [
                'id' => 'mock-002', 
                'title' => 'Test Ürün 2 - Mock Data',
                'barcode' => '1234567890124',
                'price' => 149.90,
                'stockQuantity' => 25,
                'categoryName' => 'Test Kategori',
                'brand' => 'Test Marka',
                'description' => 'Bu da mock test ürünüdür.',
                'images' => [
                    ['url' => 'https://via.placeholder.com/400x400?text=Mock+Product+2']
                ]
            ]
        ];
        
        return [
            'success' => true,
            'message' => '🎭 Mock: Trendyol katalog başarıyla çekildi',
            'correlation_id' => $correlationId,
            'data' => [
                'urunler' => $mockProducts,
                'toplam_urun' => count($mockProducts),
                'sayfa' => $sayfa,
                'toplam_sayfa' => 1,
                'size' => $size
            ],
            'debug_info' => [
                'mock_mode' => true,
                'test_time' => now()->toDateTimeString(),
                'magaza_platform' => $magaza->platform
            ]
        ];
    }
    
    public function mockTrendyolError(string $errorType = '403'): array
    {
        $correlationId = 'mock-error-' . uniqid();
        
        $errors = [
            '403' => [
                'success' => false,
                'message' => '🚫 MOCK: Cloudflare Engeli (HTTP 403)',
                'error_type' => 'cloudflare_block',
                'error_code' => 403,
                'correlation_id' => $correlationId,
                'retry_info' => [
                    'can_retry' => true,
                    'retry_after_seconds' => 60,
                    'max_retries' => 3
                ]
            ],
            '429' => [
                'success' => false,
                'message' => '🚫 MOCK: Rate Limit Aşıldı (HTTP 429)',
                'error_type' => 'rate_limit',
                'error_code' => 429,
                'correlation_id' => $correlationId,
                'retry_info' => [
                    'can_retry' => true,
                    'retry_after_seconds' => 120,
                    'max_retries' => 5
                ]
            ],
            '556' => [
                'success' => false,
                'message' => '🚫 MOCK: Service Unavailable (HTTP 556)',
                'error_type' => 'service_unavailable',
                'error_code' => 556,
                'correlation_id' => $correlationId,
                'retry_info' => [
                    'can_retry' => true,
                    'retry_after_seconds' => 300,
                    'max_retries' => 2
                ]
            ]
        ];
        
        return $errors[$errorType] ?? $errors['403'];
    }
}