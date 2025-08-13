@extends('master')

@section('title')
    <title>Edit Sale #{{ $sale->id }}</title>
@endsection

@section('content')

    @php
        $isCompleted = ($sale->status === 'completed');
    @endphp

    <section class="section">
        <div class="section-header">
        {{-- 1. Judul di sebelah kiri --}}
        <div class="badge {{ $isCompleted ? 'badge-success' : 'badge-warning' }} px-3 py-2">
                Status: {{ strtoupper($sale->status) }}
            </div>

        {{-- 2. Elemen Status di tengah (menggunakan mx-auto) --}}
        <div class="mx-auto text-center">
            <!-- <div class="badge {{ $isCompleted ? 'badge-success' : 'badge-warning' }} px-3 py-2">
                Status: {{ strtoupper($sale->status) }}
            </div> -->
            <h1 class="mb-o">Edit Sale #{{ $sale->id }}</h1>
        </div>

        {{-- 3. Tombol Aksi di sebelah kanan --}}
        <div class="section-header-breadcrumb">
             @if (!$isCompleted)
                {{-- Form untuk tombol Selesaikan Transaksi --}}
                <form action="{{ route('sales.complete', $sale->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success"
                        onclick="return confirm('Anda yakin ingin menyelesaikan transaksi ini? Setelah selesai, data tidak dapat diubah lagi.');">
                        <i class="fas fa-check-circle"></i> Selesaikan Transaksi
                    </button>
                </form>
            @endif
        </div>
    </div>

        <div class="section-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Error!</strong>
                    <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                </div>
            @endif

            {{-- ========================================================================= --}}
            {{-- FORM UTAMA UNTUK UPDATE DATA (TIDAK ADA PERUBAHAN DI DALAMNYA) --}}
            {{-- ========================================================================= --}}
            <form action="{{ route('sales.update', $sale->id) }}" method="POST" id="update-form">
                @csrf
                @method('PUT')
                <div class="row">
                    {{-- Detail Penjualan (Header) --}}
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h4>Sale Details</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="so_no">SO Number</label>
                                    {{-- Dibuat readonly karena ini adalah unique identifier --}}
                                    <input type="text" id="so_no" class="form-control" value="{{ $sale->so_no }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Cashier</label>
                                    <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="customer_id">Customer</label>
                                    <select name="customer_id" id="customer_id" class="form-control" {{ $isCompleted ? 'disabled' : '' }} required>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ $sale->customer_id == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->namecustomer }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="currency">Currency</label>
                                    <select name="currency" id="currency" class="form-control" {{ $isCompleted ? 'disabled' : '' }} required>
                                        <option value="IDR" {{ $sale->currency == 'IDR' ? 'selected' : '' }}>IDR</option>
                                        <option value="USD" {{ $sale->currency == 'USD' ? 'selected' : '' }}>USD</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea name="description" class="form-control" {{ $isCompleted ? 'readonly' : '' }}>{{ old('description', $sale->description) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Manajemen Item (Daftar Produk) --}}
                    <div class="col-md-8">
                        @if (!$isCompleted)
                            <div class="card">
                                <div class="card-header">
                                    <h4>Add/Manage Products</h4>
                                </div>
                                <div class="card-body">
                                    <div class="form-row align-items-end">
                                        <div class="form-group col-md-6">
                                            <label>Product</label>
                                            <select id="product-selection" class="form-control">
                                                <option value="">Select a product to add</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}"
                                                        data-name="{{ $product->name }}">
                                                        {{ $product->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Quantity</label>
                                            <input type="number" id="product-quantity" class="form-control" value="1" min="1">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <button type="button" id="add-item-btn" class="btn btn-primary w-100">Add
                                                Item</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="card">
                            <div class="card-header">
                                <h4>Order Items</h4>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th>Subtotal</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="cart-items"></tbody>
                                    </table>
                                    <div id="hidden-inputs-container"></div>
                                </div>
                            </div>
                            <div class="card-footer bg-light text-right">
                                <strong>Grand Total : &nbsp;</strong>
                                <strong id="grand-total">Rp 0</strong>
                            </div>
                        </div>

                        {{-- Tombol "Update" dan "Cancel" masih menjadi bagian dari form ini --}}
                        {{-- Tombol "Selesaikan" akan kita pindahkan ke luar --}}
                        @if (!$isCompleted)
                            <div class="d-flex justify-content-end">
                                {{-- Tombol 'Update' ini akan men-submit form utama 'update-form' --}}
                                <button type="submit" form="update-form" class="btn btn-primary btn-lg ml-2">Update
                                    Transaction</button>
                                <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-secondary btn-lg ml-2">Cancel</a>
                            </div>
                        @endif
                    </div>
                </div>
            </form>
            {{-- ========================================================================= --}}
            {{-- AKHIR DARI FORM UTAMA --}}
            {{-- ========================================================================= --}}


            {{-- DIUBAH: Logika tombol aksi sekarang berada di luar form utama --}}
            <div class="d-flex justify-content-end mt-3">
                @if ($isCompleted)
                    <div class="w-100">
                        <div class="alert alert-info text-center">Transaksi ini sudah selesai dan tidak dapat diubah lagi.</div>
                        <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-info"><i class="fas fa-arrow-left"></i>
                            Back to Details</a>
                    </div>
                @else
                    {{-- Form terpisah KHUSUS untuk tombol Selesaikan Transaksi --}}
                    
                @endif
            </div>
        </div>
    </section>
@endsection


@push('scripts')
    {{-- Tidak ada perubahan sama sekali di bagian JavaScript --}}
    @php
        $itemsForJs = $sale->items->map(function ($item) {
            return ['id' => $item->id, 'product_id' => $item->product_id, 'name' => $item->product->name ?? 'Product Not Found', 'price' => (float) $item->price, 'quantity' => $item->quantity];
        });
    @endphp
    <script>
        $(document).ready(function () {
            const existingItems = @json($itemsForJs);
            const isCompleted = @json($isCompleted);
            let cart = [];
            let itemIndex = 0;
            function formatRupiah(number) { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number); }
            function addToCart(item) {
                if (!isCompleted) {
                    const existingInCart = cart.find(cartItem => cartItem.product_id === item.product_id);
                    if (existingInCart) { alert('Product is already in the cart.'); return; }
                }
                cart.push({ cart_index: itemIndex++, id: item.id || null, product_id: item.product_id, name: item.name, price: item.price, quantity: item.quantity, subtotal: item.quantity * item.price });
            }
            function renderCart() {
                const cartItemsContainer = $('#cart-items');
                const hiddenInputsContainer = $('#hidden-inputs-container');
                cartItemsContainer.empty();
                hiddenInputsContainer.empty();
                let grandTotal = 0;
                cart.forEach((item, formIndex) => {
                    grandTotal += item.subtotal;
                    const removeButton = isCompleted ? '' : `<button type="button" class="btn btn-danger btn-sm remove-item-btn" data-cart-index="${item.cart_index}">Remove</button>`;
                    cartItemsContainer.append(`<tr id="cart-row-${item.cart_index}"><td>${item.name}</td><td>${item.quantity}</td><td>${formatRupiah(item.price)}</td><td>${formatRupiah(item.subtotal)}</td><td>${removeButton}</td></tr>`);
                    hiddenInputsContainer.append(`<input type="hidden" name="items[${formIndex}][id]" value="${item.id || ''}"><input type="hidden" name="items[${formIndex}][product_id]" value="${item.product_id}"><input type="hidden" name="items[${formIndex}][quantity]" value="${item.quantity}"><input type="hidden" name="items[${formIndex}][price]" value="${item.price}">`);
                });
                $('#grand-total').text(formatRupiah(grandTotal));
            }
            if (!isCompleted) {
                $('#add-item-btn').on('click', function () {
                    const selectedOption = $('#product-selection option:selected');
                    const productId = parseInt(selectedOption.val());
                    if (!productId) { return; }
                    addToCart({ product_id: productId, name: selectedOption.data('name'), price: parseFloat(selectedOption.data('price')), quantity: parseInt($('#product-quantity').val()) || 1 });
                    renderCart();
                    $('#product-selection').val('');
                    $('#product-quantity').val(1);
                });
                $(document).on('click', '.remove-item-btn', function () {
                    const cartIndexToRemove = $(this).data('cart-index');
                    cart = cart.filter(item => item.cart_index !== cartIndexToRemove);
                    renderCart();
                });
            }
            if (existingItems) { existingItems.forEach(item => addToCart(item)); }
            renderCart();
        });
    </script>
@endpush