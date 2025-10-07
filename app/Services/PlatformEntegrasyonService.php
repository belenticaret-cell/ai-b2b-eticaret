<?php

namespace App\Services;

use App\Models\Magaza;
use App\Models\Urun;
use App\Models\Siparis;
use App\Models\SenkronLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PlatformEntegrasyonService
{
    public function __construct(private ?HepsiburadaClient $hbClient = null)
    {
        $this->hbClient = $hbClient ?? new HepsiburadaClient();
    }
    /**
     * Yönetimden çağrılan genel senkronizasyon orkestrasyonu
     * $islemTuru: urun|stok|fiyat
     */
    public function senkronize(Magaza $magaza, string $islemTuru = 'urun'): array
    {
        switch ($islemTuru) {
            case 'stok':
                $res = $this->stokSenkronize($magaza);
                return [
                    'success' => ($res['error_count'] ?? 0) === 0,
                    'message' => "Stok: ".($res['success_count'] ?? 0)." başarılı, ".($res['error_count'] ?? 0)." hatalı",
                    'data' => $res,
                ];
            case 'fiyat':
                $res = $this->fiyatSenkronize($magaza);
                return [
                    'success' => ($res['error_count'] ?? 0) === 0,
                    'message' => "Fiyat: ".($res['success_count'] ?? 0)." başarılı, ".($res['error_count'] ?? 0)." hatalı",
                    'data' => $res,
                ];
            case 'urun':
            default:
                // Mağazadaki eşlenmiş ürünleri senkronize edelim
                $urunIds = $magaza->urunler()->pluck('urun_id')->all();
                $res = $this->urunleriSenkronize($magaza, $urunIds);
                return [
                    'success' => ($res['error_count'] ?? 0) === 0,
                    'message' => "Ürün: ".($res['success_count'] ?? 0)." başarılı, ".($res['error_count'] ?? 0)." hatalı",
                    'data' => $res,
                ];
        }
    }

    /**
     * Backward-compat: Admin controller testConnection çağrısı için sarmalayıcı
     */
    public function testConnection(string $platform, array $credentials): array
    {
        return $this->testApiConnection($platform, $credentials);
    }

    /**
     * Platformdan mağazanın mevcut ürün kataloğunu çekip magaza_platform_urunleri tablosuna yazar
     */
    public function uzakKatalogCekVeKaydet(Magaza $magaza): array
    {
        $platform = strtolower($magaza->platform);
        switch ($platform) {
            case 'hepsiburada':
                return $this->hepsiburadaKatalogCek($magaza);
            case 'trendyol':
                return $this->trendyolKatalogCek($magaza);
            default:
                return [
                    'success' => false,
                    'message' => 'Uzak katalog çekme bu platform için desteklenmiyor.'
                ];
        }
    }
    /**
     * Ürünleri platforma senkronize et
     */
    public function urunleriSenkronize(Magaza $magaza, array $urunIds): array
    {
        $successCount = 0;
        $errorCount = 0;
        $details = [];

        foreach ($urunIds as $urunId) {
            try {
                $urun = Urun::find($urunId);
                if (!$urun) {
                    $errorCount++;
                    $details[] = "Ürün bulunamadı: {$urunId}";
                    continue;
                }

                $result = $this->platformUrunGonder($magaza, $urun);
                
                if ($result['success']) {
                    $successCount++;
                    // Pivot yoksa ekle, varsa güncelle
                    $magaza->urunler()->syncWithoutDetaching([
                        $urunId => [
                            'platform_urun_id' => $result['platform_id'] ?? null,
                            'platform_sku' => $result['platform_sku'] ?? null,
                            'senkron_durum' => 'tamamlandi'
                        ]
                    ]);
                } else {
                    $errorCount++;
                    $details[] = "Ürün senkron hatası ({$urun->ad}): " . $result['message'];
                    $magaza->urunler()->syncWithoutDetaching([
                        $urunId => [
                            'senkron_durum' => 'hata'
                        ]
                    ]);
                }

            } catch (\Exception $e) {
                $errorCount++;
                $details[] = "Exception: " . $e->getMessage();
                Log::error('Ürün senkron hatası', [
                    'magaza_id' => $magaza->id,
                    'urun_id' => $urunId,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Senkron log kaydet
        SenkronLog::create([
            'magaza_id' => $magaza->id,
            'tip' => 'urun_senkron',
            'durum' => $errorCount === 0 ? 'basarili' : 'kismi_basarili',
            'detay' => [
                'success_count' => $successCount,
                'error_count' => $errorCount,
                'details' => $details
            ]
        ]);

        return [
            'success_count' => $successCount,
            'error_count' => $errorCount,
            'details' => $details
        ];
    }

    /**
     * Stok senkronizasyonu
     */
    public function stokSenkronize(Magaza $magaza): array
    {
        $successCount = 0;
        $errorCount = 0;
        $details = [];

        $urunler = $magaza->urunler()->wherePivot('senkron_durum', 'tamamlandi')->get();

        foreach ($urunler as $urun) {
            try {
                $result = $this->platformStokGuncelle($magaza, $urun);
                
                if ($result['success']) {
                    $successCount++;
                } else {
                    $errorCount++;
                    $details[] = "Stok güncelleme hatası ({$urun->ad}): " . $result['message'];
                }

            } catch (\Exception $e) {
                $errorCount++;
                $details[] = "Exception: " . $e->getMessage();
                Log::error('Stok senkron hatası', [
                    'magaza_id' => $magaza->id,
                    'urun_id' => $urun->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Senkron log kaydet
        SenkronLog::create([
            'magaza_id' => $magaza->id,
            'tip' => 'stok_senkron',
            'durum' => $errorCount === 0 ? 'basarili' : 'kismi_basarili',
            'detay' => [
                'success_count' => $successCount,
                'error_count' => $errorCount,
                'details' => $details
            ]
        ]);

        return [
            'success_count' => $successCount,
            'error_count' => $errorCount,
            'details' => $details
        ];
    }

    /**
     * Fiyat senkronizasyonu
     */
    public function fiyatSenkronize(Magaza $magaza): array
    {
        $successCount = 0;
        $errorCount = 0;
        $details = [];

        $urunler = $magaza->urunler()->wherePivot('senkron_durum', 'tamamlandi')->get();

        foreach ($urunler as $urun) {
            try {
                $result = $this->platformFiyatGuncelle($magaza, $urun);
                
                if ($result['success']) {
                    $successCount++;
                } else {
                    $errorCount++;
                    $details[] = "Fiyat güncelleme hatası ({$urun->ad}): " . $result['message'];
                }

            } catch (\Exception $e) {
                $errorCount++;
                $details[] = "Exception: " . $e->getMessage();
                Log::error('Fiyat senkron hatası', [
                    'magaza_id' => $magaza->id,
                    'urun_id' => $urun->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Senkron log kaydet
        SenkronLog::create([
            'magaza_id' => $magaza->id,
            'tip' => 'fiyat_senkron',
            'durum' => $errorCount === 0 ? 'basarili' : 'kismi_basarili',
            'detay' => [
                'success_count' => $successCount,
                'error_count' => $errorCount,
                'details' => $details
            ]
        ]);

        return [
            'success_count' => $successCount,
            'error_count' => $errorCount,
            'details' => $details
        ];
    }

    /**
     * Platform API bağlantı testi
     */
    public function testApiConnection(string $platform, array $credentials): array
    {
        $key = strtolower($platform);
        switch ($key) {
            case 'trendyol':
                return $this->testTrendyolApi($credentials);
            case 'hepsiburada':
                return $this->testHepsiburadaApi($credentials);
            case 'n11':
                return $this->testN11Api($credentials);
            case 'amazon':
                return $this->testAmazonApi($credentials);
            default:
                return [
                    'success' => false,
                    'message' => 'Desteklenmeyen platform'
                ];
        }
    }

    /**
     * Platform'a ürün gönder
     */
    private function platformUrunGonder(Magaza $magaza, Urun $urun): array
    {
        $platform = strtolower($magaza->platform);
        switch ($platform) {
            case 'trendyol':
                return $this->trendyolUrunGonder($magaza, $urun);
            case 'hepsiburada':
                return $this->hepsiburadaUrunGonder($magaza, $urun);
            case 'n11':
                return $this->n11UrunGonder($magaza, $urun);
            case 'amazon':
                return $this->amazonUrunGonder($magaza, $urun);
            default:
                return [
                    'success' => false,
                    'message' => 'Desteklenmeyen platform'
                ];
        }
    }

    /**
     * Platform'da stok güncelle
     */
    private function platformStokGuncelle(Magaza $magaza, Urun $urun): array
    {
        $platform = strtolower($magaza->platform);
        switch ($platform) {
            case 'trendyol':
                return $this->trendyolStokGuncelle($magaza, $urun);
            case 'hepsiburada':
                return $this->hepsiburadaStokGuncelle($magaza, $urun);
            case 'n11':
                return $this->n11StokGuncelle($magaza, $urun);
            case 'amazon':
                return $this->amazonStokGuncelle($magaza, $urun);
            default:
                return [
                    'success' => false,
                    'message' => 'Desteklenmeyen platform'
                ];
        }
    }

    /**
     * Platform'da fiyat güncelle
     */
    private function platformFiyatGuncelle(Magaza $magaza, Urun $urun): array
    {
        $platform = strtolower($magaza->platform);
        switch ($platform) {
            case 'trendyol':
                return $this->trendyolFiyatGuncelle($magaza, $urun);
            case 'hepsiburada':
                return $this->hepsiburadaFiyatGuncelle($magaza, $urun);
            case 'n11':
                return $this->n11FiyatGuncelle($magaza, $urun);
            case 'amazon':
                return $this->amazonFiyatGuncelle($magaza, $urun);
            default:
                return [
                    'success' => false,
                    'message' => 'Desteklenmeyen platform'
                ];
        }
    }

    // ============ TRENDYOL ENTEGRASYONLARı ============
    /**
     * Trendyol User-Agent üretici
     * Format: "{supplierId} - {IntegratorName}" (IntegratorName: alfanumerik, max 30)
     */
    private function buildTrendyolUserAgent(string $supplierId): string
    {
        $name = (string) (config('eticaret.platformlar.trendyol.integrator_name') ?? 'SelfIntegration');
        // Sadece alfanumerik karakterlere indir ve 30 karaktere sınırla
        $name = preg_replace('/[^A-Za-z0-9]/', '', $name ?? '');
        if ($name === '') { $name = 'SelfIntegration'; }
        $name = substr($name, 0, 30);
        return trim($supplierId) . ' - ' . $name;
    }

    private function testTrendyolApi(array $credentials): array
    {
        try {
            $apiKey = $credentials['api_key'] ?? null;
            $apiSecret = $credentials['api_secret'] ?? null;
            $supplierId = $credentials['magaza_id'] ?? null; // Satıcı ID (Cari ID)
            if (!$apiKey || !$apiSecret || !$supplierId) {
                return [
                    'success' => false,
                    'message' => 'Trendyol API testi için api_key, api_secret ve magaza_id (supplierId) gereklidir.'
                ];
            }
            $isTest = (bool)($credentials['test_mode'] ?? false);
            $base = $isTest ? 'https://stageapi.trendyol.com/sapigw' : config('eticaret.platformlar.trendyol.base_url', 'https://api.trendyol.com/sapigw');
            $url = rtrim($base, '/') . '/suppliers/' . $supplierId . '/v2/products?page=0&size=1';

            $ua = $this->buildTrendyolUserAgent((string) $supplierId);
            $http = Http::withBasicAuth($apiKey, $apiSecret)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                    'User-Agent' => $ua,
                    'Accept-Language' => 'tr-TR,tr;q=0.9'
                ]);
            $options = ['allow_redirects' => false, 'timeout' => 30];
            $proxy = env('TR_PROXY') ?? env('HTTP_PROXY') ?? config('services.http_proxy');
            if ($proxy) { $options['proxy'] = $proxy; }
            $bindIp = env('TR_BIND_IP');
            if ($bindIp && defined('CURLOPT_INTERFACE')) {
                $options['curl'][CURLOPT_INTERFACE] = $bindIp;
            }
            $response = $http->withOptions($options)->get($url);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Trendyol API bağlantısı başarılı',
                    'data' => $response->json()
                ];
            } else {
                $body = $response->body();
                $blocked = stripos($body, 'cloudflare') !== false || stripos($body, 'cf-error-details') !== false;
                if ($response->status() === 401) {
                    $msg = 'Trendyol kimlik doğrulama hatası: 401 ClientApiAuthenticationException (API Key/Secret veya ortam yanlış)';
                } else {
                    $msg = $blocked
                        ? 'Trendyol 403/Cloudflare engeli: Sunucu IP adresiniz Trendyol tarafından engellenmiş olabilir. IP beyaz liste, doğru User-Agent ve Basic Auth kullanımı gerekli.'
                        : 'Trendyol API bağlantı hatası: HTTP ' . $response->status() . ' ' . $body;
                }
                return [
                    'success' => false,
                    'message' => $msg
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Trendyol API test hatası: ' . $e->getMessage()
            ];
        }
    }

    private function trendyolUrunGonder(Magaza $magaza, Urun $urun): array
    {
        try {
            $credentials = $magaza->getApiCredentials();
            $supplierId = $magaza->magaza_id;
            if (!$supplierId) {
                return ['success' => false, 'message' => 'Trendyol ürün gönderimi için mağaza_id (supplierId) gereklidir.'];
            }
            $isTest = (bool)($magaza->test_mode ?? false);
            $base = $isTest ? 'https://stageapi.trendyol.com/sapigw' : config('eticaret.platformlar.trendyol.base_url', 'https://api.trendyol.com/sapigw');
            $url = rtrim($base, '/') . '/suppliers/' . $supplierId . '/v2/products';
            $options = ['timeout' => 60, 'allow_redirects' => false];
            $proxy = env('TR_PROXY') ?? env('HTTP_PROXY') ?? config('services.http_proxy');
            if ($proxy) { $options['proxy'] = $proxy; }
            if (defined('CURLOPT_SSLVERSION') && defined('CURL_SSLVERSION_TLSv1_2')) {
                $options['curl'][CURLOPT_SSLVERSION] = CURL_SSLVERSION_TLSv1_2;
            }
            
            $urunData = [
                'barcode' => $urun->barkod,
                'title' => $urun->ad,
                'productMainId' => $urun->sku,
                'brandId' => $urun->marka_id ?? 1, // Default brand
                'categoryId' => $urun->kategori_id ?? 1, // Default category
                'quantity' => $urun->stok,
                'stockCode' => $urun->sku,
                'dimensionalWeight' => $urun->agirlik ?? 0,
                'description' => $urun->aciklama ?? '',
                'currencyType' => 'TL',
                'listPrice' => $urun->fiyat,
                'salePrice' => $urun->fiyat,
                'vatRate' => 18,
                'cargoProfileId' => 1,
                'deliveryDuration' => [
                    'deliveryDuration' => 3
                ],
                'images' => [
                    ['url' => $urun->getAnaResim()]
                ],
                'attributes' => []
            ];

            $ua = $this->buildTrendyolUserAgent((string) $supplierId);
            $response = Http::withBasicAuth($credentials['api_key'] ?? '', $credentials['api_secret'] ?? '')
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'User-Agent' => $ua,
                    'Accept-Language' => 'tr-TR,tr;q=0.9'
                ])->withOptions($options)->post($url, [
                'items' => [$urunData]
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                return [
                    'success' => true,
                    'platform_id' => $responseData['batchRequestId'] ?? null,
                    'platform_sku' => $urun->sku
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Trendyol ürün gönderme hatası: ' . $response->body()
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Trendyol ürün gönderme exception: ' . $e->getMessage()
            ];
        }
    }

    private function trendyolStokGuncelle(Magaza $magaza, Urun $urun): array
    {
        try {
            $credentials = $magaza->getApiCredentials();
            $supplierId = $magaza->magaza_id;
            if (!$supplierId) {
                return ['success' => false, 'message' => 'Trendyol stok güncelleme için mağaza_id (supplierId) gereklidir.'];
            }
            $platformSku = $urun->pivot->platform_sku ?? $urun->sku;
            $isTest = (bool)($magaza->test_mode ?? false);
            $base = $isTest ? 'https://stageapi.trendyol.com/sapigw' : config('eticaret.platformlar.trendyol.base_url', 'https://api.trendyol.com/sapigw');
            $url = rtrim($base, '/') . '/suppliers/' . $supplierId . '/v2/products/price-and-inventory';
            $options = ['timeout' => 60, 'allow_redirects' => false];
            $proxy = env('TR_PROXY') ?? env('HTTP_PROXY') ?? config('services.http_proxy');
            if ($proxy) { $options['proxy'] = $proxy; }
            if (defined('CURLOPT_SSLVERSION') && defined('CURL_SSLVERSION_TLSv1_2')) {
                $options['curl'][CURLOPT_SSLVERSION] = CURL_SSLVERSION_TLSv1_2;
            }

            $ua = $this->buildTrendyolUserAgent((string) $supplierId);
            $response = Http::withBasicAuth($credentials['api_key'] ?? '', $credentials['api_secret'] ?? '')
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'User-Agent' => $ua,
                    'Accept-Language' => 'tr-TR,tr;q=0.9'
                ])->withOptions($options)->put($url, [
                'items' => [
                    [
                        'barcode' => $urun->barkod,
                        'quantity' => $urun->stok
                    ]
                ]
            ]);

            return [
                'success' => $response->successful(),
                'message' => $response->successful() ? 'Başarılı' : 'Hata: ' . $response->body()
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Trendyol stok güncelleme exception: ' . $e->getMessage()
            ];
        }
    }

    private function trendyolFiyatGuncelle(Magaza $magaza, Urun $urun): array
    {
        try {
            $credentials = $magaza->getApiCredentials();
            $supplierId = $magaza->magaza_id;
            if (!$supplierId) {
                return ['success' => false, 'message' => 'Trendyol fiyat güncelleme için mağaza_id (supplierId) gereklidir.'];
            }
            
            // Trendyol komisyon oranını hesapla
            $komisyonOrani = 0.15; // %15 varsayılan
            $trendyolFiyat = $urun->fiyat / (1 - $komisyonOrani);
            $isTest = (bool)($magaza->test_mode ?? false);
            $base = $isTest ? 'https://stageapi.trendyol.com/sapigw' : config('eticaret.platformlar.trendyol.base_url', 'https://api.trendyol.com/sapigw');
            $url = rtrim($base, '/') . '/suppliers/' . $supplierId . '/v2/products/price-and-inventory';
            $options = ['timeout' => 60, 'allow_redirects' => false];
            $proxy = env('TR_PROXY') ?? env('HTTP_PROXY') ?? config('services.http_proxy');
            if ($proxy) { $options['proxy'] = $proxy; }
            if (defined('CURLOPT_SSLVERSION') && defined('CURL_SSLVERSION_TLSv1_2')) {
                $options['curl'][CURLOPT_SSLVERSION] = CURL_SSLVERSION_TLSv1_2;
            }

            $ua = $this->buildTrendyolUserAgent((string) $supplierId);
            $response = Http::withBasicAuth($credentials['api_key'] ?? '', $credentials['api_secret'] ?? '')
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'User-Agent' => $ua,
                    'Accept-Language' => 'tr-TR,tr;q=0.9'
                ])->withOptions($options)->put($url, [
                'items' => [
                    [
                        'barcode' => $urun->barkod,
                        'listPrice' => round($trendyolFiyat, 2),
                        'salePrice' => round($trendyolFiyat, 2)
                    ]
                ]
            ]);

            return [
                'success' => $response->successful(),
                'message' => $response->successful() ? 'Başarılı' : 'Hata: ' . $response->body()
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Trendyol fiyat güncelleme exception: ' . $e->getMessage()
            ];
        }
    }

    // ============ HEPSİBURADA ENTEGRASYONLARı ============

    private function testHepsiburadaApi(array $credentials): array
    {
        // Hepsiburada API test implementasyonu
        return [
            'success' => true,
            'message' => 'Hepsiburada API test - Mock implementasyon'
        ];
    }

    private function hepsiburadaUrunGonder(Magaza $magaza, Urun $urun): array
    {
        // Basit ürün payload örneği (gerçek şema platform dokümantasyonuna göre genişletilmeli)
        $payload = [
            'items' => [[
                'sku' => strtoupper($urun->sku),
                'merchant' => (string) ($magaza->magaza_id ?? ''),
                'title' => $urun->ad,
                'barcode' => $urun->barkod,
                'brand' => (string) ($urun->marka->ad ?? 'GENERIC'),
                'categoryId' => (int) ($urun->kategori_id ?? 0),
                'description' => (string) ($urun->aciklama ?? ''),
                'images' => array_filter([$urun->gorsel ?? null]),
                'attributes' => [],
                'quantity' => (int) ($urun->stok ?? 0),
                'price' => (float) ($urun->fiyat ?? 0),
                'currency' => 'TRY',
            ]]
        ];
        $res = $this->hbClient->createOrUpdateProduct($magaza, $payload);
        if ($res['ok']) {
            $tracking = $res['data']['trackingId'] ?? null;
            return [
                'success' => true,
                'platform_id' => strtoupper($urun->sku),
                'platform_sku' => strtoupper($urun->sku),
                'tracking_id' => $tracking,
            ];
        }
        return ['success' => false, 'message' => $res['error'] ?? 'Hepsiburada ürün gönderim hatası'];
    }

    private function hepsiburadaStokGuncelle(Magaza $magaza, Urun $urun): array
    {
        $sku = strtoupper($urun->pivot->platform_sku ?? $urun->sku);
        $res = $this->hbClient->updateInventory($magaza, $sku, (int) ($urun->stok ?? 0));
        return ['success' => $res['ok'], 'message' => $res['ok'] ? 'Başarılı' : ($res['error'] ?? 'Hata')];
    }

    private function hepsiburadaFiyatGuncelle(Magaza $magaza, Urun $urun): array
    {
        $sku = strtoupper($urun->pivot->platform_sku ?? $urun->sku);
        $res = $this->hbClient->updatePrice($magaza, $sku, (float) ($urun->fiyat ?? 0));
        return ['success' => $res['ok'], 'message' => $res['ok'] ? 'Başarılı' : ($res['error'] ?? 'Hata')];
    }

    private function hepsiburadaKatalogCek(Magaza $magaza): array
    {
        $page = 0; $size = 100; $totalUpsert = 0; $errors = 0; $details = [];
        do {
            $res = $this->hbClient->listProducts($magaza, $page, $size);
            if (!$res['ok']) {
                $errors++;
                $details[] = $res['error'] ?? 'Bilinmeyen hata';
                break;
            }
            $items = (array) ($res['data']['items'] ?? $res['data'] ?? []);
            if (empty($items)) break;

            foreach ($items as $it) {
                try {
                    $sku = $it['sku'] ?? ($it['stockCode'] ?? null);
                    if (!$sku) continue;
                    \App\Models\MagazaPlatformUrunu::updateOrCreate(
                        [
                            'magaza_id' => $magaza->id,
                            'platform_sku' => $sku,
                        ],
                        [
                            'platform' => 'hepsiburada',
                            'platform_urun_id' => (string) ($it['id'] ?? $sku),
                            'baslik' => (string) ($it['title'] ?? ''),
                            'fiyat' => (float) ($it['price'] ?? 0),
                            'stok' => (int) ($it['quantity'] ?? 0),
                            'ham_veri' => $it,
                            'son_senkron' => now(),
                        ]
                    );
                    $totalUpsert++;
                } catch (\Exception $e) {
                    $errors++;
                    $details[] = $e->getMessage();
                }
            }
            $page++;
            // Basit limit: ilk 5 sayfa (gerektiğinde parametreleştir)
        } while ($page < 5);

        return [
            'success' => $errors === 0,
            'message' => "Hepsiburada katalog çekildi: {$totalUpsert} kayıt upsert, {$errors} hata",
            'data' => ['upserted' => $totalUpsert, 'errors' => $errors, 'details' => $details]
        ];
    }

    private function trendyolKatalogCek(Magaza $magaza): array
    {
        $supplierId = $magaza->magaza_id;
        if (!$supplierId) {
            return ['success' => false, 'message' => 'Trendyol katalog için mağaza_id (supplierId) gerekli.'];
        }
        $cred = $magaza->getApiCredentials();
        $apiKey = $cred['api_key'] ?? null;
        $apiSecret = $cred['api_secret'] ?? null;
        if (!$apiKey || !$apiSecret) {
            return ['success' => false, 'message' => 'Trendyol katalog için API Anahtarı ve Gizli Anahtar gerekli.'];
        }

        // Debug bilgileri logla
        Log::info('Trendyol katalog çekme başlatıldı', [
            'magaza_id' => $magaza->id,
            'supplier_id' => $supplierId,
            'test_mode' => $magaza->test_mode ?? false,
        ]);

        $page = 0; $size = 20; $totalUpsert = 0; $errors = 0; $details = [];
        $maxPages = 3; // Başlangıçta sadece 3 sayfa ile sınırla
        
        do {
            try {
                $isTest = (bool)($magaza->test_mode ?? false);
                $base = $isTest ? 'https://stageapi.trendyol.com/sapigw' : config('eticaret.platformlar.trendyol.base_url', 'https://api.trendyol.com/sapigw');
                $url = rtrim($base, '/') . '/suppliers/' . $supplierId . '/v2/products?page=' . $page . '&size=' . $size;
                
                // User-Agent oluştur
                $ua = $this->buildTrendyolUserAgent((string) $supplierId);
                
                Log::info('Trendyol API isteği', [
                    'url' => $url,
                    'user_agent' => $ua,
                    'page' => $page,
                    'size' => $size,
                    'test_mode' => $isTest
                ]);
                
                $http = Http::withBasicAuth($apiKey, $apiSecret)
                    ->withHeaders([
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'User-Agent' => $ua,
                        'Accept-Language' => 'tr-TR,tr;q=0.9',
                        'Cache-Control' => 'no-cache'
                    ]);
                    
                $options = [
                    'allow_redirects' => false, 
                    'timeout' => 30, // Timeout'u azalttık
                    'connect_timeout' => 10
                ];
                
                // Proxy ayarları
                $proxy = env('TR_PROXY') ?? env('HTTP_PROXY') ?? config('services.http_proxy');
                if ($proxy) { 
                    $options['proxy'] = $proxy; 
                    Log::info('Proxy kullanılıyor', ['proxy' => $proxy]);
                }
                
                // Bind IP ayarları
                $bindIp = env('TR_BIND_IP');
                if ($bindIp && defined('CURLOPT_INTERFACE')) {
                    $options['curl'][CURLOPT_INTERFACE] = $bindIp;
                    Log::info('Bind IP kullanılıyor', ['bind_ip' => $bindIp]);
                }
                
                // TLS ayarları
                if (defined('CURLOPT_SSLVERSION') && defined('CURL_SSLVERSION_TLSv1_2')) {
                    $options['curl'][CURLOPT_SSLVERSION] = CURL_SSLVERSION_TLSv1_2;
                }
                
                $res = null; $attempt = 0; $maxAttempts = 5; // Daha fazla deneme
                
                do {
                    // İstek öncesi küçük gecikme (rate limit için)
                    if ($attempt > 0) {
                        usleep(200000); // 200ms
                    }
                    
                    $res = $http->withOptions($options)->get($url);
                    
                    if ($res->successful()) {
                        Log::info('Trendyol API başarılı', [
                            'status' => $res->status(),
                            'attempt' => $attempt + 1
                        ]);
                        break;
                    }
                    
                    $status = $res->status();
                    $body = $res->body();
                    
                    Log::warning('Trendyol API hatası', [
                        'status' => $status,
                        'attempt' => $attempt + 1,
                        'body_preview' => substr($body, 0, 200),
                        'headers' => $res->headers()
                    ]);
                    
                    // Transient hata kontrolü
                    $transient = in_array($status, [429, 502, 503, 504, 556]) || 
                                stripos($body, 'Service Unavailable') !== false ||
                                stripos($body, 'temporarily unavailable') !== false;
                    
                    if (!$transient) {
                        Log::error('Transient olmayan hata, tekrar denenmeyecek', ['status' => $status]);
                        break;
                    }
                    
                    // Retry-After desteği
                    $retryAfter = $res->header('Retry-After');
                    if ($retryAfter) {
                        $sleepSec = is_numeric($retryAfter) ? min(30, (int)$retryAfter) : 2;
                        Log::info('Retry-After beklemesi', ['sleep_seconds' => $sleepSec]);
                        sleep($sleepSec);
                    } else {
                        // Exponential backoff with jitter
                        $backoffSec = min(8, pow(2, $attempt)) + (rand(0, 1000) / 1000);
                        Log::info('Backoff beklemesi', ['sleep_seconds' => $backoffSec]);
                        usleep($backoffSec * 1000000);
                    }
                    
                    // Rate limit durumunda sayfa boyutunu küçült
                    if ($status == 429 && $size > 5) {
                        $size = max(5, intval($size / 2));
                        Log::info('Rate limit nedeniyle sayfa boyutu küçültüldü', ['new_size' => $size]);
                    }
                    
                    $attempt++;
                } while ($attempt < $maxAttempts);

                if (!$res->successful()) {
                    $errors++;
                    $status = $res->status();
                    $body = $res->body();
                    $headers = $res->headers();
                    
                    // Correlation ID'leri topla
                    $corr = $headers['X-Correlation-Id'][0] ?? 
                           $headers['X-Request-Id'][0] ?? 
                           $headers['x-correlation-id'][0] ?? 
                           $headers['x-request-id'][0] ?? null;
                    
                    // Hata tipini belirle
                    $blocked = stripos($body, 'cloudflare') !== false || 
                              stripos($body, 'cf-error-details') !== false ||
                              stripos($body, 'cf-ray') !== false;
                    
                    $unauthorizedMsgs = ['ClientApiAuthenticationException', 'Unauthorized', 'Invalid credentials'];
                    $isAuthError = $status == 401 || 
                                  collect($unauthorizedMsgs)->some(fn($msg) => stripos($body, $msg) !== false);
                    
                    // Detaylı hata mesajı oluştur
                    if ($blocked) {
                        $details[] = "🚫 CLOUDFLARE ENGELİ (HTTP {$status}): IP adresiniz Trendyol tarafından engellenmiş. Çözüm: IP whitelist, güvenilir proxy kullanın." . ($corr ? " | ID: {$corr}" : "");
                    } elseif ($isAuthError) {
                        $details[] = "🔐 KİMLİK DOĞRULAMA HATASI (HTTP {$status}): API Key/Secret hatalı veya yanlış ortam (stage/prod). Test modunu kontrol edin." . ($corr ? " | ID: {$corr}" : "");
                    } elseif ($status == 429) {
                        $rateLimitReset = $headers['X-RateLimit-Reset'][0] ?? $headers['x-ratelimit-reset'][0] ?? 'bilinmiyor';
                        $details[] = "⏱️ RATE LİMİT AŞILDI (HTTP 429): Çok fazla istek. Reset zamanı: {$rateLimitReset}" . ($corr ? " | ID: {$corr}" : "");
                    } elseif (in_array($status, [502, 503, 504, 556])) {
                        $details[] = "🔄 GEÇİCİ SERVİS HATASI (HTTP {$status}): Trendyol servisinde geçici sorun. Tekrar denenecek." . ($corr ? " | ID: {$corr}" : "");
                    } else {
                        $bodyPreview = strlen($body) > 300 ? substr($body, 0, 300) . '...' : $body;
                        $details[] = "❌ BEKLENMEYEN HATA (HTTP {$status}): {$bodyPreview}" . ($corr ? " | ID: {$corr}" : "");
                    }
                    
                    Log::error('Trendyol katalog çekme hatası', [
                        'status' => $status,
                        'body' => $body,
                        'correlation_id' => $corr,
                        'magaza_id' => $magaza->id,
                        'supplier_id' => $supplierId,
                        'page' => $page,
                        'size' => $size
                    ]);
                    
                    break;
                }

                $body = $res->json();
                $items = (array)($body['content'] ?? $body['items'] ?? $body['data'] ?? []);
                
                Log::info('Trendyol sayfa işlendi', [
                    'page' => $page,
                    'items_count' => count($items),
                    'total_upserted' => $totalUpsert
                ]);
                
                if (empty($items)) {
                    Log::info('Boş sayfa, işlem tamamlandı', ['page' => $page]);
                    break;
                }

                foreach ($items as $it) {
                    try {
                        $sku = $it['stockCode'] ?? $it['barcode'] ?? $it['productMainId'] ?? null;
                        if (!$sku) {
                            Log::debug('SKU bulunamadı, ürün atlandı', ['item' => $it]);
                            continue;
                        }
                        \App\Models\MagazaPlatformUrunu::updateOrCreate(
                            [
                                'magaza_id' => $magaza->id,
                                'platform_sku' => (string)$sku,
                            ],
                            [
                                'platform' => 'trendyol',
                                'platform_urun_id' => (string)($it['productMainId'] ?? $it['barcode'] ?? $sku),
                                'baslik' => (string)($it['title'] ?? $it['name'] ?? ''),
                                'fiyat' => (float)($it['salePrice'] ?? $it['listPrice'] ?? $it['price'] ?? 0),
                                'stok' => (int)($it['quantity'] ?? $it['stock'] ?? 0),
                                'ham_veri' => $it,
                                'son_senkron' => now(),
                            ]
                        );
                        $totalUpsert++;
                    } catch (\Exception $e) {
                        $errors++;
                        $details[] = 'Ürün kaydetme hatası: ' . $e->getMessage();
                        Log::error('Ürün upsert hatası', [
                            'error' => $e->getMessage(),
                            'item' => $it ?? 'null'
                        ]);
                    }
                }
            } catch (\Exception $e) {
                $errors++;
                $details[] = 'Sayfa işleme hatası: ' . $e->getMessage();
                Log::error('Sayfa işleme hatası', [
                    'error' => $e->getMessage(),
                    'page' => $page,
                    'magaza_id' => $magaza->id
                ]);
                break;
            }
            $page++;
        } while ($page < $maxPages && $errors == 0); // Hata varsa dur

        return [
            'success' => $errors === 0,
            'message' => "Trendyol katalog çekildi: {$totalUpsert} kayıt upsert, {$errors} hata",
            'data' => ['upserted' => $totalUpsert, 'errors' => $errors, 'details' => $details]
        ];
    }

    // ============ N11 ENTEGRASYONLARı ============

    private function testN11Api(array $credentials): array
    {
        // N11 API test implementasyonu
        return [
            'success' => true,
            'message' => 'N11 API test - Mock implementasyon'
        ];
    }

    private function n11UrunGonder(Magaza $magaza, Urun $urun): array
    {
        // N11 ürün gönderme implementasyonu
        return [
            'success' => true,
            'platform_id' => 'N11_' . $urun->id,
            'platform_sku' => $urun->sku
        ];
    }

    private function n11StokGuncelle(Magaza $magaza, Urun $urun): array
    {
        return ['success' => true, 'message' => 'Mock başarılı'];
    }

    private function n11FiyatGuncelle(Magaza $magaza, Urun $urun): array
    {
        return ['success' => true, 'message' => 'Mock başarılı'];
    }

    // ============ AMAZON ENTEGRASYONLARı ============

    private function testAmazonApi(array $credentials): array
    {
        return [
            'success' => true,
            'message' => 'Amazon API test - Mock implementasyon'
        ];
    }

    private function amazonUrunGonder(Magaza $magaza, Urun $urun): array
    {
        return [
            'success' => true,
            'platform_id' => 'AMZ_' . $urun->id,
            'platform_sku' => $urun->sku
        ];
    }

    private function amazonStokGuncelle(Magaza $magaza, Urun $urun): array
    {
        return ['success' => true, 'message' => 'Mock başarılı'];
    }

    private function amazonFiyatGuncelle(Magaza $magaza, Urun $urun): array
    {
        return ['success' => true, 'message' => 'Mock başarılı'];
    }

    // ============ WEBHOOK İŞLEMLERİ ============

    public function trendyolSiparisIsle(array $data): void
    {
        // Trendyol sipariş webhook işleme
        Log::info('Trendyol sipariş webhook alındı', $data);
    }

    public function trendyolSiparisIptal(array $data): void
    {
        // Trendyol sipariş iptal webhook işleme
        Log::info('Trendyol sipariş iptal webhook alındı', $data);
    }

    public function hepsiburadaSiparisIsle(array $data): void
    {
        Log::info('Hepsiburada sipariş webhook alındı', $data);
    }

    public function n11WebhookIsle(array $data): void
    {
        Log::info('N11 webhook alındı', $data);
    }

    public function amazonWebhookIsle(array $data): void
    {
        Log::info('Amazon webhook alındı', $data);
    }
}