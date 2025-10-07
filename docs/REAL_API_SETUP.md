# 🔑 Gerçek API Credentials Setup Rehberi

## Şu An: Mock Mode Aktif ✅
Sistem mock mode'da çalışıyor ve test verileri kullanıyor.

## Real API'ye Geçiş İçin:

### 1. .env Dosyasında Değişiklik:
```bash
# Bu satırı false yapın:
MOCK_API_MODE=false

# Gerçek credentials'ları ekleyin:
TRENDYOL_API_KEY=your_actual_api_key_here
TRENDYOL_API_SECRET=your_actual_api_secret_here  
TRENDYOL_SUPPLIER_ID=your_supplier_id_here

HB_USERNAME=your_hb_username
HB_PASSWORD=your_hb_password
HB_MERCHANT_ID=your_merchant_id
```

### 2. Trendyol Partner Setup:
1. https://partner.trendyol.com adresine gidin
2. Hesap oluşturun / giriş yapın
3. Entegrasyon > API Yönetimi bölümüne gidin
4. API Key & Secret alın
5. Supplier ID'nizi not edin
6. IP whitelist için destek açın

### 3. Test Süreci:
```bash
# Admin panel > API Test sayfasında:
1. Platform: Trendyol seçin
2. Credentials'ları girin  
3. "Test API Credentials" butonuna tıklayın
4. Sonucu gözlemleyin
```

### 4. Beklenen Sonuçlar:

#### ✅ Başarılı Durumda:
```
✅ Trendyol API: Trendyol API credentials geçerli!
```

#### ❌ Hatalı Durumda:
```
❌ Trendyol API: Cloudflare engeli veya eksik User-Agent (HTTP 403)
❌ Trendyol API: Geçersiz credentials (HTTP 401)  
❌ Trendyol API: Rate limit aşıldı (HTTP 429)
```

### 5. Troubleshooting:

#### 403 Cloudflare Hatası:
- IP adresinizi Trendyol'a whitelist ettirin
- VPN/Proxy kullanın
- User-Agent format'ını kontrol edin

#### 401 Authentication Hatası:  
- API Key & Secret'ı kontrol edin
- Supplier ID'yi doğrulayın
- Hesap aktif mi kontrol edin

#### 429 Rate Limit:
- API kullanım limitinizi kontrol edin
- Daha az sıklıkla istek atın
- Premium API paketi düşünün

### 6. Production Deployment:
```bash
# Production ortamında:
1. SSL sertifikası kurun
2. .env dosyasını güvenle saklayın
3. API credentials'ları şifreleyin
4. Log rotation setup yapın
5. Monitoring & alerting kurun
```

---

## 🎯 Sonraki Adımlar:

1. **Real API Test** - Gerçek credentials ile test edin
2. **Error Monitoring** - Production-level monitoring setup
3. **Performance Optimization** - Caching & optimization
4. **Security Hardening** - Security best practices
5. **Documentation** - API documentation tamamlayın

## 📞 Destek:

Herhangi bir sorunla karşılaştığınızda:
- Error logs'ları kontrol edin: `storage/logs/laravel.log`
- Correlation ID'leri not edin
- Platform destek ekipleriyle iletişime geçin