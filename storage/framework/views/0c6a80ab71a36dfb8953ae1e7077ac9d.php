<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-eyebrow">RINGKASAN</div>
    <h1 class="page-heading">Selamat datang kembali, <?php echo e(explode(' ', Auth::user()->name)[0]); ?></h1>
    <p class="page-subtext">Berikut performa toko SwiftMart hari ini.</p>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-card-label">Pendapatan Hari Ini</div>
                    <div class="stat-card-value">Rp<?php echo e(number_format($pendapatanHariIni, 0, ',', '.')); ?></div>
                </div>
                <div class="stat-card-icon"><i class="bi bi-currency-dollar"></i></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-card-label">Transaksi Hari Ini</div>
                    <div class="stat-card-value"><?php echo e($transaksiHariIni); ?></div>
                </div>
                <div class="stat-card-icon"><i class="bi bi-receipt"></i></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-card-label">Total Produk</div>
                    <div class="stat-card-value"><?php echo e($totalProduk); ?></div>
                </div>
                <div class="stat-card-icon"><i class="bi bi-box-seam"></i></div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-card-label">Stok Menipis</div>
                    <div class="stat-card-value"><?php echo e($stokMenipis); ?></div>
                </div>
                <div class="stat-card-icon" style="background:rgba(217,119,87,.15); color:var(--danger);"><i class="bi bi-exclamation-triangle"></i></div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-7">
            <div class="swift-card">
                <div class="fw-bold mb-1">Tren Penjualan</div>
                <div class="text-muted small mb-4">7 hari terakhir</div>

                <div class="d-flex align-items-end justify-content-between" style="height:200px;">
                    <?php $__currentLoopData = $trenPenjualan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hari): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $heightPct = $hari['total'] > 0 ? max(8, ($hari['total'] / $maxTren) * 100) : 4; ?>
                        <div class="text-center" style="width: 12%;">
                            <div style="height:160px; display:flex; align-items:flex-end;">
                                <div style="width:100%; height:<?php echo e($heightPct); ?>%; background:linear-gradient(to top, var(--teal), var(--teal-light)); border-radius:6px;"
                                     title="Rp<?php echo e(number_format($hari['total'], 0, ',', '.')); ?>"></div>
                            </div>
                            <div class="text-muted small mt-2"><?php echo e($hari['label']); ?></div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="swift-card">
                <div class="fw-bold mb-1">Produk Terlaris</div>
                <div class="text-muted small mb-3">Berdasarkan jumlah terjual</div>

                <?php $__empty_1 = true; $__currentLoopData = $produkTerlaris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="d-flex align-items-center justify-content-between py-2 <?php echo e(!$loop->last ? 'border-bottom' : ''); ?>" style="border-color: var(--card-border) !important;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="stat-card-icon" style="width:28px; height:28px; font-size:.8rem;"><?php echo e($i + 1); ?></div>
                            <div>
                                <div class="fw-semibold"><?php echo e($item->product->name ?? 'Produk dihapus'); ?></div>
                                <div class="text-muted small"><?php echo e($item->total_qty); ?> terjual</div>
                            </div>
                        </div>
                        <div class="fw-bold">Rp<?php echo e(number_format($item->total_omzet, 0, ',', '.')); ?></div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-muted small">Belum ada data penjualan.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\swiftmart\resources\views/dashboard.blade.php ENDPATH**/ ?>