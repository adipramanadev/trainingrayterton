@extends('master')

@section('title')
    <title>Edit Sale #{{ $sale->id }}</title>
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Edit Sale #{{ $sale->id }}</h1>
    </div>

    <div class="section-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('sales.update', $sale->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                {{-- Detail Penjualan --}}
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header"><h4>Sale Details</h4></div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Cashier</label>
                                <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="customer_id">Customer</label>
                                <select name="customer_id" id="customer_id" class="form-control" required>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ $sale->customer_id == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->namecustomer }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="currency">Currency</label>
                                <select name="currency" id="currency" class="form-control" required>
                                    <option value="IDR" {{ $sale->currency == 'IDR' ? 'selected' : '' }}>IDR</option>
                                    <option value="USD" {{ $sale->currency == 'USD' ? 'selected' : '' }}>USD</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" class="form-control">{{ old('description', $sale->description) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Manajemen Item --}}
                <div class="col-md-8">
                    {{-- Form untuk menambah produk --}}
                    <div class="card">
                        <div class="card-header"><h4>Add Products</h4></div>
                        <div class="card-body">
                            <div class="form-row align-items-end">
                                <div class="form-group col-md-6">
                                    <label>Product</label>
                                    <select id="product-selection" class="form-control">
                                        <option value="">Select a product</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-name="{{ $product->name }}">
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
                                    <button type="button" id="add-item-btn" class="btn btn-primary w-100">Add Item</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tabel keranjang belanja --}}
                    <div class="card">
                        <div class="card-header"><h4>Order Items</h4></div>
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
                                    <tbody id="cart-items">
                                        {{-- Item akan ditambah di sini oleh JavaScript --}}
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-right">Grand Total</th>
                                            <th id="grand-total">Rp 0</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                                <div id="hidden-inputs-container"></div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg float-right">Update Transaction</button>
                     <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-secondary btn-lg float-right mr-2">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
// Mengambil data item yang sudah ada dari controller dan mengubahnya menjadi format JSON
const existingItems = @json($sale->items->map(function($item) {
    return [
        'id' => $item->id, // ID dari sales_items
        'product_id' => $item->product_id,
        'name' => $item->product->name,
        'price' => (float)$item->price,
        'quantity' => $item->quantity
    ];
}));

$(document).ready(function() {
    let cart = [];
    let itemIndex = 0; // Untuk item baru

    // Fungsi untuk memformat angka ke format Rupiah
    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(number);
    }

    // Fungsi untuk menambahkan item ke cart (baik data lama maupun baru)
    function addToCart(item) {
        // Cek duplikat berdasarkan product_id
        const existingInCart = cart.find(cartItem => cartItem.product_id === item.product_id);
        if (existingInCart) {
            alert('Product is already in the cart.');
            return;
        }

        const cartItem = {
            cart_index: itemIndex++, // Index unik untuk setiap baris di cart
            id: item.id || null, // ID dari database (jika ada)
            product_id: item.product_id,
            name: item.name,
            price: item.price,
            quantity: item.quantity,
            subtotal: item.quantity * item.price
        };
        cart.push(cartItem);
    }

    // Fungsi untuk me-render tabel cart dan hidden input
    function renderCart() {
        const cartItemsContainer = $('#cart-items');
        const hiddenInputsContainer = $('#hidden-inputs-container');
        cartItemsContainer.empty();
        hiddenInputsContainer.empty();
        let grandTotal = 0;

        cart.forEach((item, formIndex) => {
            grandTotal += item.subtotal;

            const row = `
                <tr id="cart-row-${item.cart_index}">
                    <td>${item.name}</td>
                    <td>${item.quantity}</td>
                    <td>${formatRupiah(item.price)}</td>
                    <td>${formatRupiah(item.subtotal)}</td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-item-btn" data-cart-index="${item.cart_index}">Remove</button></td>
                </tr>
            `;
            cartItemsContainer.append(row);

            const hiddenInputs = `
                <input type="hidden" name="items[${formIndex}][id]" value="${item.id || ''}">
                <input type="hidden" name="items[${formIndex}][product_id]" value="${item.product_id}">
                <input type="hidden" name="items[${formIndex}][quantity]" value="${item.quantity}">
                <input type="hidden" name="items[${formIndex}][price]" value="${item.price}">
            `;
            hiddenInputsContainer.append(hiddenInputs);
        });

        $('#grand-total').text(formatRupiah(grandTotal));
    }

    // Event handler untuk tombol "Add Item"
    $('#add-item-btn').on('click', function() {
        const selectedOption = $('#product-selection option:selected');
        const productId = selectedOption.val();
        if (!productId) {
            alert('Please select a product.');
            return;
        }

        const newItem = {
            product_id: productId,
            name: selectedOption.data('name'),
            price: parseFloat(selectedOption.data('price')),
            quantity: parseInt($('#product-quantity').val()) || 1
        };
        
        addToCart(newItem);
        renderCart();
        
        // Reset form
        $('#product-selection').val('');
        $('#product-quantity').val(1);
    });

    // Event handler untuk tombol "Remove"
    $(document).on('click', '.remove-item-btn', function() {
        const cartIndexToRemove = $(this).data('cart-index');
        cart = cart.filter(item => item.cart_index !== cartIndexToRemove);
        renderCart();
    });

    // Inisialisasi: Muat item yang sudah ada ke dalam cart saat halaman dibuka
    existingItems.forEach(item => {
        addToCart(item);
    });
    renderCart(); // Tampilkan cart yang sudah diisi
});
</script>
@endpush