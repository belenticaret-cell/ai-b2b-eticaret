<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Services\LocalDevelopmentService;

class RealApiConfigService
{
    private array $apiConfigs;
    private LocalDevelopmentService $localDevService;
    
    public function __construct(LocalDevelopmentService $localDevService)
    {
        $this->localDevService = $localDevService;
        $this->apiConfigs = [
            'trendyol' => [
                'base_url' => 'https://api.trendyol.com',
                'sandbox_url' => 'https://api.trendyol.com/sapigw',
                'endpoints' => [
                    'products' => '/sapigw/suppliers/{supplierId}/products',
                    'orders' => '/sapigw/suppliers/{supplierId}/orders',
                    'stock' => '/sapigw/suppliers/{supplierId}/products/price-and-inventory',
                ],
                'headers' => [
                    'User-Agent' => '{supplierId} - {integrator}',
                    'Content-Type' => 'application/json',
                ],
                'auth_type' => 'basic', // Basic Auth with API Key:Secret
                'rate_limits' => [
                    'products' => '100/minute',
                    'orders' => '200/minute',
                    'stock' => '500/minute',
                ]
            ],
            'hepsiburada' => [
                'base_url' => 'https://listing-external.hepsiburada.com',
                'sandbox_url' => 'https://listing-external.hepsiburada.com',
                'endpoints' => [
                    'products' => '/listings/{merchantId}/products',
                    'stock' => '/listings/{merchantId}/inventory',
                ],
                'auth_type' => 'basic',
                'rate_limits' => [
                    'products' => '50/minute',
                ]
            ],
            'n11' => [
                'base_url' => 'https://api.n11.com',
                'sandbox_url' => 'https://api.n11.com', 
                'endpoints' => [
                    'products' => '/ws/ProductService.wsdl',
                ],
                'auth_type' => 'signature',
            ]
        ];
    }
    
    public function getApiConfig(string $platform): array
    {
        return $this->apiConfigs[strtolower($platform)] ?? [];
    }
    
    public function isRealApiMode(): bool
    {
        return !env('MOCK_API_MODE', true);
    }
    
    public function validateCredentials(string $platform, array $credentials): array
    {
        if (!$this->isRealApiMode()) {
            return [
                'valid' => true,
                'message' => '🎭 Mock mode aktif - credentials geçerli sayılıyor',
                'mock' => true
            ];
        }
        
        $config = $this->getApiConfig($platform);
        if (empty($config)) {
            return [
                'valid' => false,
                'message' => "❌ Platform '$platform' desteklenmiyor",
                'errors' => ['platform' => 'Unsupported platform']
            ];
        }
        
        switch (strtolower($platform)) {
            case 'trendyol':
                return $this->validateTrendyolCredentials($credentials);
            case 'hepsiburada':
                return $this->validateHepsiburadaCredentials($credentials);
            case 'n11':
                return $this->validateN11Credentials($credentials);
            default:
                return ['valid' => false, 'message' => 'Platform not implemented'];
        }
    }
    
    private function validateTrendyolCredentials(array $credentials): array
    {
        $required = ['api_key', 'api_secret', 'supplier_id'];
        $missing = [];
        
        foreach ($required as $field) {
            if (empty($credentials[$field])) {
                $missing[] = $field;
            }
        }
        
        if (!empty($missing)) {
            return [
                'valid' => false,
                'message' => '❌ Eksik Trendyol credentials: ' . implode(', ', $missing),
                'errors' => $missing
            ];
        }
        
        // Local development kontrolü
        if ($this->localDevService->isLocalDevelopment()) {
            Log::info('Local development: Trendyol API test simülasyonu', [
                'supplier_id' => $credentials['supplier_id'],
                'local_info' => $this->localDevService->getLocalDevelopmentInfo()
            ]);
            
            return [
                'valid' => false, // Local'de her zaman false döndür ama açıklayıcı mesaj ver
                'message' => '🏠 LOCAL DEV: IP Engeli (Normal) - Gerçek sunucuda çalışacaktır. Local development ortamından Trendyol API\'sine erişim Cloudflare tarafından engellenmektedir.',
                'local_dev' => true,
                'error_type' => 'local_development_limitation',
                'solutions' => [
                    '✅ Mock mode kullanarak development yapın',
                    '🌐 VPN/Proxy ile test edin', 
                    '🚀 Production sunucuda deploy edin',
                    '📞 Trendyol ile IP whitelist görüşmesi yapın'
                ]
            ];
        }
        
        // Gerçek API test çağrısı
        try {
            $client = $this->localDevService->getHttpClientForPlatform('trendyol');
            
            $this->localDevService->logApiRequest(
                'trendyol', 
                'GET', 
                "https://api.trendyol.com/sapigw/suppliers/{$credentials['supplier_id']}/products"
            );
            
            $response = $client
                ->withBasicAuth($credentials['api_key'], $credentials['api_secret'])
                ->get("https://api.trendyol.com/sapigw/suppliers/{$credentials['supplier_id']}/products", [
                    'page' => 1,
                    'size' => 1
                ]);
            
            $this->localDevService->logApiResponse('trendyol', $response->status());
            
            if ($response->successful()) {
                return [
                    'valid' => true,
                    'message' => '✅ Trendyol API credentials geçerli!',
                    'response_code' => $response->status()
                ];
            } else {
                return [
                    'valid' => false,
                    'message' => '❌ Trendyol API yanıtı: HTTP ' . $response->status(),
                    'response_code' => $response->status(),
                    'response_body' => $response->body()
                ];
            }
            
        } catch (\Exception $e) {
            Log::error('Trendyol API validation error', [
                'error' => $e->getMessage(),
                'credentials' => array_keys($credentials),
                'local_dev' => $this->localDevService->isLocalDevelopment()
            ]);
            
            // Local development'ta daha açıklayıcı mesaj
            if ($this->localDevService->isLocalDevelopment()) {
                return [
                    'valid' => false,
                    'message' => '🏠 LOCAL DEV BEKLENEN HATA: ' . $e->getMessage() . ' - Bu local development limitasyonudur.',
                    'exception' => $e->getMessage(),
                    'local_dev' => true,
                    'note' => 'Bu hata local development ortamında normaldir. Production sunucuda çalışacaktır.'
                ];
            }
            
            return [
                'valid' => false,
                'message' => '💥 Trendyol API bağlantı hatası: ' . $e->getMessage(),
                'exception' => $e->getMessage()
            ];
        }
    }
    
    private function validateHepsiburadaCredentials(array $credentials): array
    {
        $required = ['username', 'password', 'merchant_id'];
        $missing = [];
        
        foreach ($required as $field) {
            if (empty($credentials[$field])) {
                $missing[] = $field;
            }
        }
        
        if (!empty($missing)) {
            return [
                'valid' => false,
                'message' => '❌ Eksik Hepsiburada credentials: ' . implode(', ', $missing),
                'errors' => $missing
            ];
        }
        
        // Hepsiburada API test
        try {
            $response = Http::timeout(10)
                ->withBasicAuth($credentials['username'], $credentials['password'])
                ->get("https://listing-external.hepsiburada.com/listings/{$credentials['merchant_id']}/products", [
                    'limit' => 1
                ]);
            
            if ($response->successful()) {
                return [
                    'valid' => true,
                    'message' => '✅ Hepsiburada API credentials geçerli!',
                    'response_code' => $response->status()
                ];
            } else {
                return [
                    'valid' => false,
                    'message' => '❌ Hepsiburada API yanıtı: HTTP ' . $response->status(),
                    'response_code' => $response->status()
                ];
            }
            
        } catch (\Exception $e) {
            return [
                'valid' => false,
                'message' => '💥 Hepsiburada API bağlantı hatası: ' . $e->getMessage(),
                'exception' => $e->getMessage()
            ];
        }
    }
    
    private function validateN11Credentials(array $credentials): array
    {
        // N11 SOAP API validation placeholder
        return [
            'valid' => false,
            'message' => '🚧 N11 API validation henüz implement edilmedi',
            'todo' => 'SOAP client implementation needed'
        ];
    }
    
    public function getCredentialsFromEnv(string $platform): array
    {
        switch (strtolower($platform)) {
            case 'trendyol':
                return [
                    'api_key' => env('TRENDYOL_API_KEY'),
                    'api_secret' => env('TRENDYOL_API_SECRET'),
                    'supplier_id' => env('TRENDYOL_SUPPLIER_ID'),
                ];
            case 'hepsiburada':
                return [
                    'username' => env('HB_USERNAME'),
                    'password' => env('HB_PASSWORD'),
                    'merchant_id' => env('HB_MERCHANT_ID'),
                ];
            case 'n11':
                return [
                    'api_key' => env('N11_API_KEY'),
                    'secret_key' => env('N11_SECRET_KEY'),
                    'shop_id' => env('N11_SHOP_ID'),
                ];
            default:
                return [];
        }
    }
}