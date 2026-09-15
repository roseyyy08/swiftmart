

<?php $__env->startSection('title', 'Produk'); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-eyebrow">MASTER DATA</div>
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="page-heading">Produk</h1>
            <p class="page-subtext mb-0">Kelola katalog produk SwiftMart.</p>
        </div>
        <div class="d-flex gap-2">
            <?php echo $__env->make('products._restock-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <a href="<?php echo e(route('products.create')); ?>" class="btn-swift-primary">
                <i class="bi bi-plus-lg me-1"></i>Tambah Produk
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4">
            <div class="stat-card"><div class="stat-card-label">Total Produk</div><div class="stat-card-value"><?php echo e($totalProduk); ?></div></div>
        </div>
        <div class="col-6 col-md-4">
            <div class="stat-card"><div class="stat-card-label">Stok Menipis</div><div class="stat-card-value"><?php echo e($stokMenipis); ?></div></div>
        </div>
        <div class="col-6 col-md-4">
            <div class="stat-card"><div class="stat-card-label">Nonaktif</div><div class="stat-card-value"><?php echo e($nonaktif); ?></div></div>
        </div>
    </div>

    <form method="GET" class="d-flex gap-2 mb-3 flex-wrap">
        <div class="flex-grow-1" style="min-width:220px;">
            <input type="text" name="search" class="form-control" placeholder="Cari nama atau barcode..." value="<?php echo e(request('search')); ?>">
        </div>
        <select name="category_id" class="form-select" style="max-width:180px;" onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($cat->id); ?>" <?php echo e(request('category_id') == $cat->id ? 'selected' : ''); ?>><?php echo e($cat->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <select name="stock_filter" class="form-select" style="max-width:180px;" onchange="this.form.submit()">
            <option value="">Semua Stok</option>
            <option value="low" <?php echo e(request('stock_filter') == 'low' ? 'selected' : ''); ?>>Stok Menipis</option>
            <option value="empty" <?php echo e(request('stock_filter') == 'empty' ? 'selected' : ''); ?>>Stok Habis</option>
        </select>
        <button type="submit" class="btn-swift-primary"><i class="bi bi-search"></i></button>
    </form>

    <div class="swift-card">
        <table class="swift-table">
            <thead>
                <tr><th>PRODUK</th><th>KATEGORI</th><th>BARCODE</th><th>HARGA</th><th>STOK</th><th>STATUS</th><th>AKSI</th></tr>
            </thead>
            <tbody>
                <?php if($products->count() == 0): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">Produk tidak ditemukan.</td></tr>
                <?php endif; ?>

                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="d-flex align-items-center gap-2">
                            <?php if($product->image): ?>
                                <img src="<?php echo e(asset('storage/'.$product->image)); ?>" width="36" height="36" style="object-fit:cover; border-radius:6px;">
                            <?php else: ?>
                                <div style="width:36px; height:36px; background:var(--bg-2); border-radius:6px;"></div>
                            <?php endif; ?>
                            <span class="fw-semibold"><?php echo e($product->name); ?></span>
                        </td>
                        <td class="text-muted"><?php echo e($product->category->name ?? '-'); ?></td>
                        <td class="text-muted"><?php echo e($product->barcode); ?></td>
                        <td>Rp<?php echo e(number_format($product->price, 0, ',', '.')); ?></td>
                        <td>
                            <span class="pill <?php echo e($product->stock <= 5 ? 'pill-danger' : 'pill-success'); ?>"><?php echo e($product->stock); ?></span>
                        </td>
                        <td>
                            <span class="pill <?php echo e($product->is_active ? 'pill-success' : 'pill-neutral'); ?>"><?php echo e($product->is_active ? 'Aktif' : 'Nonaktif'); ?></span>
                        </td>
                        <td>
                            <a href="<?php echo e(route('products.edit', $product->id)); ?>" class="btn-swift-ghost">Edit</a>
                            <a href="javascript:void(0)" onclick="actionDestroy('<?php echo e(route('products.destroy', $product->id)); ?>')" class="btn-swift-ghost-danger">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <div class="mt-3"><?php echo $products->links(); ?></div>
    </div>

    <form action="" id="form-destroy" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function actionDestroy(url) {
            Swal.fire({
                title: 'Apakah anda yakin akan menghapusnya?', text: 'Kamu tidak bisa memulihkannya!', icon: 'warning',
                showCancelButton: true, confirmButtonText: 'Ya, hapus!', cancelButtonText: 'Batal',
                background: '#131f19', color: '#e7f1ee'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('form-destroy');
                    form.action = url;
                    form.submit();
                }
            });
        }
    </script>

    <?php if(Session::has('success')): ?>
        <script>Swal.fire({ title: 'Berhasil!', text: '<?php echo e(Session::get('success')); ?>', icon: 'success', background: '#131f19', color: '#e7f1ee' });</script>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\swiftmart\resources\views/products/index.blade.php ENDPATH**/ ?>