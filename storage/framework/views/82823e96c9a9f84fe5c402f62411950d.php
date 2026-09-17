

<?php $__env->startSection('content'); ?>
<div class="container text-center py-4">
    <div class="brand-logo fs-4 mb-3">Swift<span class="accent">Mart</span></div>
    <h5 class="mb-1"><i class="bi bi-upc-scan"></i> Scan Barcode (Admin)</h5>
    <p class="text-muted small mb-3">Hasil scan bakal otomatis muncul di layar laptop</p>

    <div id="scanner-area" style="width:100%; height:55vh; background:#000; border-radius:12px; overflow:hidden; border: 1px solid var(--card-border);"></div>

    <div id="scan-status" class="alert alert-secondary mt-3">Siap scan...</div>

    <div class="input-group mt-2">
        <input type="text" id="manual-barcode" class="form-control" placeholder="Atau ketik manual...">
        <button class="btn btn-dark" id="btn-manual-scan">Kirim</button>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js"></script>
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
const token = '<?php echo e($token); ?>';
const statusBox = document.getElementById('scan-status');
let isProcessing = false;

function showStatus(msg, ok) {
    statusBox.textContent = msg;
    statusBox.className = 'alert mt-3 ' + (ok ? 'alert-success' : 'alert-danger');
}

function beep(success) {
    if (navigator.vibrate) navigator.vibrate(success ? 80 : [50, 50, 50]);
    try {
        const ctx = new (window.AudioContext || window.webkitAudioContext)();
        const osc = ctx.createOscillator();
        osc.frequency.value = success ? 880 : 220;
        osc.connect(ctx.destination);
        osc.start();
        osc.stop(ctx.currentTime + 0.12);
    } catch (e) {}
}

// Kirim hasil scan ke server. INI BEDANYA sama scan-device Self-Checkout:
// di sini cuma "nitip" barcode-nya ke cache (lewat endpoint push), nggak
// langsung ngapa-ngapain ke keranjang/produk. Laptop yang nentuin nanti
// barcode ini dipakai buat apa (isi field / cari produk buat restock).
function sendScan(barcode) {
    fetch('<?php echo e(route("admin-scan.push")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ barcode: barcode, token: token })
    })
    .then(res => res.json())
    .then(data => {
        showStatus('Terkirim ke laptop: ' + barcode, true);
        beep(true);
    })
    .catch(() => {
        showStatus('Gagal menghubungi server.', false);
        beep(false);
    })
    .finally(() => {
        setTimeout(() => {
            isProcessing = false;
            Quagga.start();
            showStatus('Siap scan...', true);
        }, 1200); // <== jeda cooldown, sama kayak di scan-device checkout
    });
}

Quagga.init({
    inputStream: {
        type: 'LiveStream',
        target: document.querySelector('#scanner-area'),
        constraints: { width: 1280, height: 720, facingMode: 'environment' }
    },
    locator: { patchSize: 'medium', halfSample: true },
    numOfWorkers: 2,
    frequency: 10,
    decoder: { readers: ['ean_reader', 'ean_8_reader', 'code_128_reader'] },
    locate: true
}, function (err) {
    if (err) {
        console.error('QUAGGA ERROR:', err);
        document.getElementById('scanner-area').innerHTML =
            '<p class="text-white text-center pt-5">Kamera tidak tersedia, gunakan input manual</p>';
        return;
    }
    Quagga.start();
});

Quagga.onDetected(function (result) {
    if (isProcessing) return;
    isProcessing = true;
    Quagga.pause();
    sendScan(result.codeResult.code);
});

document.getElementById('btn-manual-scan').addEventListener('click', function () {
    const code = document.getElementById('manual-barcode').value.trim();
    if (!code) return;
    sendScan(code);
    document.getElementById('manual-barcode').value = '';
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.checkout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\swiftmart\resources\views/admin-scan/page.blade.php ENDPATH**/ ?>