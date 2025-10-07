# 🔑 Gerçek API Entegrasyonu Rehberi

## Trendyol API Kurulumu

### Gerekli Bilgiler
1. **Supplier ID**: Trendyol'dan alacağınız tedarikçi kimliği
2. **API Key**: Entegrasyon API anahtarı  
3. **API Secret**: Gizli anahtar

### Test Prosedürü
```bash
# 1. .env dosyasında gerçek credentials'ları tanımlayın
TRENDYOL_INTEGRATOR=SelfIntegration
TR_SUPPLIER_ID=your_supplier_id
TR_API_KEY=your_api_key  
TR_API_SECRET=your_api_secret

# 2. Proxy/VPN setup (opsiyonel)
TR_PROXY=http://proxy-server:port
TR_BIND_IP=your_whitelist_ip
```

### IP Whitelist için Trendyol ile İletişim
- **Destek Portal**: https://partner.trendyol.com
- **Gerekli Bilgi**: Sabit IP adresiniz
- **Süreç**: 1-3 iş günü

## Hepsiburada API Kurulumu

### Gerekli Bilgiler
1. **Merchant ID**: Hepsiburada mağaza kimliği
2. **Username**: API kullanıcı adı
3. **Password**: API şifresi

### Test URL'leri
- **Test**: https://api.hepsiburada.com
- **Prod**: https://api.hepsiburada.com

## N11 API Kurulumu

### Gerekli Bilgiler  
1. **API Key**: N11 API anahtarı
2. **Secret Key**: Gizli anahtar
3. **Shop ID**: Mağaza kimliği

## Amazon API Kurulumu

### Gerekli Bilgiler
1. **Seller ID**: Amazon satıcı kimliği
2. **Access Key**: AWS erişim anahtarı
3. **Secret Key**: AWS gizli anahtarı
4. **Region**: Amazon marketplace bölgesi (TR için eu-west-1)

---

## 🧪 Test Süreci

1. **Mock Mode Testi** ✅ (Tamamlandı)
2. **Sandbox Testi** (Sıradaki)
3. **Production Testi** (Son aşama)

## 🔒 Güvenlik Kontrolleri

- [ ] API keys .env dosyasında saklanıyor
- [ ] SSL sertifikası kurulu
- [ ] Webhook endpoint'leri güvenli
- [ ] Rate limiting aktif
- [ ] Logging detaylı