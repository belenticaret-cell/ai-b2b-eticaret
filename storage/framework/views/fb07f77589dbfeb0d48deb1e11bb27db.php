

<?php $__env->startSection('title', 'Profilim'); ?>
<?php $__env->startSection('page-title', 'Profilim'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-xl">
    <div class="bg-white border rounded-xl p-6">
        <form method="POST" action="<?php echo e(route('bayi.profil.guncelle')); ?>">
            <?php echo csrf_field(); ?>
            <div class="grid gap-4">
                <div>
                    <label class="block text-sm font-medium">Bayi Adı</label>
                    <input type="text" name="ad" value="<?php echo e(old('ad', $bayi->ad ?? '')); ?>" class="mt-1 w-full border rounded px-3 py-2" />
                </div>
                <div>
                    <label class="block text-sm font-medium">Telefon</label>
                    <input type="text" name="telefon" value="<?php echo e(old('telefon', $bayi->telefon ?? '')); ?>" class="mt-1 w-full border rounded px-3 py-2" />
                </div>
                <div>
                    <label class="block text-sm font-medium">Adres</label>
                    <textarea name="adres" rows="3" class="mt-1 w-full border rounded px-3 py-2"><?php echo e(old('adres', $bayi->adres ?? '')); ?></textarea>
                </div>
            </div>
            <div class="mt-6">
                <button class="bg-blue-600 text-white px-4 py-2 rounded">Kaydet</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.bayi.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ai-b2b\resources\views/bayi/profil.blade.php ENDPATH**/ ?>