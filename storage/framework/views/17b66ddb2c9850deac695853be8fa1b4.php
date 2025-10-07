

<?php $__env->startSection('title', 'Geliştirici Sayfası'); ?>
<?php $__env->startSection('page-title', 'Geliştirici Takip Paneli'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8" x-data="gelissiriciPanel()">
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-gray-900 via-purple-900 to-gray-900 rounded-2xl p-8 text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">👨‍💻 Geliştirici Takip Paneli</h1>
                    <p class="text-purple-100 text-lg">AI B2B E-Ticaret Projesi - Gelişim Durumu</p>
                    <p class="text-purple-200 text-sm mt-2">📅 Son güncelleme: <?php echo e(now()->format('d.m.Y H:i')); ?></p>
                </div>
                <div class="hidden md:block">
                    <div class="bg-white bg-opacity-20 p-4 rounded-full">
                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9.4 16.6L4.8 12l4.6-4.6L8 6l-6 6 6 6 1.4-1.4zm5.2 0L19.2 12l-4.6-4.6L16 6l6 6-6 6-1.4-1.4z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hızlı Kontrol Bağlantıları (Admin + B2B Test) -->
    <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
        <h3 class="text-lg font-bold text-gray-800">🔍 Hızlı Kontrol Bağlantıları</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mt-4">
            <!-- Admin Yönetim Linkleri -->
            <a href="<?php echo e(route('admin.vitrin.index')); ?>" class="px-4 py-2 rounded-lg border hover:bg-gray-50 text-sm">🎨 Vitrin (Pazarlama) Yönetimi</a>
            <a href="<?php echo e(route('admin.anasayfa')); ?>" class="px-4 py-2 rounded-lg border hover:bg-gray-50 text-sm">🏠 Anasayfa Yönetimi</a>
            <a href="<?php echo e(route('admin.site-ayar.index')); ?>" class="px-4 py-2 rounded-lg border hover:bg-gray-50 text-sm">⚙️ E‑Ticaret Site Yönetimi</a>
            <a href="<?php echo e(route('admin.moduller.entegrasyon')); ?>" class="px-4 py-2 rounded-lg border hover:bg-gray-50 text-sm">🔗 Entegrasyon Modülü</a>
            <a href="<?php echo e(route('admin.magaza.index')); ?>" class="px-4 py-2 rounded-lg border hover:bg-gray-50 text-sm">🏪 Mağazalar</a>
            <a href="<?php echo e(route('admin.kategori.index')); ?>" class="px-4 py-2 rounded-lg border hover:bg-gray-50 text-sm">📂 Kategoriler</a>
            <a href="<?php echo e(route('admin.urun.create')); ?>" class="px-4 py-2 rounded-lg border hover:bg-gray-50 text-sm">📦 Yeni Ürün</a>
            <a href="<?php echo e(route('admin.bayi.index')); ?>" class="px-4 py-2 rounded-lg border hover:bg-gray-50 text-sm">👥 Bayiler</a>
            <a href="<?php echo e(route('admin.gelistirici.index')); ?>" class="px-4 py-2 rounded-lg border hover:bg-gray-50 text-sm">🧑‍💻 Geliştirici</a>

            <!-- Public Linkler -->
            <a href="<?php echo e(route('vitrin.index')); ?>" target="_blank" class="px-4 py-2 rounded-lg border hover:bg-gray-50 text-sm">🌐 Vitrin (Public)</a>
            <a href="<?php echo e(route('vitrin.magaza')); ?>" target="_blank" class="px-4 py-2 rounded-lg border hover:bg-gray-50 text-sm">🛍️ Mağaza (Public)</a>

            <!-- B2B/Bayi Admin Test Linkleri -->
            <a href="<?php echo e(route('b2b.login')); ?>" target="_blank" class="px-4 py-2 rounded-lg border hover:bg-gray-50 text-sm">🏢 B2B Giriş</a>
            <a href="<?php echo e(route('b2b.panel')); ?>" target="_blank" class="px-4 py-2 rounded-lg border hover:bg-gray-50 text-sm">➡️ B2B Panel (Yönlendir)</a>
            <a href="<?php echo e(route('bayi.panel')); ?>" target="_blank" class="px-4 py-2 rounded-lg border hover:bg-gray-50 text-sm">📊 Bayi Admin Paneli</a>
            <a href="<?php echo e(route('bayi.urunler')); ?>" target="_blank" class="px-4 py-2 rounded-lg border hover:bg-gray-50 text-sm">📦 Ürünlerim (Bayi)</a>
            <a href="<?php echo e(route('bayi.siparisler')); ?>" target="_blank" class="px-4 py-2 rounded-lg border hover:bg-gray-50 text-sm">🧾 Siparişlerim (Bayi)</a>
            <a href="<?php echo e(route('bayi.cari')); ?>" target="_blank" class="px-4 py-2 rounded-lg border hover:bg-gray-50 text-sm">💳 Cari Hesap (Bayi)</a>
            <a href="<?php echo e(route('bayi.toplu-siparis')); ?>" target="_blank" class="px-4 py-2 rounded-lg border hover:bg-gray-50 text-sm">🗂️ Toplu Sipariş (Bayi)</a>
            <a href="<?php echo e(route('bayi.ayarlar')); ?>" target="_blank" class="px-4 py-2 rounded-lg border hover:bg-gray-50 text-sm">⚙️ Mağaza Ayarları (Bayi)</a>
            <a href="<?php echo e(route('bayi.profil')); ?>" target="_blank" class="px-4 py-2 rounded-lg border hover:bg-gray-50 text-sm">👤 Profil (Bayi)</a>
        </div>
        <p class="text-xs text-gray-500 mt-3">Not: Bayi bağlantıları için bayi rolüyle giriş gerekir; yetkisiz erişimler B2B giriş sayfasına yönlendirilir.</p>

        <?php if(app()->environment('local')): ?>
        <div class="mt-4 pt-4 border-t">
            <h4 class="text-sm font-semibold text-gray-700 mb-2">⚡ Yerel Hızlı Giriş (Test)</h4>
            <div class="flex flex-wrap gap-2">
                <a href="<?php echo e(route('dev.login', 'admin')); ?>" class="px-3 py-1.5 rounded border text-xs hover:bg-gray-50">Admin Giriş</a>
                <a href="<?php echo e(route('dev.login', 'bayi')); ?>" class="px-3 py-1.5 rounded border text-xs hover:bg-gray-50">Bayi Giriş</a>
                <a href="<?php echo e(route('dev.login', 'musteri')); ?>" class="px-3 py-1.5 rounded border text-xs hover:bg-gray-50">Müşteri Giriş</a>
                <a href="<?php echo e(route('dev.logout')); ?>" class="px-3 py-1.5 rounded border text-xs hover:bg-gray-50">Çıkış</a>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Sistem İstatistikleri -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
        <div class="bg-white rounded-xl shadow border p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Ürünler</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e(number_format($stats['urunler'])); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow border p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Bayiler</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e(number_format($stats['bayiler'])); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow border p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Siparişler</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e(number_format($stats['siparisler'])); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow border p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Kategoriler</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e(number_format($stats['kategoriler'])); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow border p-6">
            <div class="flex items-center">
                <div class="p-3 bg-indigo-100 rounded-lg">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Ayarlar</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e(number_format($stats['site_ayarlari'])); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Proje Durumu Tabları -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Tamamlanan Projeler -->
        <div class="bg-white rounded-xl shadow border">
            <div class="px-6 py-4 border-b border-gray-200 bg-green-50">
                <h3 class="text-lg font-semibold text-green-800 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    ✅ Tamamlanan (<?php echo e(count($projeDurumu['tamamlanan'])); ?>)
                </h3>
            </div>
            <div class="p-6 space-y-4 max-h-96 overflow-y-auto">
                <?php $__currentLoopData = $projeDurumu['tamamlanan']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proje): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border border-green-200 rounded-lg p-4 bg-green-50">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-semibold text-gray-900"><?php echo e($proje['ad']); ?></h4>
                            <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full"><?php echo e($proje['tarih']); ?></span>
                        </div>
                        <p class="text-sm text-gray-600 mb-3"><?php echo e($proje['aciklama']); ?></p>
                        <p class="text-xs text-gray-500 mb-3">💻 <?php echo e($proje['teknoloji']); ?></p>
                        <div class="flex gap-2">
                            <?php if($proje['link']): ?>
                                <a href="<?php echo e($proje['link']); ?>" class="text-xs bg-green-600 text-white px-3 py-1 rounded-full hover:bg-green-700">Yönet</a>
                            <?php endif; ?>
                            <?php if($proje['test_link']): ?>
                                <a href="<?php echo e($proje['test_link']); ?>" target="_blank" class="text-xs bg-blue-600 text-white px-3 py-1 rounded-full hover:bg-blue-700">Test Et</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- Geliştirme Aşamasında -->
        <div class="bg-white rounded-xl shadow border">
            <div class="px-6 py-4 border-b border-gray-200 bg-yellow-50">
                <h3 class="text-lg font-semibold text-yellow-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 animate-spin" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                    </svg>
                    ⚠️ Geliştirme Aşaması (<?php echo e(count($projeDurumu['gelistirme_asamasi'])); ?>)
                </h3>
            </div>
            <div class="p-6 space-y-4 max-h-96 overflow-y-auto">
                <?php $__currentLoopData = $projeDurumu['gelistirme_asamasi']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proje): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border border-yellow-200 rounded-lg p-4 bg-yellow-50">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-semibold text-gray-900"><?php echo e($proje['ad']); ?></h4>
                            <span class="text-xs bg-<?php echo e($proje['oncelik'] == 'yuksek' ? 'red' : ($proje['oncelik'] == 'orta' ? 'yellow' : 'green')); ?>-100 text-<?php echo e($proje['oncelik'] == 'yuksek' ? 'red' : ($proje['oncelik'] == 'orta' ? 'yellow' : 'green')); ?>-800 px-2 py-1 rounded-full">
                                <?php echo e(ucfirst($proje['oncelik'])); ?>

                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2"><?php echo e($proje['aciklama']); ?></p>
                        <p class="text-xs text-gray-500 mb-2">⏱️ <?php echo e($proje['tahmini_sure']); ?></p>
                        <p class="text-xs text-gray-500 mb-2">📋 <?php echo e($proje['gereksinimler']); ?></p>
                        <p class="text-xs text-gray-500">💻 <?php echo e($proje['teknoloji']); ?></p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- Planlanan Özellikler -->
        <div class="bg-white rounded-xl shadow border">
            <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
                <h3 class="text-lg font-semibold text-blue-800 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    🔮 Planlanan (<?php echo e(count($projeDurumu['planlanan'])); ?>)
                </h3>
            </div>
            <div class="p-6 space-y-4 max-h-96 overflow-y-auto">
                <?php $__currentLoopData = $projeDurumu['planlanan']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proje): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border border-blue-200 rounded-lg p-4 bg-blue-50">
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-semibold text-gray-900"><?php echo e($proje['ad']); ?></h4>
                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full"><?php echo e(ucfirst($proje['oncelik'])); ?></span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2"><?php echo e($proje['aciklama']); ?></p>
                        <p class="text-xs text-gray-500 mb-2">⏱️ <?php echo e($proje['tahmini_sure']); ?></p>
                        <p class="text-xs text-gray-500">💻 <?php echo e($proje['teknoloji']); ?></p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    <!-- Teknoloji Stack ve Sistem Sağlığı -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Teknoloji Stack -->
        <div class="bg-white rounded-xl shadow border">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"></path>
                    </svg>
                    🛠️ Teknoloji Stack
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">Backend</h4>
                        <div class="flex flex-wrap gap-2">
                            <?php $__currentLoopData = $teknolojiStack['backend']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tech): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm"><?php echo e($tech); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">Frontend</h4>
                        <div class="flex flex-wrap gap-2">
                            <?php $__currentLoopData = $teknolojiStack['frontend']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tech): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm"><?php echo e($tech); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">Tools</h4>
                        <div class="flex flex-wrap gap-2">
                            <?php $__currentLoopData = $teknolojiStack['tools']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tech): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm"><?php echo e($tech); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">Deployment</h4>
                        <div class="flex flex-wrap gap-2">
                            <?php $__currentLoopData = $teknolojiStack['deployment']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tech): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm"><?php echo e($tech); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sistem Sağlığı -->
        <div class="bg-white rounded-xl shadow border">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    🏥 Sistem Sağlığı
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <?php $__currentLoopData = $sistemSagligi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $component => $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 capitalize"><?php echo e(ucfirst(str_replace('_', ' ', $component))); ?></span>
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm flex items-center">
                                <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                                <?php echo e($status); ?>

                            </span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Geliştirici Notları -->
    <div class="bg-white rounded-xl shadow border">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">📝 Geliştirici Notları</h3>
                <button @click="showNoteModal = true" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm">
                    + Yeni Not
                </button>
            </div>
        </div>
        <div class="p-6">
            <?php
                $notlar = session('gelistirici_notlar', []);
            ?>
            <?php if(count($notlar) > 0): ?>
                <div class="space-y-3">
                    <?php $__currentLoopData = $notlar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $not): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-medium text-gray-900"><?php echo e($not['baslik']); ?></h4>
                                <span class="text-xs bg-<?php echo e($not['oncelik'] == 'acil' ? 'red' : ($not['oncelik'] == 'yüksek' ? 'orange' : ($not['oncelik'] == 'orta' ? 'yellow' : 'green'))); ?>-100 text-<?php echo e($not['oncelik'] == 'acil' ? 'red' : ($not['oncelik'] == 'yüksek' ? 'orange' : ($not['oncelik'] == 'orta' ? 'yellow' : 'green'))); ?>-800 px-2 py-1 rounded-full">
                                    <?php echo e($not['oncelik']); ?>

                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-2"><?php echo e($not['icerik']); ?></p>
                            <p class="text-xs text-gray-500"><?php echo e($not['tarih']); ?></p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <p class="text-gray-500">Henüz not eklenmemiş</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Not Ekleme Modal -->
<div x-show="showNoteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="showNoteModal" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-500 bg-opacity-75" 
             @click="showNoteModal = false"></div>

        <div x-show="showNoteModal" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            
            <form action="<?php echo e(route('admin.gelistirici.not-ekle')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="bg-white px-6 pt-6 pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Yeni Geliştirici Notu</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Başlık</label>
                            <input type="text" name="baslik" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Kısa başlık">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">İçerik</label>
                            <textarea name="icerik" rows="4" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Detaylı açıklama..."></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Öncelik</label>
                            <select name="oncelik" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="düşük">Düşük</option>
                                <option value="orta" selected>Orta</option>
                                <option value="yüksek">Yüksek</option>
                                <option value="acil">Acil</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-6 py-3 flex justify-end space-x-3">
                    <button type="button" @click="showNoteModal = false" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">İptal</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function gelissiriciPanel() {
    return {
        showNoteModal: false
    }
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ai-b2b\resources\views/admin/gelistirici/index.blade.php ENDPATH**/ ?>