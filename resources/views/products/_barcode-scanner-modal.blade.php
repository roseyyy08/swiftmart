
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
                <p class="mb-1">Buka alamat ini di browser HP kamu:</p>
                <p class="fs-6 fw-bold" style="word-break: break-all;">{{ url('/admin-scan/' . config('services.kiosk_token')) }}</p>
                <p class="text-muted small">Pastikan HP terhubung ke WiFi yang sama dengan laptop ini.</p>
                <hr style="border-color: var(--card-border);">
                <div id="barcode-scan-status" class="alert alert-secondary">Menunggu scan dari HP...</div>
            </div>
        </div> 
    </div>
</div>

<script>
(function () {
    const modalEl = document.getElementById('barcodeScanModal');
    const statusEl = document.getElementById('barcode-scan-status');
    const adminScanToken = '{{ config('services.kiosk_token') }}';
    let pollTimer = null;
    let baselineTs = 0; 

    function poll() {
        fetch('{{ url('/admin-scan') }}/' + adminScanToken + '/poll')
            .then(res => res.json())
            .then(data => {
                if (data.barcode && data.ts > baselineTs) {
                    document.getElementById('barcode').value = data.barcode;
                    statusEl.textContent = 'Terisi otomatis: ' + data.barcode;
                    statusEl.className = 'alert alert-success';
                    baselineTs = data.ts;

                    setTimeout(function () {
                        bootstrap.Modal.getInstance(modalEl).hide();
                    }, 600);
                }
            });
    }

    modalEl.addEventListener('shown.bs.modal', function () {
        statusEl.textContent = 'Menunggu scan dari HP...';
        statusEl.className = 'alert alert-secondary';

        fetch('{{ url('/admin-scan') }}/' + adminScanToken + '/poll')
            .then(res => res.json())
            .then(data => {
                baselineTs = data.ts || 0;
                pollTimer = setInterval(poll, 1500);
            });
    });

    modalEl.addEventListener('hidden.bs.modal', function () {
        if (pollTimer) clearInterval(pollTimer);
    });
})();
</script>