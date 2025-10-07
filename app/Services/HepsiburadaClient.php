<?php

namespace App\Services;

use App\Models\Magaza;
use Illuminate\Support\Facades\Http;

class HepsiburadaClient
{
    private function baseUrl(Magaza $magaza): string
    {
        $cfgUrl = $magaza->getPlatformConfig()['base_url'] ?? '';
        $base = $magaza->api_url ?: $cfgUrl;
        $merchantId = $magaza->magaza_id ?: '';
        // Test ortamı için domain'e -sit ekle (config veya api_url üretilecekse)
        if ($magaza->test_mode) {
            $base = str_replace('listing-external.hepsiburada.com', 'listing-external-sit.hepsiburada.com', $base);
        } else {
            // Canlıda yanlışlıkla -sit kalmışsa düzelt
            $base = str_replace('listing-external-sit.hepsiburada.com', 'listing-external.hepsiburada.com', $base);
        }
        $base = rtrim($base, '/');
        // {merchantId} veya merchantid placeholderlarını değiştir
        if (!empty($merchantId)) {
            $base = preg_replace('/\{?merchantid\}?/i', trim($merchantId), $base);
        }
        // /listings segmentini garanti et ve merchantId ekini tamamla
        if (stripos($base, '/listings') === false) {
            $base .= '/listings';
        }
        // /listings sonrası merchantId var mı kontrol et
        if (preg_match('#/listings($|/([^/]+))#i', $base, $m)) {
            $hasId = isset($m[2]) && $m[2] !== '' && strcasecmp($m[2], 'merchantid') !== 0;
            if (!$hasId && !empty($merchantId)) {
                $base .= '/' . trim($merchantId);
            }
        }

        return $base;
    }

    private function http(Magaza $magaza)
    {
        $cred = $magaza->getApiCredentials();
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'AI-B2B-ETicaret/1.0'
        ];
        if (!empty($cred['api_key']) && !empty($cred['api_secret'])) {
            $headers['Authorization'] = 'Basic ' . base64_encode(($cred['api_key'] ?? '') . ':' . ($cred['api_secret'] ?? ''));
        }
        return Http::withHeaders($headers)->timeout(30);
    }

    public function test(Magaza $magaza): array
    {
        try {
            if (empty($magaza->magaza_id)) {
                return [
                    'success' => false, 
                    'message' => '❌ Merchant ID eksik: Hepsiburada mağaza kimliği (magaza_id) tanımlı değil.'
                ];
            }
            
            // Basit bir GET ile erişimi test et (ürün listesi ilk sayfa)
            $url = $this->baseUrl($magaza) . '/products?page=0&size=1';
            
            \Log::info('Hepsiburada test isteği', [
                'url' => $url,
                'magaza_id' => $magaza->id,
                'merchant_id' => $magaza->magaza_id,
                'test_mode' => $magaza->test_mode ?? false
            ]);
            
            $res = $this->http($magaza)->get($url);
            
            if ($res->successful()) {
                \Log::info('Hepsiburada test başarılı', ['status' => $res->status()]);
                return [
                    'success' => true, 
                    'message' => '✅ Hepsiburada API bağlantısı başarılı', 
                    'data' => $res->json()
                ];
            }
            
            $status = $res->status();
            $body = $res->body();
            
            \Log::error('Hepsiburada test hatası', [
                'status' => $status,
                'body' => $body,
                'url' => $url
            ]);
            
            // Hata tipine göre mesaj
            if ($status == 401) {
                return [
                    'success' => false, 
                    'message' => '🔐 Kimlik doğrulama hatası (401): API Key/Secret kontrol edin'
                ];
            } elseif ($status == 403) {
                return [
                    'success' => false, 
                    'message' => '🚫 Erişim engeli (403): Merchant ID veya yetki problemi'
                ];
            } elseif ($status == 404) {
                return [
                    'success' => false, 
                    'message' => '❓ Endpoint bulunamadı (404): URL veya Merchant ID kontrol edin'
                ];
            } else {
                return [
                    'success' => false, 
                    'message' => "❌ Hepsiburada API hatası (HTTP {$status}): " . substr($body, 0, 200)
                ];
            }
        } catch (\Exception $e) {
            \Log::error('Hepsiburada test exception', ['error' => $e->getMessage()]);
            return [
                'success' => false, 
                'message' => '💥 Bağlantı hatası: ' . $e->getMessage()
            ];
        }
    }

    public function listProducts(Magaza $magaza, int $page = 0, int $size = 50): array
    {
        try {
            if (empty($magaza->magaza_id)) {
                return [
                    'ok' => false,
                    'status' => 400,
                    'data' => null,
                    'error' => '❌ Merchant ID eksik: Hepsiburada mağaza kimliği (magaza_id) tanımlı değil. Lütfen mağaza bilgilerini güncelleyin.'
                ];
            }
            
            $url = $this->baseUrl($magaza) . "/products?page={$page}&size={$size}";
            
            \Log::info('Hepsiburada ürün listesi isteği', [
                'url' => $url,
                'page' => $page,
                'size' => $size,
                'magaza_id' => $magaza->id
            ]);
            
            $res = $this->http($magaza)->timeout(45)->get($url);
            
            $result = [
                'ok' => $res->successful(),
                'status' => $res->status(),
                'data' => $res->successful() ? $res->json() : null,
                'error' => $res->successful() ? null : $this->formatHepsiburadaError($res)
            ];
            
            if (!$res->successful()) {
                \Log::error('Hepsiburada ürün listesi hatası', [
                    'status' => $res->status(),
                    'body' => $res->body(),
                    'url' => $url
                ]);
            }
            
            return $result;
            
        } catch (\Exception $e) {
            \Log::error('Hepsiburada listProducts exception', [
                'error' => $e->getMessage(),
                'magaza_id' => $magaza->id
            ]);
            
            return [
                'ok' => false,
                'status' => 500,
                'data' => null,
                'error' => '💥 İstek hatası: ' . $e->getMessage()
            ];
        }
    }
    
    private function formatHepsiburadaError($response): string
    {
        $status = $response->status();
        $body = $response->body();
        
        switch ($status) {
            case 401:
                return '🔐 Kimlik doğrulama hatası (401): API Key/Secret kontrol edin';
            case 403:
                return '🚫 Erişim engeli (403): Merchant ID veya yetki problemi';
            case 404:
                return '❓ Endpoint bulunamadı (404): URL veya Merchant ID kontrol edin';
            case 429:
                return '⏱️ Rate limit aşıldı (429): Çok fazla istek, bekleyin';
            case 500:
            case 502:
            case 503:
            case 504:
                return "🔄 Sunucu hatası (HTTP {$status}): Hepsiburada servisinde geçici sorun";
            default:
                return "❌ HTTP {$status}: " . substr($body, 0, 200);
        }
    }

    public function createOrUpdateProduct(Magaza $magaza, array $payload): array
    {
        $cred = $magaza->getApiCredentials();
        if (empty($magaza->magaza_id)) {
            return [
                'ok' => false,
                'status' => 400,
                'data' => null,
                'error' => 'Hepsiburada mağaza kimliği (magaza_id) tanımlı değil. Lütfen mağaza bilgilerini güncelleyin.'
            ];
        }
        if (empty($cred['api_key']) || empty($cred['api_secret'])) {
            return [
                'ok' => false,
                'status' => 400,
                'data' => null,
                'error' => 'Hepsiburada ürün gönderimi için API anahtarı ve gizli anahtar gereklidir.'
            ];
        }
        $url = $this->baseUrl($magaza) . '/products';
        $res = $this->http($magaza)->post($url, $payload);
        return [
            'ok' => $res->successful(),
            'status' => $res->status(),
            'data' => $res->json(),
            'error' => $res->successful() ? null : $res->body(),
        ];
    }

    public function updateInventory(Magaza $magaza, string $sku, int $quantity): array
    {
        $cred = $magaza->getApiCredentials();
        if (empty($magaza->magaza_id)) {
            return [
                'ok' => false,
                'status' => 400,
                'data' => null,
                'error' => 'Hepsiburada mağaza kimliği (magaza_id) tanımlı değil. Lütfen mağaza bilgilerini güncelleyin.'
            ];
        }
        if (empty($cred['api_key']) || empty($cred['api_secret'])) {
            return [
                'ok' => false,
                'status' => 400,
                'data' => null,
                'error' => 'Hepsiburada stok güncelleme için API anahtarı ve gizli anahtar gereklidir.'
            ];
        }
        $url = $this->baseUrl($magaza) . '/inventory';
        $res = $this->http($magaza)->put($url, [
            'items' => [
                [
                    'sku' => $sku,
                    'quantity' => $quantity,
                ]
            ]
        ]);
        return [
            'ok' => $res->successful(),
            'status' => $res->status(),
            'data' => $res->json(),
            'error' => $res->successful() ? null : $res->body(),
        ];
    }

    public function updatePrice(Magaza $magaza, string $sku, float $price): array
    {
        $cred = $magaza->getApiCredentials();
        if (empty($magaza->magaza_id)) {
            return [
                'ok' => false,
                'status' => 400,
                'data' => null,
                'error' => 'Hepsiburada mağaza kimliği (magaza_id) tanımlı değil. Lütfen mağaza bilgilerini güncelleyin.'
            ];
        }
        if (empty($cred['api_key']) || empty($cred['api_secret'])) {
            return [
                'ok' => false,
                'status' => 400,
                'data' => null,
                'error' => 'Hepsiburada fiyat güncelleme için API anahtarı ve gizli anahtar gereklidir.'
            ];
        }
        $url = $this->baseUrl($magaza) . '/prices';
        $res = $this->http($magaza)->put($url, [
            'items' => [
                [
                    'sku' => $sku,
                    'price' => $price,
                    'currency' => 'TRY',
                ]
            ]
        ]);
        return [
            'ok' => $res->successful(),
            'status' => $res->status(),
            'data' => $res->json(),
            'error' => $res->successful() ? null : $res->body(),
        ];
    }
}
