

<?php $__env->startSection('content'); ?>
<style>
    /* ==================== VERSI CETAK STRUK ====================
       Disembunyikan di layar (display:none), CUMA muncul pas di-print.
       Lebar 80mm = lebar kertas thermal printer kasir pada umumnya
       (kalau printernya 58mm, tinggal ganti angka width di bawah).
       Kalau nggak ada printer thermal, dialog print browser tetap
       bisa "Save as PDF" atau print ke printer biasa. */
    .print-receipt { display: none; }

    @media print {
        body * { visibility: hidden; }
        .print-receipt, .print-receipt * { visibility: visible; }
        .print-receipt {
            display: block;
            position: absolute;
            top: 0; left: 0;
            width: 80mm;
            padding: 4mm;
            font-family: 'Courier New', monospace;
            font-size: 11px;
            color: #000;
        }
        .print-receipt .center { text-align: center; }
        .print-receipt .line { border-top: 1px dashed #000; margin: 6px 0; }
        .print-receipt table { width: 100%; border-collapse: collapse; }
        .print-receipt td { padding: 2px 0; font-size: 11px; }
        .print-receipt .right { text-align: right; }
        @page { margin: 0; }
    }
</style>

<div class="print-receipt">
    <div class="center">
        <strong>SWIFTMART</strong><br>
        Self-Checkout System
    </div>
    <div class="line"></div>
    <?php echo e($transaction->invoice); ?><br>
    <?php echo e($transaction->created_at->format('d/m/Y H:i')); ?><br>
    Kasir: <?php echo e($transaction->member->name ?? 'Guest'); ?>

    <div class="line"></div>
    <table>
        <?php $__currentLoopData = $transaction->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td colspan="2"><?php echo e($detail->product->name ?? '-'); ?></td>
            </tr>
            <tr>
                <td><?php echo e($detail->quantity); ?> x <?php echo e(number_format($detail->price, 0, ',', '.')); ?></td>
                <td class="right"><?php echo e(number_format($detail->subtotal, 0, ',', '.')); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </table>
    <div class="line"></div>
    <table>
        <tr><td><strong>TOTAL</strong></td><td class="right"><strong>Rp<?php echo e(number_format($transaction->total, 0, ',', '.')); ?></strong></td></tr>
        <tr><td>Metode</td><td class="right"><?php echo e(strtoupper($transaction->payment_method)); ?></td></tr>
        <?php if($transaction->member): ?>
            <tr><td>Poin</td><td class="right"><?php echo e($transaction->member->points); ?></td></tr>
        <?php endif; ?>
    </table>
    <div class="line"></div>
    <div class="center">Terima kasih sudah belanja!</div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-body text-center">
                    <div class="brand-logo fs-4 mb-3">Swift<span class="accent">Mart</span></div>
                    <h4 class="text-success"><i class="bi bi-check-circle-fill"></i> Pembayaran Berhasil</h4>
                    <p class="text-muted">Terima kasih sudah belanja di SwiftMart</p>

                    <hr>

                    <div class="text-start small">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">No. Invoice</span>
                            <span><?php echo e($transaction->invoice); ?></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Tanggal</span>
                            <span><?php echo e($transaction->created_at->format('d/m/Y H:i')); ?></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Member</span>
                            <span><?php echo e($transaction->member->name ?? 'Guest'); ?></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Metode Bayar</span>
                            <span><?php echo e(strtoupper($transaction->payment_method)); ?></span>
                        </div>
                    </div>

                    <hr>

                    <table class="table table-sm">
                        <?php $__currentLoopData = $transaction->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($detail->product->name ?? '-'); ?></td>
                                <td class="text-center text-muted"><?php echo e($detail->quantity); ?>x</td>
                                <td class="text-end">Rp<?php echo e(number_format($detail->subtotal, 0, ',', '.')); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </table>

                    <hr>

                    <div class="d-flex justify-content-between fs-5 fw-bold">
                        <span>Total</span>
                        <span>Rp<?php echo e(number_format($transaction->total, 0, ',', '.')); ?></span>
                    </div>

                    <?php if($transaction->member): ?>
                        <div class="alert alert-info mt-3 mb-0">
                            Poin kamu sekarang: <strong><?php echo e($transaction->member->points); ?></strong>
                        </div>
                    <?php endif; ?>

                    <button type="button" class="btn btn-outline-primary w-100 mt-4" onclick="window.print()">
                        <i class="bi bi-printer-fill me-1"></i>Cetak Struk
                    </button>

                    <a href="<?php echo e(route('checkout.index')); ?>" class="btn btn-success w-100 mt-2">
                        Selesai - Belanja Lagi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.checkout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\swiftmart\resources\views/checkout/receipt.blade.php ENDPATH**/ ?>