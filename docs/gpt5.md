# GPT5 Playbook (03–07 Ekim 2025)

Bu playbook, 3 Ekim’den 7 Ekim’e kadar bu projede yaptığımız çalışmaların konsolide özeti ve tekrar kullanılabilir entegrasyon rehberidir. Hedefimiz: benzer projelerde hızlı başlangıç, tutarlı mimari kararlar ve operasyonel dayanıklılık.

## 0) Kapsam ve Tarih Aralığı
- Kapsam: Admin/B2B modülleri, Hepsiburada ve Trendyol entegrasyonları, katalog ve senkron akışları, dayanıklılık ve gözlemlenebilirlik, küçük UI/UX dokunuşları.
- Tarih: 03.10.2025 – 07.10.2025

## 1) Yüksek Seviye Özet
- Admin ve B2B geliştirmeleri: guard’lar, audit, CRUD düzeltmeleri, smoke testler, sipariş modülü.
- Entegrasyon altyapısı: `PlatformEntegrasyonService` kuruldu; platform anahtarları normalize edildi; `MagazaPlatformUrunu` ile uzak katalog haritalama.
- Hepsiburada: `HepsiburadaClient` yazıldı; base URL/merchantId doğrulaması; liste/ürün/stok/fiyat uçları; özellik testleri; upsert’lı katalog çekimi.
- Trendyol: Gerçek katalog çekme ve upsert; Basic Auth, User-Agent, stage/prod ayrımı; 403 Cloudflare ve 556 Service Unavailable durumlarına karşı dayanıklılık; fiyat/stok endpoint düzeltmesi.
- Operasyon: Proxy/bind IP seçenekleri, TLS 1.2, adaptif retry/backoff, Retry-After desteği, correlation-id loglama; test_mode Boolean parsing fix.

## 2) Mimari Kararlar (ADR niteliğinde)
1. Entegrasyon orkestrasyonu servis katmanında: `App/Services/PlatformEntegrasyonService`.
2. Uzak-katalog haritalama modeli: `MagazaPlatformUrunu` (platform_sku ve platform_urun_id temelinde upsert).
3. Session-tabanlı sepet korunur; B2B/B2C ayrımı route/middleware bazında.
4. Platform spesifik config’ler `config/eticaret.php` ve `.env` üzerinden yönetilir.

## 3) Hepsiburada – Uygulama Notları
- Base URL ve merchantId URL içinde garanti edilir (listing-external/merchantid).
- Okuma isteklerinde Authorization opsiyonel; yazmada zorunlu.
- Uçlar: listProducts, createOrUpdateProduct, updateInventory, updatePrice.
- Katalog çekme: ilk 5 sayfa sınırlı; her öğede idempotent upsert (SKU kökenli); basit hata toplama.

## 4) Trendyol – Uygulama Notları
- Auth: Basic Auth (API Key/Secret). Stage ve Prod cred’leri farklı olabilir; `test_mode` ile URL seçimi: `https://stageapi.trendyol.com/sapigw` / `https://api.trendyol.com/sapigw`.
- Zorunlu User-Agent: "{supplierId} - {IntegratorName}". IntegratorName `.env` → `TRENDYOL_INTEGRATOR` (alfanümerik, max 30). UA eksikse 403.
- Katalog: `/suppliers/{supplierId}/v2/products?page={p}&size={s}`. İçerik `content`/`items` alanlarından okunur.
- Fiyat/Stok: `/suppliers/{supplierId}/v2/products/price-and-inventory` (PUT). Endpoint düzeltilmiştir.
- Dayanıklılık: 429/5xx/556 ve metinde "Service Unavailable" durumlarında adaptif retry/backoff (Retry-After destekli), sayfa boyutunu kademeli küçült (50→20→10), yönlendirmeleri kapat, TLS1.2 zorlama.
- WAF/Cloudflare: 403 HTML/CF pattern’lerinde açık mesaj; IP whitelist/proxy/bilinir çıkış IP önerilir.
- Diagnostik: Uygun başlıklardan Correlation-Id/Request-Id yakalanır ve hata detaylarına eklenir.

## 5) Güvenlik ve Konfig
- Sırlar `.env` üzerinden: API anahtarları, `TRENDYOL_INTEGRATOR`, `TR_PROXY`/`HTTP_PROXY`, `TR_BIND_IP`.
- Laravel 12 ile uyumlu middleware aliasing; strict boolean validasyonları yerine güvenilir casting (örn. `test_mode`).
- TLS 1.2 zorlama (cURL option) ve otomatik yönlendirme devre dışı.

## 6) Senkron ve Veri Eşleştirme
- SKU/barcode/productMainId eşlemesi; upsert idempotent strateji.
- Fiyat dönüşümü: Trendyol komisyon varsayımı (örnek %15) ile target fiyat hesaplama; projeye özgü kural seti tanımlı.
- Sayfalama/paging: adaptive size; ilk aşamada sınırlı sayfa çekimi (Hepsiburada: 5 sayfa; Trendyol: 50→20→10 adaptif).

## 7) Webhook ve Olaylar
- Trendyol/Hepsiburada/N11/Amazon webhook endpoint’leri route’larda mevcut (`routes/api.php`).
- İmza doğrulaması için placeholder; Trendyol için HMAC/benzeri yönergeler dokümandan teyit edilip eklenecek.

## 8) Hata Yönetimi ve Gözlemlenebilirlik
- Hata sınıfları: 401 (Auth), 403 (WAF/Cloudflare), 429 (Rate limit), 5xx/556 (geçici).
- Loglama: Hata detayları, correlation-id ve body’den kısmi özet; tekrar denemeler kayıt altına alınır.
- Health/Smoke: Basit health bot ve Admin System Health ekranı.

## 9) Test ve Kalite Kapıları
- Hepsiburada katalog flow için Feature testleri (SQLite tabanlı hızlı senaryolar).
- Laravel cache/route/view temizliği (`php artisan optimize:clear`) ve composer autoload optimizasyonu rutinleri.
- Paginator güvenli kullanımı ve Admin form validasyonu düzeltmeleri.

## 10) Operasyonel Runbook
1. Bağlantı Testi: Admin > Mağazalar > “Bağlantıyı Test Et”
   - 401: cred/ortam (stage/prod) doğrula.
   - 403: UA formatı ve çıkış IP/proxy/whitelist kontrol.
   - 429: bekle; Retry-After’a uy; talep hızını düşür.
   - 556/5xx: tekrar dene/backoff; correlation-id’yi logla.
2. Katalog Çek: Küçük sayfa boyutu ile dene (50→20→10). Hata detaylarında correlation-id varsa destekle paylaş.
3. Senkron: Ürün/Stok/Fiyat çağrılarında timeout’lar, redirect kapalı, TLS1.2 açık.
4. İyileştirme: Proxy/Bind IP, Retries arası jitter, hız sınırlayıcı ve queue concurrency ayarı.

## 11) Bilinen Konular ve Açık İşler
- Trendyol rate limit başlıkları ve 556 kodunun resmi anlamı için dokümantasyon teyidi.
- Kategori/atribüt/variant mapping genişletmesi.
- Webhook imza doğrulaması implementasyonu.
- Platform katalog ekranında eşleştir-gönder UX iyileştirmeleri.

## 12) Hızlı Başlangıç Checklistesi
- [ ] En az 1 okuma + 1 yazma endpoint URL ve örnek payload.
- [ ] Auth tipi ve zorunlu header’lar (User-Agent/imza).
- [ ] Rate limit ve Retry-After bilgisi.
- [ ] Sandbox/Stage/Prod URL + erişim (IP whitelist/proxy?).
- [ ] Veri eşleşmesi: SKU/barcode, fiyat/KDV, stok kuralları.
- [ ] Webhook imzası, retry/idempotency planı.
- [ ] Loglama ve correlation-id.
- [ ] Test senaryoları ve kabul kriterleri.

## 13) Ekler
- Ortam anahtarları:
  - TRENDYOL_INTEGRATOR, TR_PROXY/HTTP_PROXY, TR_BIND_IP, TRENDYOL_API_KEY/SECRET
- Başlıca endpoint referansı:
  - Trendyol: `/suppliers/{supplierId}/v2/products`, `/v2/products/price-and-inventory`
  - Hepsiburada: list/create/update inventory/price (client üzerinden)

---

Bu playbook, projedeki kalıpları yeni entegrasyonlara hızlı taşımak için hazır bir şablondur. Gerektikçe güncellenmelidir.