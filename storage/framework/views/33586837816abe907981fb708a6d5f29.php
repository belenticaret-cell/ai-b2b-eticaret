

<?php $__env->startSection('title', 'Anasayfa Yönetimi'); ?>
<?php $__env->startSection('page-title', 'Anasayfa Yönetimi'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Hero ve Banner</h2>
        <form method="POST" action="<?php echo e(route('admin.anasayfa.guncelle')); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hero Başlık</label>
                    <input type="text" name="anasayfa_hero_baslik" value="<?php echo e($ayarlar['anasayfa_hero_baslik'] ?? ''); ?>" class="w-full px-3 py-2 border rounded" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hero Alt Başlık</label>
                    <input type="text" name="anasayfa_hero_altbaslik" value="<?php echo e($ayarlar['anasayfa_hero_altbaslik'] ?? ''); ?>" class="w-full px-3 py-2 border rounded" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hero Buton Yazısı</label>
                    <input type="text" name="anasayfa_hero_buton_yazi" value="<?php echo e($ayarlar['anasayfa_hero_buton_yazi'] ?? ''); ?>" class="w-full px-3 py-2 border rounded" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hero Buton Linki</label>
                    <input type="text" name="anasayfa_hero_buton_link" value="<?php echo e($ayarlar['anasayfa_hero_buton_link'] ?? ''); ?>" class="w-full px-3 py-2 border rounded" />
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Üst Duyuru (opsiyonel)</label>
                    <input type="text" name="anasayfa_duyuru" value="<?php echo e($ayarlar['anasayfa_duyuru'] ?? ''); ?>" class="w-full px-3 py-2 border rounded" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Üst Banner Resmi (URL)</label>
                    <input type="text" name="anasayfa_ust_banner_resmi" value="<?php echo e($ayarlar['anasayfa_ust_banner_resmi'] ?? ''); ?>" class="w-full px-3 py-2 border rounded" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alt Banner Resmi (URL)</label>
                    <input type="text" name="anasayfa_alt_banner_resmi" value="<?php echo e($ayarlar['anasayfa_alt_banner_resmi'] ?? ''); ?>" class="w-full px-3 py-2 border rounded" />
                </div>
            </div>

            <h2 class="text-lg font-medium text-gray-900 mt-8 mb-4">Öne Çıkanlar</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Öne Çıkan Kategoriler</label>
                    <?php ($seciliKats = array_filter(explode(',', $ayarlar['anasayfa_onecikan_kategoriler'] ?? ''))); ?>
                    <select name="anasayfa_onecikan_kategoriler[]" multiple class="w-full px-3 py-2 border rounded min-h-[160px]">
                        <?php $__currentLoopData = $kategoriler; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($k->id); ?>" <?php if(in_array((string)$k->id, $seciliKats)): ?> selected <?php endif; ?>><?php echo e($k->ad); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <small class="text-gray-500">Ctrl/Command ile birden fazla seçebilirsiniz.</small>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Öne Çıkan Ürünler</label>
                    <?php ($seciliUrun = array_filter(explode(',', $ayarlar['anasayfa_onecikan_urunler'] ?? ''))); ?>
                    <select name="anasayfa_onecikan_urunler[]" multiple class="w-full px-3 py-2 border rounded min-h-[160px]">
                        <?php $__currentLoopData = $urunler; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($u->id); ?>" <?php if(in_array((string)$u->id, $seciliUrun)): ?> selected <?php endif; ?>><?php echo e($u->ad); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <small class="text-gray-500">Ctrl/Command ile birden fazla seçebilirsiniz.</small>
                </div>
            </div>

            <h2 class="text-lg font-medium text-gray-900 mt-8 mb-4">Header / Footer / Logo</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Header Aktif mi?</label>
                    <select name="theme_header_aktif" class="w-full px-3 py-2 border rounded">
                        <option value="1" <?php echo e(($ayarlar['theme_header_aktif'] ?? '1') == '1' ? 'selected' : ''); ?>>Aktif</option>
                        <option value="0" <?php echo e(($ayarlar['theme_header_aktif'] ?? '1') == '0' ? 'selected' : ''); ?>>Pasif</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Footer Aktif mi?</label>
                    <select name="theme_footer_aktif" class="w-full px-3 py-2 border rounded">
                        <option value="1" <?php echo e(($ayarlar['theme_footer_aktif'] ?? '1') == '1' ? 'selected' : ''); ?>>Aktif</option>
                        <option value="0" <?php echo e(($ayarlar['theme_footer_aktif'] ?? '1') == '0' ? 'selected' : ''); ?>>Pasif</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Logo Konumu</label>
                    <?php ($logoKonum = $ayarlar['theme_logo_position'] ?? 'left'); ?>
                    <select name="theme_logo_position" class="w-full px-3 py-2 border rounded">
                        <option value="left" <?php echo e($logoKonum==='left' ? 'selected' : ''); ?>>Sol</option>
                        <option value="center" <?php echo e($logoKonum==='center' ? 'selected' : ''); ?>>Orta</option>
                        <option value="right" <?php echo e($logoKonum==='right' ? 'selected' : ''); ?>>Sağ</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Logo Yükle (PNG/SVG)</label>
                    <input type="file" name="theme_logo_dosya" accept="image/png,image/svg+xml" class="w-full px-3 py-2 border rounded" />
                    <?php if(!empty($ayarlar['theme_logo_url'])): ?>
                        <div class="mt-2 flex items-center gap-3">
                            <img src="<?php echo e($ayarlar['theme_logo_url']); ?>" alt="Logo" class="h-10 object-contain bg-white p-1 border rounded" />
                            <span class="text-sm text-gray-500">Mevcut: <?php echo e($ayarlar['theme_logo_url']); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Logo Maksimum Yükseklik (px)</label>
                    <input type="number" min="20" max="120" step="1" name="theme_logo_max_h" value="<?php echo e($ayarlar['theme_logo_max_h'] ?? 40); ?>" class="w-full px-3 py-2 border rounded" />
                    <small class="text-gray-500">Yükleme sırasında otomatik ölçeklendirme uygulanır.</small>
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition">Kaydet</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ai-b2b\resources\views/admin/anasayfa/index.blade.php ENDPATH**/ ?>