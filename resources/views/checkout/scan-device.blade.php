@extends('layouts.checkout')

@section('content')
<div class="container text-center py-4">
    <h4 class="mb-3"><i class="bi bi-upc-scan"></i> Arahkan kamera ke barcode</h4>

    <div id="scanner-area" style="width:100%; height:65vh; background:#000; border-radius:8px; overflow:hidden;"></div>

    <div id="scan-status" class="alert alert-secondary mt-3">Siap scan...</div>

    <div class="input-group mt-2">
        <input type="text" id="manual-barcode" class="form-control" placeholder="Atau ketik manual...">
        <button class="btn btn-dark" id="btn-manual-scan">Tambah</button>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js"></script>
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
const token = '{{ $token }}';
const statusBox = document.getElementById('scan-status');

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

function sendScan(barcode) {
    fetch('{{ route("checkout.scan") }}', {
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
        if (data.success) {
            showStatus('Berhasil ditambahkan!', true);
            beep(true);
        } else {
            showStatus(data.message || 'Gagal', false);
            beep(false);
        }
    })
    .catch(() => showStatus('Gagal menghubungi server.', false))
    .finally(() => {
        setTimeout(() => {
            isProcessing = false;
            Quagga.start();
            showStatus('Siap scan...', true);
        }, 1200); // <== jeda cooldown biar nggak dobel-dobel
    });
}

// ==================== SCANNER ====================

Quagga.init({
    inputStream: {
        type: 'LiveStream',
        target: document.querySelector('#scanner-area'),
        constraints: {
            width: 1280,
            height: 720,
            facingMode: 'environment' // kamera belakang HP
        },
        // area: { top: "20%", right: "10%", left: "10%", bottom: "20%" }
    },
    locator: { patchSize: 'medium', halfSample: true },
    numOfWorkers: 2,
    frequency: 10,
    decoder: { readers: ['ean_reader', 'ean_8_reader', 'code_128_reader'] },
    locate: true
}, function(err) {
    if (err) {
        console.error('QUAGGA ERROR:', err);
        document.getElementById('scanner-area').innerHTML =
            '<p class="text-white text-center pt-5">Kamera tidak tersedia, gunakan input manual</p>';
        return;
    }
    Quagga.start();
});

// ==================== DETEKSI + LOCK (INI FIX ANTI DOBEL-SCAN) ====================

let isProcessing = false;

Quagga.onDetected(function(result) {
    if (isProcessing) return; // lagi ada proses jalan -> abaikan deteksi lain

    isProcessing = true;
    Quagga.pause(); // stop baca frame sepenuhnya sampai proses ini kelar

    sendScan(result.codeResult.code);
});

// ==================== SCAN MANUAL (di HP juga ada, buat cadangan) ====================

document.getElementById('btn-manual-scan').addEventListener('click', function() {
    const code = document.getElementById('manual-barcode').value.trim();
    if (!code) return;
    sendScan(code);
    document.getElementById('manual-barcode').value = '';
});
</script>
@endsection