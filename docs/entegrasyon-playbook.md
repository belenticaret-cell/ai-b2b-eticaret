# Entegrasyon Playbook

Bu doküman, pazar yeri ve benzeri harici servis entegrasyonlarında izlenecek standart yaklaşımı özetler. Amacımız hızlı başlangıç, minimum sürpriz, maksimum dayanıklılık ve gözlemlenebilirlik.

## 1) Amaç ve kapsam
- Hedef entegrasyon türü: Marketplace, ödeme, kargo, muhasebe/ERP, CRM vb.
- Okuma/yazma yetenekleri: katalog çek, sipariş çek, stok/fiyat güncelle, ürün gönder.
- MVP mi tam kapsam mı, kabul kriterleri nedir?

## 2) Ortamlar ve erişim
- Sandbox/Stage/Prod URL’leri ve kimlik bilgileri.
- IP whitelist, proxy, sabit çıkış IP veya TR_BIND_IP ihtiyacı.
- Zaman eşleşmesi (timestamp/nonce), TLS sürümleri (TLS1.2+).

## 3) Kimlik doğrulama ve yetkilendirme
- Basic Auth, API Key/Secret, OAuth2 (client credentials/refresh), imzalı istekler (HMAC).
- Zorunlu header’lar: örn. Trendyol için User-Agent zorunlu.
- Yetki kapsamları (scope/rol) ve anahtar rotasyonu.

## 4) Rate limit ve kotlar
- Limit ve pencere (örn. aynı endpoint’e 10 sn’de 50 istek).
- Retry-After başlığı desteği, exponential backoff, jitter.
- Batch gönderimler ve sayfa boyutu optimizasyonu.

## 5) Endpoint envanteri ve sözleşme
- Okuma: list/get/search + sayfalama parametreleri.
- Yazma: create/update/bulk; idempotency anahtarı.
- Versiyonlama (v1/v2) ve geriye dönük uyumluluk.

## 6) Veri modelleme ve eşleştirme
- SKU/barcode/productMainId eşleşmesi.
- Para birimi, vergi/KDV, yuvarlama.
- Varyant/özellik/atribüt eşleştirmesi ve zorunlu alanlar.

## 7) İş kuralları ve dönüşümler
- Başlık/açıklama uzunluk limitleri, resim format/boyut sınırları.
- Kategori/marka haritaları ve eksik veri stratejisi.

## 8) Senkron stratejisi
- Tam vs. delta senkron; cron/queue planı.
- Sayfa boyutu (size) ve adaptif küçültme (50→20→10 gibi).
- Upsert ve idempotent tasarım.

## 9) Webhook/olay odaklı akış
- İmza doğrulama (HMAC/shared secret), replay koruması.
- Yeniden deneme, dead-letter queue, idempotency.
- Olay şemaları ve sürümleme.

## 10) Hata yönetimi ve dayanıklılık
- 4xx/5xx/WAF özel durumları (örn. Cloudflare 403, 556 Service Unavailable gibi vendor-özel kodlar).
- Geri dönüş stratejileri: backoff, devre kesici (circuit breaker), düşürülmüş kapasite.
- Diagnosti̇k: correlation/request-id’leri topla ve logla.

## 11) Gözlemlenebilirlik (Observability)
- Metrikler: başarı oranı, gecikme, retry sayısı, throughput.
- Yapılandırılmış loglar ve korelasyon kimliği.
- Alarm/uyarı eşikleri.

## 12) Güvenlik ve uyumluluk
- Sırlar: .env/Secret Manager/Vault, salt okunur erişim.
- PII/finansal veri: KVKK/GDPR, maskeleme ve minimizasyon.
- Audit log ve erişim kontrol listeleri.

## 13) Performans ve ölçek
- Kuyruklar, işçi sayısı, eşzamanlılık sınırları.
- Caching (ETag/If-Modified-Since/response cache).
- Büyük batch’lerde parça parça işlem ve akış kontrolü.

## 14) Test stratejisi
- Sandbox testleri, mock/stub, contract testleri.
- Fixture’lar ve deterministik veri setleri.
- E2E senaryolar ve kabul kriterleri.

## 15) DevOps ve dağıtım
- CI/CD, feature flag, mavi-yeşil veya kanarya dağıtımı.
- Rollback planı ve bakım pencereleri.
- Ortam değişkenleri ve konfig versiyonlama.

## 16) Dokümantasyon ve bakım
- Runbook/Playbook (kurulum, sorun giderme, sık hatalar).
- Changelog, versiyon notları.
- Destek kanalları ve escalation süreci.

## 17) SLA ve operasyon
- Yanıt süreleri, çözüm süreleri, ulaşılabilirlik.
- Tedarikçi destek süreçleri (correlation-id ile case açma).
- Olay sonrası kök neden analizi.

---

## Trendyol’a özel notlar (örnek)
- Basic Auth: supplierId’ye bağlı API Key/Secret (stage/prod farklı olabilir).
- User-Agent zorunlu: "{supplierId} - {IntegratorName}" (alfanümerik, max 30). UA’sız istekler 403 ile reddedilir.
- Rate limit: aynı endpoint’e 10 sn’de max 50 istek; 429’larda Retry-After’a uy.
- WAF/Cloudflare: 403/HTML dönebilir; güvenilir çıkış IP/proxy ve doğru UA gerekir.
- Dayanıklılık: transient (429/5xx/556) hatalarda backoff + sayfa boyutunu küçült.
- Örnek endpoint: ürün listeleme `/suppliers/{supplierId}/v2/products`, fiyat/stok `/v2/products/price-and-inventory`.

## Hızlı başlangıç checklistesi
- [ ] En az 1 okuma + 1 yazma endpoint URL ve örnek payload.
- [ ] Auth tipi ve zorunlu header’lar (User-Agent/imza).
- [ ] Rate limit ve Retry-After bilgisi.
- [ ] Sandbox/Stage/Prod URL + erişim (IP whitelist/proxy?).
- [ ] Veri eşleşmesi: SKU/barcode, fiyat/KDV, stok kuralları.
- [ ] Webhook imzası, retry/idempotency planı.
- [ ] Loglama ve correlation-id.
- [ ] Test senaryoları ve kabul kriterleri.
