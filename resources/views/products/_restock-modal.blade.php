{{--
    Modal "Terima Stok Masuk" - buat kasus dus barang baru datang ke toko.
    BEDA sama modal scan di form Tambah/Edit Produk:
    - Modal ini nyari produk yang SUDAH ADA lalu NAMBAH stoknya (increment)
    - Modal satunya cuma ngisi field barcode di form Tambah Produk BARU

    Alurnya: scan sekali (barcode-nya sama untuk semua unit di 1 dus) ->
    sistem ketemu produknya -> admin ketik jumlah yang masuk dari dus ->
    stok nambah otomatis, nggak perlu itung manual.
--}}
<button type="button" class="btn-swift-outline-gold" data-bs-toggle="modal" data-bs-target="#restockModal">
    <i class="bi bi-box-seam"></i> Terima Stok Masuk
</button>

<div class="modal fade" id="restockModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: var(--card-bg); border: 1px solid var(--card-border); color: var(--text);">
            <div class="modal-header" style="border-color: var(--card-border);">
                <h5 class="modal-title"><i class="bi bi-box-seam"></i> Terima Stok Masuk</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                {{-- STATE 1: scan kamera --}}
                <div id="restock-step-scan">
                    <p class="text-muted small mb-2 text-center">
                        Scan barcode di kemasan (cukup 1 kali, semua unit di dus yang sama barcode-nya sama)
                    </p>
                    <div id="restock-scanner-area" style="width:100%; height:240px; background:#000; border-radius:10px; overflow:hidden;"></div>
                    <div id="restock-scan-status" class="mt-2 small text-muted text-center">Menyalakan kamera...</div>

                    <div class="input-group mt-3">
                        <input type="text" id="restock-manual-barcode" class="form-control" placeholder="Atau ketik barcode manual...">
                        <button class="btn btn-dark" id="restock-btn-manual" type="button">Cari</button>
                    </div>
                </div>

                {{-- STATE 2: produk ketemu, minta jumlah --}}
                <div id="restock-step-found" class="d-none text-center">
                    <i class="bi bi-check-circle-fill text-success fs-1"></i>
                    <h5 class="mt-2 mb-0" id="restock-product-name">-</h5>
                    <p class="text-muted small">Stok sekarang: <span id="restock-current-stock">-</span></p>

                    <label class="form-label text-start d-block mt-3">Jumlah yang masuk (dari dus ini)</label>
                    <input type="number" id="restock-qty" class="form-control text-center" min="1" placeholder="Misal: 24">

                    <button type="button" class="btn-swift-primary w-100 mt-3" id="restock-btn-confirm">
                        <i class="bi bi-plus-circle me-1"></i>Tambah ke Stok
                    </button>
                    <button type="button" class="btn-swift-ghost w-100 mt-2" id="restock-btn-scan-again">
                        Scan Produk Lain
                    </button>
                </div>

                {{-- STATE 3: barcode nggak ketemu sama sekali --}}
                <div id="restock-step-notfound" class="d-none text-center">
                    <i class="bi bi-question-circle-fill text-warning fs-1"></i>
                    <p class="mt-2">Barcode <strong id="restock-notfound-code"></strong> belum terdaftar.</p>
                    <p class="text-muted small">Ini kemungkinan produk baru yang belum pernah dijual sebelumnya, bukan restock.</p>
                    <a href="#" id="restock-link-create" class="btn-swift-primary w-100">Tambah sebagai Produk Baru</a>
                    <button type="button" class="btn-swift-ghost w-100 mt-2" id="restock-btn-scan-again-2">
                        Scan Ulang
                    </button>
                </div>

                {{-- STATE 4: sukses --}}
                <div id="restock-step-success" class="d-none text-center">
                    <i class="bi bi-check-circle-fill text-success fs-1"></i>
                    <p class="mt-2"><strong id="restock-success-name"></strong></p>
                    <p class="text-muted">Stok baru: <strong id="restock-success-stock"></strong></p>
                    <button type="button" class="btn-swift-primary w-100" id="restock-btn-next">
                        Scan Produk Lain
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

@once
    <script src="https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js"></script>
@endonce
<script>
(function () {
    const modalEl = document.getElementById('restockModal');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    const stepScan = document.getElementById('restock-step-scan');
    const stepFound = document.getElementById('restock-step-found');
    const stepNotfound = document.getElementById('restock-step-notfound');
    const stepSuccess = document.getElementById('restock-step-success');
    const statusEl = document.getElementById('restock-scan-status');

    let quaggaRunning = false;
    let currentProduct = null;

    function showStep(step) {
        [stepScan, stepFound, stepNotfound, stepSuccess].forEach(el => el.classList.add('d-none'));
        step.classList.remove('d-none');
    }

    function startCamera() {
        statusEl.textContent = 'Menyalakan kamera...';
        Quagga.init({
            inputStream: {
                type: 'LiveStream',
                target: document.querySelector('#restock-scanner-area'),
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
                statusEl.textContent = 'Kamera tidak tersedia, pakai input manual di bawah.';
                return;
            }
            Quagga.start();
            quaggaRunning = true;
            statusEl.textContent = 'Arahkan kamera ke barcode...';
        });
    }

    function stopCamera() {
        if (quaggaRunning) {
            Quagga.stop();
            quaggaRunning = false;
        }
    }

    function lookupBarcode(barcode) {
        stopCamera();
        statusEl.textContent = 'Mencari produk...';

        fetch('{{ route("products.restock-lookup") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ barcode: barcode })
        })
        .then(res => res.json())
        .then(data => {
            if (data.found) {
                currentProduct = data.product;
                document.getElementById('restock-product-name').textContent = data.product.name;
                document.getElementById('restock-current-stock').textContent = data.product.stock;
                document.getElementById('restock-qty').value = '';
                showStep(stepFound);
                document.getElementById('restock-qty').focus();
            } else {
                document.getElementById('restock-notfound-code').textContent = barcode;
                document.getElementById('restock-link-create').href = '{{ route("products.create") }}?barcode=' + encodeURIComponent(barcode);
                showStep(stepNotfound);
            }
        })
        .catch(() => {
            statusEl.textContent = 'Gagal menghubungi server.';
        });
    }

    modalEl.addEventListener('shown.bs.modal', function () {
        showStep(stepScan);
        startCamera();
    });

    modalEl.addEventListener('hidden.bs.modal', function () {
        stopCamera();
        currentProduct = null;
    });

    Quagga.onDetected(function (result) {
        if (!quaggaRunning) return;
        lookupBarcode(result.codeResult.code);
    });

    document.getElementById('restock-btn-manual').addEventListener('click', function () {
        const code = document.getElementById('restock-manual-barcode').value.trim();
        if (!code) return;
        lookupBarcode(code);
    });

    document.getElementById('restock-btn-scan-again').addEventListener('click', function () {
        showStep(stepScan);
        startCamera();
    });
    document.getElementById('restock-btn-scan-again-2').addEventListener('click', function () {
        showStep(stepScan);
        startCamera();
    });
    document.getElementById('restock-btn-next').addEventListener('click', function () {
        showStep(stepScan);
        startCamera();
    });

    document.getElementById('restock-btn-confirm').addEventListener('click', function () {
        const qty = parseInt(document.getElementById('restock-qty').value, 10);
        if (!qty || qty < 1) {
            alert('Isi jumlah yang masuk dulu (minimal 1).');
            return;
        }

        fetch('{{ route("products.restock-confirm") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ product_id: currentProduct.id, qty: qty })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('restock-success-name').textContent = data.name;
                document.getElementById('restock-success-stock').textContent = data.new_stock;
                showStep(stepSuccess);
            } else {
                alert('Gagal menambah stok.');
            }
        });
    });
})();
</script>