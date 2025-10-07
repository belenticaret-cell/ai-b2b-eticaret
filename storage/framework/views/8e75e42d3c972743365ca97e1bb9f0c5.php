

<?php $__env->startSection('title', 'Sayfa Yönetimi'); ?>
<?php $__env->startSection('page-title', 'Sayfa Yönetimi'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-lg font-medium text-gray-900">Sayfalar</h2>
        <a href="<?php echo e(route('admin.sayfalar.create')); ?>" 
           class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
            Yeni Sayfa Ekle
        </a>
    </div>
    
    <!-- Sayfalar Listesi -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sayfa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tip</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sıra</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarih</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $sayfalar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sayfa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900"><?php echo e($sayfa->baslik); ?></div>
                                    <div class="text-sm text-gray-500">/<?php echo e($sayfa->slug); ?></div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    <?php if($sayfa->tip === 'sayfa'): ?> bg-blue-100 text-blue-800
                                    <?php elseif($sayfa->tip === 'blog'): ?> bg-green-100 text-green-800
                                    <?php else: ?> bg-purple-100 text-purple-800 <?php endif; ?>">
                                    <?php echo e(ucfirst($sayfa->tip)); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    <?php echo e($sayfa->durum ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                    <?php echo e($sayfa->durum ? 'Aktif' : 'Pasif'); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e($sayfa->sira); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo e($sayfa->created_at->format('d.m.Y H:i')); ?>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <a href="<?php echo e(route('sayfa.goster', $sayfa->slug)); ?>" target="_blank"
                                   class="text-blue-600 hover:text-blue-900">Görüntüle</a>
                                <a href="<?php echo e(route('admin.sayfalar.edit', $sayfa)); ?>" 
                                   class="text-indigo-600 hover:text-indigo-900">Düzenle</a>
                                <form method="POST" action="<?php echo e(route('admin.sayfalar.destroy', $sayfa)); ?>" 
                                      style="display: inline;" 
                                      onsubmit="return confirm('Bu sayfayı silmek istediğinizden emin misiniz?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-red-600 hover:text-red-900">Sil</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Henüz sayfa yok</h3>
                                    <p class="mt-1 text-sm text-gray-500">İlk sayfanızı oluşturarak başlayın.</p>
                                    <div class="mt-6">
                                        <a href="<?php echo e(route('admin.sayfalar.create')); ?>" 
                                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                            Yeni Sayfa Ekle
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ai-b2b\resources\views/admin/sayfalar/index.blade.php ENDPATH**/ ?>