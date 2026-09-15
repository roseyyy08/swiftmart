@extends('layouts.checkout')

@section('content')
<div class="container-fluid py-4">
    <div class="text-center mb-4">
        <div class="brand-logo fs-3">Swift<span class="accent">Mart</span></div>
        <small class="text-muted"><i class="bi bi-cart-check"></i> Self-Checkout</small>
    </div>

    <div class="row">
        {{-- KIRI: Pairing HP + input manual --}}
        <div class="col-md-7">
            <div class="card mb-3">
                <div class="card-body text-center">
                    <h5 class="card-title"><i class="bi bi-phone"></i> Scan pakai HP</h5>
                    <p class="mb-1">Buka alamat ini di browser HP kamu:</p>
                    <p class="fs-5 fw-bold">{{ url('/checkout/pair/' . $token) }}</p>
                    <p class="text-muted small">Pastikan HP terhubung ke WiFi yang sama.</p>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-upc-scan"></i> Atau Input Manual</h5>
                    <div class="input-group">
                        <input type="text" id="manual-barcode" class="form-control"
                            placeholder="Ketik barcode manual...">
                        <button class="btn btn-dark" id="btn-manual-scan">Tambah</button>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-person-badge"></i> Member (Opsional)
                    </h5>

                    <div id="member-form" class="input-group">
                        <input type="text" id="member-phone" class="form-control"
                            placeholder="Nomor HP Member">
                        <button class="btn btn-outline-primary" id="btn-check-member">Cek</button>
                    </div>

                    <div id="member-info" class="alert alert-success mt-2 d-none"></div>
                </div>
            </div>
        </div>

        {{-- KANAN: Keranjang --}}
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-cart3"></i> Keranjang Belanja
                    </h5>

                    <div id="cart-list">
                        <p class="text-muted text-center py-4" id="cart-empty">
                            Belum ada produk di keranjang
                        </p>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between fs-5 fw-bold">
                        <span>Total</span>
                        <span id="cart-total">Rp0</span>
                    </div>

                    <button class="btn btn-success w-100 mt-3"
                        id="btn-checkout"
                        disabled
                        data-bs-toggle="modal"
                        data-bs-target="#paymentModal">
                        Bayar Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Pembayaran --}}
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Pilih Metode Pembayaran</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="btn-group w-100 mb-3" role="group">

                    <input type="radio" class="btn-check"
                        name="payment_method" id="pay-cash"
                        value="cash" autocomplete="off" checked>

                    <label class="btn btn-outline-dark" for="pay-cash">
                        Tunai
                    </label>

                    <input type="radio" class="btn-check"
                        name="payment_method" id="pay-debit"
                        value="debit" autocomplete="off">

                    <label class="btn btn-outline-dark" for="pay-debit">
                        Debit
                    </label>

                    <input type="radio" class="btn-check"
                        name="payment_method" id="pay-qris"
                        value="qris" autocomplete="off">

                    <label class="btn btn-outline-dark" for="pay-qris">
                        QRIS
                    </label>
                </div>

                <div id="qris-dummy" class="text-center d-none">
                    <div style="
                        width:180px;
                        height:180px;
                        margin:0 auto;
                        background:repeating-linear-gradient(
                            45deg,
                            #000,
                            #000 10px,
                            #fff 10px,
                            #fff 20px
                        );
                        border:4px solid #000;">
                    </div>

                    <p class="text-muted mt-2 small">
                        (Simulasi QRIS - bukan QR asli)
                    </p>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">
                    Batal
                </button>

                <button type="button"
                    class="btn btn-primary"
                    id="btn-confirm-payment">
                    Konfirmasi Bayar
                </button>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
const token = '{{ $token }}'; // <== BARU

function apiPost(url, data) {
    return fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ ...data, token }) // <== BARU: token selalu ikut terkirim
    }).then(res => {
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        return res.json();
    });
}

// ==================== BARU: POLLING KERANJANG ====================
// Setiap 1.5 detik, tanya server "keranjang token ini udah ada isinya belum"
setInterval(() => {
    fetch(`/checkout/cart-state/${token}`)
        .then(res => res.json())
        .then(data => renderCart(data.cart));
}, 1500);


// ==================== SCAN MANUAL ====================

document.getElementById('btn-manual-scan').addEventListener('click', function() {
    const code = document.getElementById('manual-barcode').value.trim();
    if (!code) return;

    apiPost('{{ route("checkout.scan") }}', { barcode: code })
        .then(data => {
            if (data.success) {
                renderCart(data.cart);
            } else {
                Swal.fire({ icon: 'error', title: 'Gagal', text: data.message, timer: 1500, showConfirmButton: false });
            }
        });

    document.getElementById('manual-barcode').value = '';
});


// ==================== RENDER KERANJANG ====================

function renderCart(cart) {
    const list = document.getElementById('cart-list');
    const totalEl = document.getElementById('cart-total');
    const btnCheckout = document.getElementById('btn-checkout');

    const items = Object.entries(cart);

    if (items.length === 0) {
        list.innerHTML = '<p class="text-muted text-center py-4">Belum ada produk di keranjang</p>';
        totalEl.textContent = 'Rp0';
        btnCheckout.disabled = true;
        return;
    }

    let total = 0;
    let html = '';

    items.forEach(([productId, item]) => {
        const subtotal = item.price * item.quantity;
        total += subtotal;

        html += `
            <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                <div>
                    <div>${item.name}</div>
                    <small class="text-muted">Rp${item.price.toLocaleString('id-ID')} x ${item.quantity}</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-sm btn-outline-secondary" onclick="updateQty(${productId}, ${item.quantity - 1})">-</button>
                    <span>${item.quantity}</span>
                    <button class="btn btn-sm btn-outline-secondary" onclick="updateQty(${productId}, ${item.quantity + 1})">+</button>
                </div>
            </div>
        `;
    });

    list.innerHTML = html;
    totalEl.textContent = 'Rp' + total.toLocaleString('id-ID');
    btnCheckout.disabled = false;
}


// ==================== UPDATE QTY ====================

function updateQty(productId, quantity) {
    apiPost('{{ route("checkout.cart.update") }}', { product_id: productId, quantity: quantity })
        .then(data => {
            if (data.success) {
                renderCart(data.cart);
            } else {
                // <== FIX: sebelumnya kalau gagal (misal stok kurang), nggak ada
                // pesan apapun ke user - tombol + kelihatan kayak nggak ngapa-ngapain.
                Swal.fire({ icon: 'error', title: 'Gagal', text: data.message, timer: 1800, showConfirmButton: false });
                if (data.cart) renderCart(data.cart); // tetep sinkronin tampilan ke qty yang valid
            }
        });
}


// ==================== CEK MEMBER ====================

document.getElementById('btn-check-member').addEventListener('click', function() {
    const phone = document.getElementById('member-phone').value.trim();
    if (!phone) return;

    apiPost('{{ route("checkout.member.check") }}', { phone: phone })
        .then(data => {
            const infoBox = document.getElementById('member-info');

            if (data.success) {
                infoBox.textContent = `Member: ${data.member.name} (Poin: ${data.member.points})`;
                infoBox.classList.remove('d-none');
            } else {
                Swal.fire({ icon: 'error', title: 'Gagal', text: data.message, timer: 1500, showConfirmButton: false });
            }
        });
});


// ==================== QRIS ====================

document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.getElementById('qris-dummy').classList.toggle('d-none', this.value !== 'qris');
    });
});


// ==================== PEMBAYARAN ====================

document.getElementById('btn-confirm-payment').addEventListener('click', function() {
    const method = document.querySelector('input[name="payment_method"]:checked').value;

    apiPost('{{ route("checkout.process") }}', { payment_method: method })
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect;
            } else {
                Swal.fire({ icon: 'error', title: 'Gagal', text: data.message });
            }
        });
});
</script>
@endsection