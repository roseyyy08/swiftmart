@extends('layouts.checkout')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card">
                <div class="card-body text-center">
                    <h4 class="text-success"><i class="bi bi-check-circle-fill"></i> Pembayaran Berhasil</h4>
                    <p class="text-muted">Terima kasih sudah belanja di SwiftMart</p>

                    <hr>

                    <div class="text-start small">
                        <div class="d-flex justify-content-between">
                            <span>No. Invoice</span>
                            <span>{{ $transaction->invoice }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Tanggal</span>
                            <span>{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Member</span>
                            <span>{{ $transaction->member->name ?? 'Guest' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Metode Bayar</span>
                            <span>{{ strtoupper($transaction->payment_method) }}</span>
                        </div>
                    </div>

                    <hr>

                    <table class="table table-sm">
                        @foreach ($transaction->details as $detail)
                            <tr>
                                <td>{{ $detail->product->name ?? '-' }}</td>
                                <td class="text-center">{{ $detail->quantity }}x</td>
                                <td class="text-end">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </table>

                    <hr>

                    <div class="d-flex justify-content-between fs-5 fw-bold">
                        <span>Total</span>
                        <span>Rp{{ number_format($transaction->total, 0, ',', '.') }}</span>
                    </div>

                    @if ($transaction->member)
                        <div class="alert alert-info mt-3 mb-0">
                            Poin kamu sekarang: <strong>{{ $transaction->member->points }}</strong>
                        </div>
                    @endif

                    <a href="{{ route('checkout.index') }}" class="btn btn-success w-100 mt-4">
                        Selesai - Belanja Lagi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection