

<?php $__env->startSection('title', 'E-Ticaret Site Ayarları'); ?>
<?php $__env->startSection('page-title', 'E-Ticaret Site Ayarları'); ?>

<?php $__env->startSection('content'); ?>
<div x-data="siteAyarApp()" class="space-y-8">
    <!-- Header Card -->
    <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-blue-800 rounded-2xl p-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">🛍️ E-Ticaret Site Yönetimi</h1>
                <p class="text-blue-100 text-lg">Site durumunu kontrol edin ve satışa açılacak kategorileri belirleyin</p>
            </div>
            
            <!-- Site Status Toggle -->
            <div class="text-center">
                <div class="mb-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" 
                               x-model="siteAktif" 
                               @change="toggleSite()"
                               class="sr-only">
                        <div class="relative">
                            <div class="w-20 h-10 bg-gray-300 rounded-full shadow-inner transition-colors duration-300"
                                 :class="siteAktif ? 'bg-green-500' : 'bg-gray-400'"></div>
                            <div class="absolute w-8 h-8 bg-white rounded-full shadow top-1 left-1 transition-transform duration-300"
                                 :class="siteAktif ? 'transform translate-x-10' : ''"></div>
                        </div>
                    </label>
                </div>
                <div class="text-sm font-semibold">
                    <span x-text="siteAktif ? '🟢 E-Ticaret Aktif' : '🔴 E-Ticaret Pasif'"></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Toplam Ürün</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e($stats['toplam_urun']); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Aktif Ürün</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e($stats['aktif_urun']); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Kategori Sayısı</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e($stats['kategori_sayisi']); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-100">
            <div class="flex items-center">
                <div class="p-3 bg-orange-100 rounded-full">
                    <svg class="w-8 h-8 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.5 4a1.5 1.5 0 01-3 0 1.5 1.5 0 013 0zm-3 8a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Satışa Açık</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e($stats['satisa_acik_kategori']); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Site Configuration -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Settings -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    ⚙️ Temel Ayarlar
                </h3>

                <form action="<?php echo e(route('admin.site-ayar.update')); ?>" method="POST" class="space-y-6">
                    <?php echo csrf_field(); ?>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Site Adı</label>
                            <input type="text" name="site_adi" 
                                   value="<?php echo e($ayarlar['site_adi'] ?? 'AI B2B E-Ticaret'); ?>"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Site Durumu</label>
                            <select name="site_aktif" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="0" <?php echo e(($ayarlar['site_aktif'] ?? '0') === '0' ? 'selected' : ''); ?>>🔴 Pasif</option>
                                <option value="1" <?php echo e(($ayarlar['site_aktif'] ?? '0') === '1' ? 'selected' : ''); ?>>🟢 Aktif</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Site Açıklaması</label>
                        <textarea name="site_aciklama" rows="3" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Sitenizin kısa açıklaması..."><?php echo e($ayarlar['site_aciklama'] ?? ''); ?></textarea>
                    </div>

                    <!-- Category Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-4">📂 Satışa Açık Kategoriler</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-64 overflow-y-auto border border-gray-200 rounded-lg p-4">
                            <?php
                                $secilenKategoriler = !empty($ayarlar['satisa_acik_kategoriler']) 
                                    ? explode(',', $ayarlar['satisa_acik_kategoriler']) 
                                    : [];
                            ?>
                            
                            <?php $__currentLoopData = $kategoriler; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kategori): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                                    <input type="checkbox" name="satisa_acik_kategoriler[]" 
                                           value="<?php echo e($kategori->id); ?>"
                                           <?php echo e(in_array($kategori->id, $secilenKategoriler) ? 'checked' : ''); ?>

                                           class="text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <div class="ml-3 flex-1">
                                        <div class="font-medium text-gray-900"><?php echo e($kategori->ad); ?></div>
                                        <div class="text-sm text-gray-500"><?php echo e($kategori->urunler_count ?? 0); ?> ürün</div>
                                    </div>
                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <p class="text-sm text-gray-600 mt-2">
                            💡 Seçilen kategorilerdeki ürünler satışa açılacaktır. Diğer kategoriler gizli kalacaktır.
                        </p>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                                class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-8 py-3 rounded-lg font-semibold hover:shadow-lg transition-all transform hover:scale-105">
                            💾 Ayarları Kaydet
                        </button>
                    </div>
                </form>
            </div>

            <!-- Advanced Settings -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    🎨 Görsel & İletişim Ayarları
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Logo URL</label>
                        <input type="url" name="site_logo_url" 
                               value="<?php echo e($ayarlar['site_logo_url'] ?? ''); ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="https://...">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ana Renk</label>
                        <input type="color" name="site_renk_ana" 
                               value="<?php echo e($ayarlar['site_renk_ana'] ?? '#3B82F6'); ?>"
                               class="w-full h-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">İletişim E-posta</label>
                        <input type="email" name="iletisim_email" 
                               value="<?php echo e($ayarlar['iletisim_email'] ?? ''); ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="info@siteniz.com">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">İletişim Telefon</label>
                        <input type="tel" name="iletisim_telefon" 
                               value="<?php echo e($ayarlar['iletisim_telefon'] ?? ''); ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="+90 555 123 45 67">
                    </div>
                </div>
            </div>
        </div>

        <!-- Side Panel -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">⚡ Hızlı İşlemler</h3>
                
                <div class="space-y-3">
                    <a href="<?php echo e(route('vitrin.index')); ?>" target="_blank"
                       class="w-full bg-blue-50 text-blue-700 px-4 py-3 rounded-lg text-sm font-medium hover:bg-blue-100 transition-colors flex items-center">
                        🌐 Siteyi Önizle
                        <svg class="w-4 h-4 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                    </a>
                    
                    <a href="<?php echo e(route('vitrin.magaza')); ?>" target="_blank"
                       class="w-full bg-green-50 text-green-700 px-4 py-3 rounded-lg text-sm font-medium hover:bg-green-100 transition-colors flex items-center">
                        🛍️ Mağazayı Görüntüle
                        <svg class="w-4 h-4 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                    </a>
                    
                    <a href="<?php echo e(route('admin.urun.index')); ?>"
                       class="w-full bg-purple-50 text-purple-700 px-4 py-3 rounded-lg text-sm font-medium hover:bg-purple-100 transition-colors flex items-center">
                        📦 Ürün Yönetimi
                        <svg class="w-4 h-4 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    
                    <a href="<?php echo e(route('admin.kategori.index')); ?>"
                       class="w-full bg-orange-50 text-orange-700 px-4 py-3 rounded-lg text-sm font-medium hover:bg-orange-100 transition-colors flex items-center">
                        📂 Kategori Yönetimi
                        <svg class="w-4 h-4 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Status Info -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">📊 Sistem Durumu</h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">Site Durumu</span>
                        <span class="text-sm font-bold" 
                              :class="siteAktif ? 'text-green-600' : 'text-red-600'"
                              x-text="siteAktif ? '🟢 Aktif' : '🔴 Pasif'"></span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">Toplam Ürün</span>
                        <span class="text-sm font-bold text-blue-600"><?php echo e($stats['toplam_urun']); ?></span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">Satışa Açık</span>
                        <span class="text-sm font-bold text-green-600"><?php echo e($stats['aktif_urun']); ?></span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <span class="text-sm font-medium text-gray-700">Son Güncelleme</span>
                        <span class="text-sm text-gray-600"><?php echo e(now()->format('d.m.Y H:i')); ?></span>
                    </div>
                </div>
            </div>

            <!-- Tips -->
            <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl shadow-lg border border-yellow-200 p-6">
                <h3 class="text-lg font-bold text-yellow-800 mb-4">💡 İpuçları</h3>
                
                <div class="space-y-3 text-sm text-yellow-700">
                    <p>• Site aktif olduğunda sadece seçilen kategorilerdeki ürünler görünür</p>
                    <p>• Site pasif olduğunda tüm ürünler gizlenir</p>
                    <p>• Kategori seçimi otomatik olarak ürün durumlarını günceller</p>
                    <p>• Değişiklikler anında etki eder</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div x-show="showToast" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed bottom-6 right-6 z-50 px-6 py-4 rounded-lg shadow-xl text-white"
         :class="toastType === 'success' ? 'bg-green-500' : 'bg-red-500'">
        <div class="flex items-center">
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span x-text="toastMessage"></span>
        </div>
    </div>
</div>

<script>
function siteAyarApp() {
    return {
        siteAktif: <?php echo e(($ayarlar['site_aktif'] ?? '0') === '1' ? 'true' : 'false'); ?>,
        showToast: false,
        toastMessage: '',
        toastType: 'success',
        
        toggleSite() {
            fetch('<?php echo e(route('admin.site-ayar.toggle')); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({
                    durum: this.siteAktif ? '1' : '0'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.showToastMessage(data.message, 'success');
                } else {
                    this.showToastMessage('Bir hata oluştu!', 'error');
                    this.siteAktif = !this.siteAktif; // Revert toggle
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.showToastMessage('Bir hata oluştu!', 'error');
                this.siteAktif = !this.siteAktif; // Revert toggle
            });
        },
        
        showToastMessage(message, type = 'success') {
            this.toastMessage = message;
            this.toastType = type;
            this.showToast = true;
            
            setTimeout(() => {
                this.showToast = false;
            }, 3000);
        }
    }
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ai-b2b\resources\views/admin/site-ayar/index.blade.php ENDPATH**/ ?>