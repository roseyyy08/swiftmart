

<?php $__env->startSection('title', 'Laporan'); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-eyebrow">LAPORAN</div>
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="page-heading">Laporan Penjualan</h1>
            <p class="page-subtext mb-0">Rekap transaksi berdasarkan periode, metode pembayaran, dan tipe pelanggan.</p>
        </div>
        <a href="<?php echo e(route('reports.export-pdf', request()->query())); ?>" target="_blank" class="btn-swift-primary">
            <i class="bi bi-download me-1"></i>Export PDF
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4">
            <div class="stat-card"><div class="stat-card-label">Total Pendapatan</div><div class="stat-card-value">Rp<?php echo e(number_format($totalPendapatan, 0, ',', '.')); ?></div></div>
        </div>
        <div class="col-6 col-md-4">
            <div class="stat-card"><div class="stat-card-label">Jumlah Transaksi</div><div class="stat-card-value"><?php echo e($totalTransaksi); ?></div></div>
        </div>
        <div class="col-6 col-md-4">
            <div class="stat-card"><div class="stat-card-label">Rata-rata / Transaksi</div><div class="stat-card-value">Rp<?php echo e(number_format($rataRata, 0, ',', '.')); ?></div></div>
        </div>
    </div>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-3">
            <label class="form-label">Dari Tanggal</label>
            <input type="date" name="date_from" class="form-control" value="<?php echo e(request('date_from')); ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Sampai Tanggal</label>
            <input type="date" name="date_to" class="form-control" value="<?php echo e(request('date_to')); ?>">
        </div>
        <div class="col-md-2">
            <label class="form-label">Metode Bayar</label>
            <select name="payment_method" class="form-select">
                <option value="">Semua</option>
                <option value="cash" <?php echo e(request('payment_method') == 'cash' ? 'selected' : ''); ?>>Tunai</option>
                <option value="debit" <?php echo e(request('payment_method') == 'debit' ? 'selected' : ''); ?>>Debit</option>
                <option value="qris" <?php echo e(request('payment_method') == 'qris' ? 'selected' : ''); ?>>QRIS</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Tipe Pelanggan</label>
            <select name="tipe_pelanggan" class="form-select">
                <option value="">Semua</option>
                <option value="member" <?php echo e(request('tipe_pelanggan') == 'member' ? 'selected' : ''); ?>>Member</option>
                <option value="guest" <?php echo e(request('tipe_pelanggan') == 'guest' ? 'selected' : ''); ?>>Guest</option>
            </select>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn-swift-primary w-100">Terapkan Filter</button>
        </div>
    </form>

    <div class="swift-card">
        <table class="swift-table">
            <thead><tr><th>INVOICE</th><th>TANGGAL</th><th>PELANGGAN</th><th>ITEM</th><th>METODE</th><th>TOTAL</th></tr></thead>
            <tbody>
                <?php if($transactions->count() == 0): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada transaksi ditemukan.</td></tr>
                <?php endif; ?>

                <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="text-muted"><?php echo e($trx->invoice); ?></td>
                        <td class="text-muted"><?php echo e($trx->created_at->format('d M Y, H:i')); ?></td>
                        <td><span class="pill pill-neutral"><?php echo e($trx->member->name ?? 'guest'); ?></span></td>
                        <td class="text-muted"><?php echo e($trx->details->sum('quantity')); ?> produk</td>
                        <td>
                            <span class="pill <?php echo e($trx->payment_method == 'qris' ? 'pill-success' : 'pill-neutral'); ?>"><?php echo e(strtoupper($trx->payment_method)); ?></span>
                        </td>
                        <td class="fw-bold">Rp<?php echo e(number_format($trx->total, 0, ',', '.')); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <div class="mt-3"><?php echo $transactions->links(); ?></div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\swiftmart\resources\views/reports/index.blade.php ENDPATH**/ ?>