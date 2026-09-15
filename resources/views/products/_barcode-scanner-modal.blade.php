{{--
    Modal "Scan Barcode" buat form Produk (admin).
    Dipakai bareng di create.blade.php & edit.blade.php lewat @include,
    biar logic-nya nggak keduplikasi/ke-fork jadi 2 versi beda.

    Alasan fitur ini ada: di dunia nyata, nomor barcode produk itu SUDAH
    ditentukan pabrik (GTIN/EAN-13) dan tercetak di kemasan - admin toko
    nggak pernah ngetik manual, tinggal SCAN barcode yang ada di dus
    barang baru. Form ini pakai kamera device yang lagi buka halaman ini
    (laptop/HP admin), teknik yang sama kayak QuaggaJS di Self-Checkout.
--}}
<button type="button" class="btn-swift-ghost" data-bs-toggle="modal" data-bs-target="#barcodeScanModal">
    <i class="bi bi-upc-scan"></i> Scan
</button>

<div class="modal fade" id="barcodeScanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: var(--card-bg); border: 1px solid var(--card-border); color: var(--text);">
            <div class="modal-header" style="border-color: var(--card-border);">
                <h5 class="modal-title"><i class="bi bi-upc-scan"></i> Scan Barcode Produk</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p class="text-muted small mb-2">Arahkan kamera ke barcode di kemasan produk</p>
                <div id="product-scanner-area" style="width:100%; height:280px; background:#000; border-radius:10px; overflow:hidden;"></div>
                <div id="product-scan-status" class="mt-2 small text-muted">Menyalakan kamera...</div>
            </div>
        </div>
    </div>
</div>

@once
    {{-- @once supaya kalau ada 2 @include di halaman yang sama, script/library-nya cuma dimuat sekali --}}
    <script src="https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js"></script>
    <script>
    (function () {
        const modalEl = document.getElementById('barcodeScanModal');
        const statusEl = document.getElementById('product-scan-status');
        let quaggaRunning = false;

        // Init kamera pas modal BENERAN kebuka (bukan pas halaman diload),
        // supaya nggak minta izin kamera kalau admin nggak jadi pakai fitur ini.
        modalEl.addEventListener('shown.bs.modal', function () {
            statusEl.textContent = 'Menyalakan kamera...';

            Quagga.init({
                inputStream: {
                    type: 'LiveStream',
                    target: document.querySelector('#product-scanner-area'),
                    constraints: { width: 640, height: 480, facingMode: 'environment' }
                },
                locator: { patchSize: 'medium', halfSample: true },
                numOfWorkers: 2,
                frequency: 10,
                decoder: { readers: ['ean_reader', 'ean_8_reader', 'code_128_reader'] },
                locate: true
            }, function (err) {
                if (err) {
                    console.error('QUAGGA ERROR:', err);
                    statusEl.textContent = 'Kamera tidak tersedia di device ini. Ketik manual di kolom Barcode.';
                    return;
                }
                Quagga.start();
                quaggaRunning = true;
                statusEl.textContent = 'Arahkan kamera ke barcode...';
            });
        });

        // Matiin kamera pas modal ditutup (baik berhasil scan ataupun dibatalkan admin),
        // biar kamera nggak nyala terus-terusan di background.
        modalEl.addEventListener('hidden.bs.modal', function () {
            if (quaggaRunning) {
                Quagga.stop();
                quaggaRunning = false;
            }
        });

        Quagga.onDetected(function (result) {
            if (!quaggaRunning) return; // udah kedeteksi/lagi nutup modal, abaikan sisa frame
            quaggaRunning = false;

            const code = result.codeResult.code;
            document.getElementById('barcode').value = code;
            statusEl.textContent = 'Terdeteksi: ' + code;

            Quagga.stop();

            setTimeout(function () {
                bootstrap.Modal.getInstance(modalEl).hide();
            }, 400);
        });
    })();
    </script>
@endonce