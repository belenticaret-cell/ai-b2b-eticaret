

<?php $__env->startSection('title', 'AI B2B E-Ticaret - Ana Sayfa'); ?>
<?php $__env->startSection('meta_description', 'AI B2B E-Ticaret platformunda kaliteli ürünleri keşfedin. B2B ve B2C satış seçenekleri ile en uygun fiyatları bulun.'); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Section with Particles Effect -->
<section class="relative bg-gradient-to-br from-blue-900 via-blue-800 to-purple-900 text-white py-20 overflow-hidden">
    <!-- Animated Background -->
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-20 left-20 w-72 h-72 bg-blue-400 rounded-full mix-blend-multiply filter blur-xl animate-pulse"></div>
        <div class="absolute top-40 right-20 w-72 h-72 bg-purple-400 rounded-full mix-blend-multiply filter blur-xl animate-pulse animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-40 w-72 h-72 bg-pink-400 rounded-full mix-blend-multiply filter blur-xl animate-pulse animation-delay-4000"></div>
    </div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <!-- Hero Content -->
            <div class="text-center lg:text-left">
                <div class="inline-flex items-center bg-white bg-opacity-10 backdrop-blur-sm rounded-full px-4 py-2 mb-6">
                    <span class="text-green-400 text-sm font-semibold">🚀 YENİ</span>
                    <span class="ml-2 text-sm">AI Destekli E-Ticaret Platformu</span>
                </div>
                
                <h1 class="text-5xl lg:text-6xl font-extrabold mb-6 leading-tight">
                    <span class="bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent">
                        <?php echo e($siteAyarlar['anasayfa_hero_baslik'] ?? 'Geleceğin E-Ticaret'); ?>

                    </span>
                    <br>
                    <span class="text-yellow-400">Platformu</span>
                </h1>
                
                <p class="text-xl text-blue-100 mb-8 max-w-lg">
                    <?php echo e($siteAyarlar['anasayfa_hero_altbaslik'] ?? 'AI teknolojisi ile desteklenen, çoklu platform entegrasyonlu B2B/B2C e-ticaret çözümü'); ?>

                </p>
                
                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 mb-8">
                    <a href="<?php echo e(route('vitrin.magaza')); ?>" 
                       class="bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 px-8 py-4 rounded-full font-bold text-lg hover:shadow-2xl hover:scale-105 transition-all duration-300 flex items-center justify-center">
                        🛍️ Mağazaya Git
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                    
                    <a href="#features" 
                       class="border-2 border-white text-white px-8 py-4 rounded-full font-bold text-lg hover:bg-white hover:text-blue-900 transition-all duration-300 flex items-center justify-center">
                        📖 Daha Fazla Bilgi
                    </a>
                </div>
                
                <!-- Social Proof -->
                <div class="flex items-center space-x-6 text-blue-200">
                    <div class="flex items-center">
                        <span class="text-2xl font-bold text-white"><?php echo e($urunSayisi ?? '1000+'); ?></span>
                        <span class="ml-2">Ürün</span>
                    </div>
                    <div class="w-1 h-6 bg-blue-300"></div>
                    <div class="flex items-center">
                        <span class="text-2xl font-bold text-white"><?php echo e($platformStats['aktif_magaza'] ?? '5+'); ?></span>
                        <span class="ml-2">Platform</span>
                    </div>
                    <div class="w-1 h-6 bg-blue-300"></div>
                    <div class="flex items-center">
                        <span class="text-2xl font-bold text-white">%99.9</span>
                        <span class="ml-2">Uptime</span>
                    </div>
                </div>
            </div>
            
            <!-- Hero Visual -->
            <div class="relative">
                <div class="relative z-10">
                    <!-- Dashboard Preview -->
                    <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-2xl p-6 shadow-2xl border border-white border-opacity-20">
                        <div class="flex items-center mb-4">
                            <div class="flex space-x-2">
                                <div class="w-3 h-3 bg-red-400 rounded-full"></div>
                                <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                                <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                            </div>
                            <div class="ml-4 text-sm text-blue-200">AI B2B Dashboard</div>
                        </div>
                        
                        <!-- Mini Dashboard -->
                        <div class="space-y-3">
                            <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-4 rounded-xl">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <div class="text-blue-100 text-sm">Toplam Satış</div>
                                        <div class="text-2xl font-bold">₺847,392</div>
                                    </div>
                                    <div class="text-3xl">📈</div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-gradient-to-r from-green-400 to-green-600 p-3 rounded-lg">
                                    <div class="text-sm text-green-100">Siparişler</div>
                                    <div class="text-lg font-bold">1,247</div>
                                </div>
                                <div class="bg-gradient-to-r from-orange-400 to-red-500 p-3 rounded-lg">
                                    <div class="text-sm text-orange-100">Ürünler</div>
                                    <div class="text-lg font-bold"><?php echo e($urunSayisi ?? '842'); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Floating Elements -->
                <div class="absolute -top-4 -right-4 w-20 h-20 bg-yellow-400 rounded-full flex items-center justify-center text-2xl animate-bounce">
                    🚀
                </div>
                <div class="absolute -bottom-4 -left-4 w-16 h-16 bg-pink-400 rounded-full flex items-center justify-center text-xl animate-pulse">
                    ⭐
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
        </svg>
    </div>
</section>

<!-- Platform Integration Bar -->
<section class="bg-gray-50 py-8 border-b">
    <div class="container mx-auto px-4">
        <div class="text-center mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">🔗 Entegre Platformlar</h3>
            <p class="text-gray-600">Tüm büyük e-ticaret platformları ile senkronize</p>
        </div>
        
        <div class="flex flex-wrap justify-center items-center gap-8 opacity-70 hover:opacity-100 transition-opacity">
            <div class="flex items-center space-x-3 bg-white p-4 rounded-lg shadow-sm">
                <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center text-white font-bold text-sm">T</div>
                <span class="font-semibold text-gray-700">Trendyol</span>
            </div>
            
            <div class="flex items-center space-x-3 bg-white p-4 rounded-lg shadow-sm">
                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm">H</div>
                <span class="font-semibold text-gray-700">Hepsiburada</span>
            </div>
            
            <div class="flex items-center space-x-3 bg-white p-4 rounded-lg shadow-sm">
                <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm">N</div>
                <span class="font-semibold text-gray-700">N11</span>
            </div>
            
            <div class="flex items-center space-x-3 bg-white p-4 rounded-lg shadow-sm">
                <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center text-white font-bold text-sm">A</div>
                <span class="font-semibold text-gray-700">Amazon</span>
            </div>
            
            <div class="flex items-center space-x-3 bg-white p-4 rounded-lg shadow-sm">
                <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center text-white font-bold text-sm">+</div>
                <span class="font-semibold text-gray-700">Daha Fazla</span>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <div class="inline-flex items-center bg-blue-100 text-blue-800 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                ✨ ÖZELLİKLER
            </div>
            <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                Neden <span class="text-blue-600">AI B2B</span> Seçmelisiniz?
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Gelişmiş teknoloji, güçlü entegrasyonlar ve kullanıcı dostu arayüz ile e-ticaret deneyiminizi yeniden tanımlayın
            </p>
        </div>
        
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- AI Features -->
            <div class="group bg-gradient-to-br from-purple-50 to-blue-50 p-8 rounded-2xl border border-purple-100 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <span class="text-2xl">🤖</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">AI Destekli Sistem</h3>
                <p class="text-gray-600 mb-6">Makine öğrenmesi ile akıllı ürün önerileri, otomatik fiyatlandırma ve stok optimizasyonu</p>
                <ul class="space-y-2 text-sm text-gray-700">
                    <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Akıllı ürün önerileri</li>
                    <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Otomatik fiyat optimizasyonu</li>
                    <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Stok tahmin algoritması</li>
                </ul>
            </div>
            
            <!-- Multi-Platform -->
            <div class="group bg-gradient-to-br from-green-50 to-teal-50 p-8 rounded-2xl border border-green-100 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-teal-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <span class="text-2xl">🔗</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Çoklu Platform</h3>
                <p class="text-gray-600 mb-6">Tüm büyük e-ticaret platformları ile otomatik senkronizasyon ve merkezi yönetim</p>
                <ul class="space-y-2 text-sm text-gray-700">
                    <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> 5+ Platform entegrasyonu</li>
                    <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Gerçek zamanlı senkronizasyon</li>
                    <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Merkezi stok yönetimi</li>
                </ul>
            </div>
            
            <!-- B2B Solutions -->
            <div class="group bg-gradient-to-br from-orange-50 to-red-50 p-8 rounded-2xl border border-orange-100 hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <span class="text-2xl">💼</span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">B2B Çözümler</h3>
                <p class="text-gray-600 mb-6">Bayiler için özel fiyatlandırma, toplu sipariş ve gelişmiş raporlama sistemleri</p>
                <ul class="space-y-2 text-sm text-gray-700">
                    <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Bayi özel fiyatları</li>
                    <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Toplu sipariş sistemi</li>
                    <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Gelişmiş raporlama</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-16 bg-gradient-to-r from-blue-600 to-purple-700">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-4 gap-8 text-center text-white">
            <div class="group">
                <div class="text-4xl lg:text-5xl font-bold mb-2 group-hover:scale-110 transition-transform"><?php echo e($urunSayisi ?? '1000+'); ?></div>
                <div class="text-blue-200 text-lg">Aktif Ürün</div>
            </div>
            <div class="group">
                <div class="text-4xl lg:text-5xl font-bold mb-2 group-hover:scale-110 transition-transform"><?php echo e($bayiSayisi ?? '50+'); ?></div>
                <div class="text-blue-200 text-lg">Mutlu Bayi</div>
            </div>
            <div class="group">
                <div class="text-4xl lg:text-5xl font-bold mb-2 group-hover:scale-110 transition-transform"><?php echo e($platformStats['aktif_magaza'] ?? '5+'); ?></div>
                <div class="text-blue-200 text-lg">Platform Entegrasyonu</div>
            </div>
            <div class="group">
                <div class="text-4xl lg:text-5xl font-bold mb-2 group-hover:scale-110 transition-transform">%99.9</div>
                <div class="text-blue-200 text-lg">Sistem Uptime</div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<?php if(isset($onerilen_urunler) && $onerilen_urunler->count() > 0): ?>
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">⭐ Öne Çıkan Ürünler</h2>
            <p class="text-xl text-gray-600">En popüler ve kaliteli ürünlerimizi keşfedin</p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php $__currentLoopData = $onerilen_urunler->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $urun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="group bg-white rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 overflow-hidden">
                <?php if($urun->gorsel): ?>
                    <div class="aspect-square overflow-hidden">
                        <img src="<?php echo e($urun->gorsel); ?>" alt="<?php echo e($urun->ad); ?>" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    </div>
                <?php else: ?>
                    <div class="aspect-square bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                        <span class="text-4xl text-gray-400">📦</span>
                    </div>
                <?php endif; ?>
                
                <div class="p-6">
                    <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2"><?php echo e($urun->ad); ?></h3>
                    <div class="text-2xl font-bold text-blue-600 mb-4">
                        ₺<?php echo e(number_format($urun->fiyat, 2, ',', '.')); ?>

                    </div>
                    <button class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white py-3 rounded-lg font-semibold hover:shadow-lg transition-all">
                        🛒 Sepete Ekle
                    </button>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        
        <div class="text-center mt-12">
            <a href="<?php echo e(route('vitrin.magaza')); ?>" 
               class="inline-flex items-center bg-gradient-to-r from-blue-500 to-purple-600 text-white px-8 py-4 rounded-full font-bold text-lg hover:shadow-xl transition-all">
                Mağazaya Git & Alışverişe Başla
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-gray-900 to-blue-900 text-white">
    <div class="container mx-auto px-4 text-center">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-4xl lg:text-5xl font-bold mb-6">
                Hemen Başlayın! 🚀
            </h2>
            <p class="text-xl text-blue-200 mb-8">
                AI destekli e-ticaret platformumuz ile işinizi büyütün. Ücretsiz deneme sürümü ile hemen başlayabilirsiniz.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?php echo e(route('register')); ?>" 
                   class="bg-gradient-to-r from-yellow-400 to-orange-500 text-gray-900 px-8 py-4 rounded-full font-bold text-lg hover:shadow-2xl hover:scale-105 transition-all">
                    ✨ Ücretsiz Başla
                </a>
                
                <a href="#" 
                   class="border-2 border-white text-white px-8 py-4 rounded-full font-bold text-lg hover:bg-white hover:text-blue-900 transition-all">
                    📞 Demo Talep Et
                </a>
            </div>
            
            <div class="mt-8 text-blue-200">
                <span class="text-sm">💳 Kredi kartı gerekmez • 🔒 SSL güvenli • 📞 7/24 destek</span>
            </div>
        </div>
    </div>
</section>

<style>
.animation-delay-2000 {
    animation-delay: 2s;
}
.animation-delay-4000 {
    animation-delay: 4s;
}
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Intersection Observer for animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in-up');
            }
        });
    }, observerOptions);
    
    // Observe all feature cards
    document.querySelectorAll('.group').forEach(el => {
        observer.observe(el);
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ai-b2b\resources\views/vitrin/index.blade.php ENDPATH**/ ?>