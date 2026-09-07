

<?php $__env->startSection('content'); ?>
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

                    <a href="<?php echo e(route('checkout.index')); ?>" class="btn btn-success w-100 mt-4">
                        Selesai - Belanja Lagi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.checkout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\swiftmart\resources\views/checkout/receipt.blade.php ENDPATH**/ ?>