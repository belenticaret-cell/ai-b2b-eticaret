<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LocalDevelopmentService
{
    private array $config;
    
    public function __construct()
    {
        $this->config = [
            'local_mode' => env('APP_ENV') === 'local',
            'bypass_ip_check' => env('BYPASS_IP_CHECK', true),
            'proxy_server' => env('DEV_PROXY_SERVER'),
            'mock_responses' => env('MOCK_RESPONSES', true),
            'log_requests' => env('LOG_API_REQUESTS', true),
        ];
    }
    
    public function isLocalDevelopment(): bool
    {
        return $this->config['local_mode'];
    }
    
    public function shouldBypassIpCheck(): bool
    {
        return $this->config['bypass_ip_check'] && $this->isLocalDevelopment();
    }
    
    public function getHttpClientForPlatform(string $platform): \Illuminate\Http\Client\PendingRequest
    {
        $client = Http::timeout(30);
        
        if ($this->isLocalDevelopment()) {
            // Local development için özel ayarlar
            $client = $client->withOptions([
                'verify' => false, // SSL doğrulamayı atla
                'timeout' => 60,
                'connect_timeout' => 10,
            ]);
            
            // User-Agent'ı local development için ayarla
            $userAgent = $this->getLocalUserAgent($platform);
            $client = $client->withHeaders([
                'User-Agent' => $userAgent,
                'Accept' => 'application/json',
                'X-Development-Mode' => 'true',
            ]);
            
            // Proxy varsa kullan
            if (!empty($this->config['proxy_server'])) {
                $client = $client->withOptions([
                    'proxy' => $this->config['proxy_server']
                ]);
                
                Log::info('Local development: Proxy kullanılıyor', [
                    'proxy' => $this->config['proxy_server'],
                    'platform' => $platform
                ]);
            }
        }
        
        return $client;
    }
    
    private function getLocalUserAgent(string $platform): string
    {
        $appName = config('app.name', 'AI-B2B');
        $version = '1.0.0-dev';
        
        switch (strtolower($platform)) {
            case 'trendyol':
                $supplierId = env('TRENDYOL_SUPPLIER_ID', 'dev-supplier');
                $integrator = env('TRENDYOL_INTEGRATOR', 'SelfIntegration');
                return "{$supplierId} - {$integrator} ({$appName} v{$version})";
                
            case 'hepsiburada':
                return "{$appName} v{$version} - Development";
                
            case 'n11':
                return "{$appName} v{$version} - N11 Integration";
                
            default:
                return "{$appName} v{$version} - API Client";
        }
    }
    
    public function logApiRequest(string $platform, string $method, string $url, array $headers = [], $body = null): void
    {
        if (!$this->config['log_requests']) {
            return;
        }
        
        Log::channel('daily')->info('Local API Request', [
            'platform' => $platform,
            'method' => $method,
            'url' => $url,
            'headers' => $this->sanitizeHeaders($headers),
            'body_size' => is_string($body) ? strlen($body) : (is_array($body) ? count($body) : 0),
            'timestamp' => now()->toISOString(),
            'environment' => app()->environment(),
        ]);
    }
    
    public function logApiResponse(string $platform, int $statusCode, array $headers = [], $body = null): void
    {
        if (!$this->config['log_requests']) {
            return;
        }
        
        Log::channel('daily')->info('Local API Response', [
            'platform' => $platform,
            'status_code' => $statusCode,
            'headers' => $this->sanitizeHeaders($headers),
            'body_size' => is_string($body) ? strlen($body) : (is_array($body) ? count($body) : 0),
            'timestamp' => now()->toISOString(),
        ]);
    }
    
    private function sanitizeHeaders(array $headers): array
    {
        $sensitiveKeys = ['authorization', 'x-api-key', 'password', 'secret'];
        $sanitized = [];
        
        foreach ($headers as $key => $value) {
            if (in_array(strtolower($key), $sensitiveKeys)) {
                $sanitized[$key] = '[REDACTED]';
            } else {
                $sanitized[$key] = $value;
            }
        }
        
        return $sanitized;
    }
    
    public function getMockResponseForError(string $platform, string $errorType): array
    {
        $correlationId = 'local-mock-' . uniqid();
        
        $mockErrors = [
            'cloudflare_403' => [
                'success' => false,
                'message' => '🏠 LOCAL DEV: Cloudflare 403 simülasyonu - Normal bir durum, gerçek sunucuda çalışacaktır',
                'error_type' => 'cloudflare_block_simulation',
                'error_code' => 403,
                'correlation_id' => $correlationId,
                'local_dev_note' => 'Bu hata local development için simüle edilmiştir. Production ortamında IP whitelist ile çözülecektir.',
                'solutions' => [
                    'VPN/Proxy kullanın',
                    'Production sunucuda test edin',
                    'Platform ile IP whitelist görüşmesi yapın'
                ]
            ],
            'rate_limit_429' => [
                'success' => false,
                'message' => '🏠 LOCAL DEV: Rate limit simülasyonu',
                'error_type' => 'rate_limit_simulation',
                'error_code' => 429,
                'correlation_id' => $correlationId,
                'retry_after_seconds' => 60
            ],
            'service_unavailable_556' => [
                'success' => false,
                'message' => '🏠 LOCAL DEV: Service unavailable simülasyonu',
                'error_type' => 'service_unavailable_simulation',
                'error_code' => 556,
                'correlation_id' => $correlationId,
            ]
        ];
        
        return $mockErrors[$errorType] ?? $mockErrors['cloudflare_403'];
    }
    
    public function getLocalDevelopmentInfo(): array
    {
        return [
            'environment' => app()->environment(),
            'local_mode' => $this->isLocalDevelopment(),
            'ip_check_bypass' => $this->shouldBypassIpCheck(),
            'proxy_configured' => !empty($this->config['proxy_server']),
            'proxy_server' => $this->config['proxy_server'] ?? null,
            'mock_responses' => $this->config['mock_responses'],
            'api_logging' => $this->config['log_requests'],
            'recommendations' => [
                'Bu local development ortamıdır',
                'API hataları normal karşılanmalıdır',
                'Production test için canlı sunucu gereklidir',
                'VPN/Proxy kullanarak test edebilirsiniz'
            ]
        ];
    }
}