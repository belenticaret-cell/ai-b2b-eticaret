# AI ile Paylaşım Kontrol Listesi

Diğer bir yapay zekâ asistanına geçiş yapmadan önce, bağlamı güvenli ve yeterli şekilde aktarmak için aşağıdaki kontrol listesini kullanın.

## 1) Amaç ve Kapsam
- [ ] Kısa hedef (örn. Trendyol katalog çekme hatası 556 analizi)
- [ ] MVP sınırları / Beklenen çıktı

## 2) Teknik Bağlam
- [ ] İlgili dosya yolları ve sınıflar (örn. `app/Services/PlatformEntegrasyonService.php` → `trendyolKatalogCek`)
- [ ] Konfig anahtarları (örn. `TRENDYOL_INTEGRATOR`, `TR_PROXY`, `TR_BIND_IP`) – sırları MASKELİ paylaşın.
- [ ] Ortam (Stage/Prod), base URL’ler

## 3) Hata ve Loglar
- [ ] Son hata mesajı (kısa), status code (örn. 556/403/429)
- [ ] Correlation/Request-Id (varsa)
- [ ] Retry-After ve denenen sayfa boyutu (örn. 50→20→10)

## 4) Güvenlik ve Gizlilik
- [ ] API Key/Secret veya PII’yi paylaşmayın (●●●● maskeleyin)
- [ ] Yalnızca gerekli log parçasını aktarın (en az veri ilkesi)

## 5) Referans Dokümanlar
- [ ] `docs/gpt5.md` (toplu playbook)
- [ ] `docs/entegrasyon-playbook.md` (genel standartlar)
- [ ] `docs/SESSION_NOTES/` altındaki ilgili tarihli notlar

## 6) Beklentiler
- [ ] Çıkış formatı (kısa teşhis + net aksiyon maddeleri)
- [ ] Zaman/performans kısıtları (örn. 15 dakikalık analiz)

Bu listeyi takip ederek sohbet değiştirdiğinizde bile, kritik bağlam korunur ve tekrar eden açıklamalar azalır.
