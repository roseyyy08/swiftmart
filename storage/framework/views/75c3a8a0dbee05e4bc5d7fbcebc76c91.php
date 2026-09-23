

<?php $__env->startSection('title', 'Kategori'); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-eyebrow">MASTER DATA</div>
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="page-heading">Kategori</h1>
            <p class="page-subtext mb-0">Kelola kategori produk SwiftMart.</p>
        </div>
        <a href="<?php echo e(route('categories.create')); ?>" class="btn-swift-primary">
            <i class="bi bi-plus-lg me-1"></i>Tambah Kategori
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-card-label">Total Kategori</div>
                <div class="stat-card-value"><?php echo e($totalKategori); ?></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-card-label">Produk</div>
                <div class="stat-card-value"><?php echo e($totalProduk); ?></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-card-label">Member</div>
                <div class="stat-card-value"><?php echo e($totalMember); ?></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-card-label">Transaksi Hari Ini</div>
                <div class="stat-card-value"><?php echo e($transaksiHariIni); ?></div>
            </div>
        </div>
    </div>

    <div class="swift-card">
        <table class="swift-table">
            <thead>
                <tr><th>NO</th><th>NAMA</th><th>DESKRIPSI</th><th>AKSI</th></tr>
            </thead>
            <tbody>
                <?php if($categories->count() == 0): ?>
                    <tr><td colspan="4" class="text-center text-muted py-4">Belum ada kategori.</td></tr>
                <?php endif; ?>

                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($category->id); ?></td>
                        <td class="fw-semibold"><?php echo e($category->name); ?></td>
                        <td class="text-muted"><?php echo e($category->description); ?></td>
                        <td>
                            <a href="<?php echo e(route('categories.edit', $category->id)); ?>" class="btn-swift-ghost">Edit</a>
                            <a href="javascript:void(0)" onclick="actionDestroy('<?php echo e(route('categories.destroy', $category->id)); ?>')" class="btn-swift-ghost-danger">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <div class="mt-3"><?php echo $categories->links(); ?></div>
    </div>

    <form action="" id="form-destroy" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function actionDestroy(url) {
            Swal.fire({
                title: 'Apakah anda yakin akan menghapusnya?',
                text: 'Kamu tidak bisa memulihkannya!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                background: '#131f19',
                color: '#e7f1ee'
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
        <script>
            Swal.fire({ title: 'Berhasil!', text: '<?php echo e(Session::get('success')); ?>', icon: 'success', background: '#131f19', color: '#e7f1ee' });
        </script>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\swiftmart\resources\views/categories/index.blade.php ENDPATH**/ ?>