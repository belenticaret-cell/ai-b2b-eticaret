# 07.10.2025 Oturum Notları – Trendyol & Hepsiburada

Bu oturumda Trendyol ve Hepsiburada entegrasyonları üzerine ilerledik. Ana hedef, uzak katalog çekme, ürün/stok/fiyat senkronizasyonu ve operasyonel dayanıklılık idi.

## Yapılanlar
- Trendyol
  - Basic Auth sabitlendi (tüm çağrılarda withBasicAuth).
  - User-Agent dokümana uygun hale getirildi: "{supplierId} - {IntegratorName}". IntegratorName `.env` → `TRENDYOL_INTEGRATOR`; default `SelfIntegration`.
  - Stage/Prod URL seçimi `test_mode` ile yapılıyor: `https://stageapi.trendyol.com/sapigw` / `https://api.trendyol.com/sapigw`.
  - Header’lar: `Accept`, `Content-Type`, `Accept-Language` eklendi.
  - Katalog çekmede adaptif retry/backoff: 429/5xx/556 ve “Service Unavailable” için denemeler, Retry-After okuma, sayfa boyutunu 50→20→10 düşürme.
  - Cloudflare 403 tespitine yönelik mesajlar (IP whitelist/proxy ve doğru UA yönergesi).
  - Hata mesajlarına correlation/request id eklemeye çalışıyoruz.

- Hepsiburada
  - `HepsiburadaClient` ile list/create/update inventory/price uçları stabilize edildi.
  - MerchantId URL içinde garanti ediliyor; okuma işlemlerinde optional Authorization destekli.
  - Katalog çekme: ilk 5 sayfa ile sınırlı basit akış ve upsert.

## Gözlemler
- Trendyol tarafında User-Agent zorunlu; UA yoksa 403. UA formatı: "supplierId - IntegratorName".
- 556 (Service Unavailable) ve 5xx durumları periyodik; backoff ve küçük page size ile daha stabil.
- IP kaynaklı WAF engeli olabiliyor; güvenilir çıkış IP/proxy/whitelist gerekebilir.
- `test_mode` form alanı validasyonu sıkı olursa (boolean), parse edilip cast edilmeli.

## Kararlar
- UA üretici yardımcı fonksiyon: `buildTrendyolUserAgent`.
- Trendyol çağrılarında `withBasicAuth` kullanımı genelleştirildi.
- Proxy (`TR_PROXY`/`HTTP_PROXY`) ve `TR_BIND_IP` opsiyonları korunuyor.
- Trendyol stok/fiyat endpoint’i: `/v2/products/price-and-inventory`.

## Açık Maddeler / Sonraki Adımlar
- Trendyol rate limit resmi başlıklar ve 556 anlamı için doküman teyidi.
- Kategori/atribüt/variant mapping genişletmesi.
- Webhook imza doğrulaması (Trendyol için HMAC benzeri gereksinimler araştırılacak).
- UI: Platform katalogdan seçip eşleştirme/aktarımı hızlandıran arayüz iyileştirmeleri.

---

Bu notlar, ileride benzer entegrasyonlar için referans ve tekrar kullanılan şablonlar (retry, UA, proxy) açısından rehber niteliğindedir.
