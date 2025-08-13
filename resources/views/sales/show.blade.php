@extends('master')

@section('title')
    <title>Sale Details #{{ $sale->id }}</title>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Sale Details #{{ $sale->id }}</h1>
            <div class="section-header-breadcrumb">
                {{-- **DIUBAH**: Logika tombol dipindahkan ke sini --}}
                <a href="{{ route('sales.index') }}" class="btn btn-secondary">Back to Sales List</a>

                {{-- Jika status COMPLETED, tampilkan tombol Batalkan --}}
                @if($sale->status === 'completed')
                    <form action="{{ route('sales.cancel', $sale->id) }}" method="POST" class="d-inline ml-2">
                        @csrf
                        <button type="submit" class="btn btn-danger"
                            onclick="return confirm('Anda yakin ingin membatalkan transaksi ini? Status akan kembali ke INPUT.');">
                            Batalkan Transaksi
                        </button>
                    </form>
                @endif

                {{-- Tombol Edit hanya muncul jika status INPUT --}}
                @if($sale->status === 'input')
                    <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-warning ml-2">Edit</a>
                @endif
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif


        <div class="section-body">
            <div class="row">
                {{-- Kolom Kiri: Info Utama --}}
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Transaction Info</h4>
                        </div>
                        <div class="card-body">
                            <p><strong>SO Number:</strong> {{ $sale->so_no }}</p>
                            <p><strong>Date:</strong> {{ $sale->created_at->format('d F Y, H:i') }}</p>
                            <p><strong>Customer:</strong> {{ $sale->customer->namecustomer }}</p>
                            <p><strong>Cashier:</strong> {{ $sale->user->name }}</p>
                            {{-- **DIUBAH**: Badge status disesuaikan --}}
                            <p><strong>Status:</strong>
                                <span
                                    class="badge {{ $sale->status === 'completed' ? 'badge-success' : 'badge-warning' }} text-capitalize">
                                    {{ $sale->status }}
                                </span>
                            </p>
                            @if($sale->description)
                                <p class="mt-4"><strong>Description:</strong><br>{{ $sale->description }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Daftar Item (Tidak ada perubahan di sini) --}}
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>Items Purchased</h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th>Price per Item</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $grandTotal = 0; @endphp
                                        @foreach($sale->items as $item)
                                            @php
                                                $subtotal = $item->quantity * $item->price;
                                                $grandTotal += $subtotal;
                                            @endphp
                                            <tr>
                                                <td>{{ $item->product->name ?? 'Product Deleted' }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>{{ number_format($item->price, 0, ',', '.') }}</td>
                                                <td>{{ number_format($subtotal, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-light">
                                            <th colspan="3" class="text-right">Grand Total ({{ $sale->currency }})</th>
                                            <th>{{ number_format($grandTotal, 0, ',', '.') }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection