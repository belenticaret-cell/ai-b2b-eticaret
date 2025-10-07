

<?php $__env->startSection('title', 'Bayi Admin'); ?>
<?php $__env->startSection('page-title', 'Bayi Admin Paneli'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $bayiAd = $bayi->ad ?? 'Bayi';
?>
<div class="space-y-6">
    <!-- Karşılama -->
    <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 rounded-xl p-6 text-white">
        <h2 class="text-2xl font-bold">👋 Hoş geldiniz, <?php echo e($bayiAd); ?></h2>
        <p class="text-indigo-100">Kendi mağazanızı yönetin, ürün ve siparişleri takip edin.</p>
    </div>

    <!-- Hızlı Aksiyonlar -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="<?php echo e(route('bayi.urunler')); ?>" class="bg-white border rounded-xl p-4 hover:shadow">
            <div class="text-2xl mb-2">📦</div>
            <div class="font-semibold">Ürünlerim</div>
            <div class="text-sm text-gray-500">Fiyat ve görünürlük</div>
        </a>
        <a href="<?php echo e(route('bayi.siparisler')); ?>" class="bg-white border rounded-xl p-4 hover:shadow">
            <div class="text-2xl mb-2">🧾</div>
            <div class="font-semibold">Siparişlerim</div>
            <div class="text-sm text-gray-500">Durum ve teslimat</div>
        </a>
        <a href="<?php echo e(route('bayi.cari')); ?>" class="bg-white border rounded-xl p-4 hover:shadow">
            <div class="text-2xl mb-2">💳</div>
            <div class="font-semibold">Cari Hesap</div>
            <div class="text-sm text-gray-500">Bakiye ve hareketler</div>
        </a>
        <a href="<?php echo e(route('bayi.ayarlar')); ?>" class="bg-white border rounded-xl p-4 hover:shadow">
            <div class="text-2xl mb-2">⚙️</div>
            <div class="font-semibold">Mağaza Ayarları</div>
            <div class="text-sm text-gray-500">Logo, iletişim, vitrin</div>
        </a>
    </div>

    <!-- Özet Kartları -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white border rounded-xl p-4">
            <div class="text-sm text-gray-500">Toplam Sipariş</div>
            <div class="text-2xl font-bold"><?php echo e($stats['toplam_siparis']); ?></div>
        </div>
        <div class="bg-white border rounded-xl p-4">
            <div class="text-sm text-gray-500">Bu Ay Sipariş</div>
            <div class="text-2xl font-bold"><?php echo e($stats['bu_ay_siparis']); ?></div>
        </div>
        <div class="bg-white border rounded-xl p-4">
            <div class="text-sm text-gray-500">Bekleyen Sipariş</div>
            <div class="text-2xl font-bold"><?php echo e($stats['bekleyen_siparis']); ?></div>
        </div>
        <div class="bg-white border rounded-xl p-4">
            <div class="text-sm text-gray-500">Aktif Ürün</div>
            <div class="text-2xl font-bold"><?php echo e($stats['aktif_urun']); ?></div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Son Siparişler -->
        <div class="bg-white border rounded-xl p-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold">Son Siparişler</h3>
                <a href="<?php echo e(route('bayi.siparisler')); ?>" class="text-sm text-blue-600">Tümü →</a>
            </div>
            <div class="space-y-3">
                <?php $__empty_1 = true; $__currentLoopData = $sonSiparisler; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-3 border rounded-lg flex items-center justify-between">
                        <div>
                            <div class="font-medium text-sm">#<?php echo e($s->id); ?></div>
                            <div class="text-xs text-gray-500"><?php echo e(optional($s->created_at)->diffForHumans()); ?></div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-semibold text-teal-600"><?php echo e(number_format((float)($s->toplam_tutar ?? 0), 2)); ?> ₺</div>
                            <div class="text-xs text-gray-500"><?php echo e($s->durum ?? 'bekliyor'); ?></div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-sm text-gray-500">Henüz sipariş yok.</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Popüler Ürünler -->
        <div class="bg-white border rounded-xl p-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold">Popüler Ürünler</h3>
                <a href="<?php echo e(route('bayi.urunler')); ?>" class="text-sm text-blue-600">Ürünlere Git →</a>
            </div>
            <div class="space-y-3">
                <?php $__empty_1 = true; $__currentLoopData = $populerUrunler; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-3 border rounded-lg flex items-center justify-between">
                        <div class="font-medium text-sm"><?php echo e($p->urun->ad ?? 'Ürün'); ?></div>
                        <div class="text-xs text-gray-500"><?php echo e($p->toplam_adet); ?> adet</div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-sm text-gray-500">Henüz satış verisi yok.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.bayi.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ai-b2b\resources\views/bayi/dashboard.blade.php ENDPATH**/ ?>