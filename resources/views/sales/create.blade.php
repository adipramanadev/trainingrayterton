@extends('master')

@section('title')
    <title>Sales Index</title>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Sales Index</h1>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Sales</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('sales.items.store') }}" method="post">
                                @csrf
                                @method('POST')
                                <div class="form-group">
                                    <label for="product">Product</label>
                                    <select name="product_id" class="form-control" id="product">
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="quantity">Quantity</label>
                                    <input type="number" name="quantity" class="form-control" id="quantity" placeholder="Quantity">
                                </div>
                                <div class="form-group">
                                    <label for="price">Price</label>
                                    <input type="text" name="price" class="form-control" id="price" readonly placeholder="Price">
                                </div>
                                <button type="submit" class="btn btn-primary">Add Sale</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Sales Details</h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($salesitem as $sale)
                                        <tr>
                                            <td>{{ $sale->product->name }}</td>
                                            <td>{{ $sale->quantity }}</td>
                                            <td>{{ $sale->price }}</td>
                                            <td>{{ $sale->quantity * $sale->price }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // add price from product selection
        document.getElementById('product').addEventListener('change', function() {
            var selectedProduct = this.options[this.selectedIndex];
            var price = selectedProduct.getAttribute('data-price');
            document.getElementById('price').value = price;
        });

    </script>
@endpush