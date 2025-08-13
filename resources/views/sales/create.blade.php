@extends('master')

@section('title')
    <title>New Sale</title>
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Create New Sale</h1>
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

        <form action="{{ route('sales.store') }}" method="POST">
            @csrf
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
                                    <option value="">Select Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->namecustomer }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="currency">Currency</label>
                                <select name="currency" id="currency" class="form-control" required>
                                    <option value="IDR">IDR</option>
                                    <option value="USD">USD</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" class="form-control"></textarea>
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

                    <button type="submit" class="btn btn-success btn-lg float-right">Save Transaction</button>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let cart = [];
    let itemIndex = 0;

    $('#add-item-btn').on('click', function() {
        const selectedOption = $('#product-selection option:selected');
        const productId = selectedOption.val();
        const productName = selectedOption.data('name');
        const productPrice = parseFloat(selectedOption.data('price'));
        const quantity = parseInt($('#product-quantity').val());

        if (!productId || quantity <= 0) {
            alert('Please select a product and quantity.');
            return;
        }

        const existingItem = cart.find(item => item.id === productId);
        if (existingItem) {
            alert('Product is already in the cart.');
            return;
        }

        cart.push({
            index: itemIndex,
            id: productId,
            name: productName,
            price: productPrice,
            quantity: quantity,
            subtotal: quantity * productPrice
        });
        itemIndex++;
        renderCart();
    });

    function renderCart() {
        $('#cart-items').empty();
        $('#hidden-inputs-container').empty();
        let grandTotal = 0;

        cart.forEach(item => {
            const formattedPrice = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(item.price);
            const formattedSubtotal = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(item.subtotal);
            grandTotal += item.subtotal;

            $('#cart-items').append(`
                <tr id="cart-row-${item.index}">
                    <td>${item.name}</td>
                    <td>${item.quantity}</td>
                    <td>${formattedPrice}</td>
                    <td>${formattedSubtotal}</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-item-btn" data-index="${item.index}">Remove</button>
                    </td>
                </tr>
            `);

            $('#hidden-inputs-container').append(`
                <input type="hidden" name="items[${item.index}][product_id]" value="${item.id}">
                <input type="hidden" name="items[${item.index}][quantity]" value="${item.quantity}">
                <input type="hidden" name="items[${item.index}][price]" value="${item.price}">
            `);
        });

        $('#grand-total').text(new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(grandTotal));
    }

    $(document).on('click', '.remove-item-btn', function() {
        const indexToRemove = $(this).data('index');
        cart = cart.filter(item => item.index !== indexToRemove);
        renderCart();
    });
});
</script>
@endpush