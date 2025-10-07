

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold">Özellik Yönetimi</h1>
    <a href="<?php echo e(route('admin.ozellik.create')); ?>" class="px-3 py-2 bg-blue-600 text-white rounded">Yeni Özellik</a>
  </div>
  <form class="mb-3 grid grid-cols-1 md:grid-cols-3 gap-2">
    <input type="text" name="search" placeholder="Ad veya Değer ara" value="<?php echo e(request('search')); ?>" class="border rounded px-2 py-1">
    <select name="urun_id" class="border rounded px-2 py-1">
      <option value="">Tüm Ürünler</option>
      <?php $__currentLoopData = $urunler; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($u->id); ?>" <?php if(request('urun_id')==$u->id): echo 'selected'; endif; ?>><?php echo e($u->ad); ?></option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <button class="px-3 py-1 border rounded">Filtrele</button>
  </form>

  <form method="POST" action="<?php echo e(route('admin.ozellik.bulk-delete')); ?>">
    <?php echo csrf_field(); ?>
    <div class="bg-white shadow rounded overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-3 py-2"><input type="checkbox" x-data @click="$root.querySelectorAll('input[name=ids\\[\\]]').forEach(cb=>cb.checked=$event.target.checked)"></th>
            <th class="px-3 py-2 text-left">Ürün</th>
            <th class="px-3 py-2 text-left">Ad</th>
            <th class="px-3 py-2 text-left">Değer</th>
            <th class="px-3 py-2 text-left">Birim</th>
            <th class="px-3 py-2 text-right">Sıra</th>
            <th class="px-3 py-2 text-right"></th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $ozellikler; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <tr class="border-t">
            <td class="px-3 py-2"><input type="checkbox" name="ids[]" value="<?php echo e($o->id); ?>"></td>
            <td class="px-3 py-2"><?php echo e($o->urun?->ad ?? '-'); ?></td>
            <td class="px-3 py-2 font-medium"><?php echo e($o->ad); ?></td>
            <td class="px-3 py-2"><?php echo e($o->deger); ?></td>
            <td class="px-3 py-2"><?php echo e($o->birim); ?></td>
            <td class="px-3 py-2 text-right"><?php echo e($o->sira); ?></td>
            <td class="px-3 py-2 text-right">
              <a href="<?php echo e(route('admin.ozellik.edit', $o)); ?>" class="px-3 py-1 border rounded">Düzenle</a>
              <form action="<?php echo e(route('admin.ozellik.destroy', $o)); ?>" method="POST" class="inline" onsubmit="return confirm('Silinsin mi?')">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button class="px-3 py-1 border rounded text-red-600">Sil</button>
              </form>
            </td>
          </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <tr><td colspan="7" class="px-3 py-6 text-center text-gray-500">Kayıt bulunamadı.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
      <div class="p-3"><?php echo e($ozellikler->links()); ?></div>
    </div>
    <div class="mt-3">
      <button class="px-3 py-2 border rounded" onclick="return confirm('Seçili özellikler silinsin mi?')">Seçiliyi Sil</button>
    </div>
  </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ai-b2b\resources\views/admin/ozellik/index.blade.php ENDPATH**/ ?>