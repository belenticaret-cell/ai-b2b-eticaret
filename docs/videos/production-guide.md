# Video Üretim Kılavuzu

- Hedef çözünürlük: 1920x1080 (1080p), 30fps
- Kayıt aracı: OBS Studio
  - Kaynaklar: Ekran (1080p), Mikrofon
  - Çıkış: MKV (güvenli), sonradan MP4'e dönüştür
  - Bitrate: 8-12 Mbps
- Ses: Harici mikrofon, -12 dB ortalama, pop filtresi
- Edit: DaVinci Resolve/Shotcut
  - Kesme, ses dengeleme, basit geçişler
  - Açılış/kapanış kartı, alt bant (lower-third)
- Kodlama: H.264, Yüksek profil, 8-12 Mbps, Stereo 160 kbps
- Dosya adı şeması: `NN-kisa-baslik-tr.mp4` (örn: `01-admin-panel-tur-tr.mp4`)
- Telif: Arka plan müziği kullanmayın veya lisanslı/parçasız
- Altyazı: TR altyazı (YouTube otomatik + manuel düzeltme)

## OBS Hızlı Ayar
- Video: Canvas 1920x1080, Output 1920x1080, 30fps
- Output Recording: MKV, Quality High, Encoder x264, CRF 20-23

## İçerik Şablonu
- 0:00 Açılış (logo + başlık)
- 0:05 Giriş (amaç ve kazanımlar)
- 0:20 Demo adımları (net callout’lar)
- 2:30 Özet
- 2:45 Kapanış (kaynaklar/playlist)
