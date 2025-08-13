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
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <td>Sales</td>
                                    <td><input type="text" name="user_id" class="form-control" readonly></td>
                                </tr>
                                <tr>
                                    <td>Currency</td>
                                    <td>
                                        <select name="currency" id="currency" class="form-control">
                                            <option value="">Select Currency</option>
                                            <option value="IDR">IDR</option>
                                            <option value="USD">USD</option>
                                            <option value="EUR">EUR</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td><input type="text" name="status" id="status" class="form-control" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Description</td>
                                    <td>
                                        <textarea name="description" class="form-control" id="" cols="30" rows="100"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td><button type="submit" class="btn btn-primary">Save</button></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <!-- Button trigger Add Modal -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addSaleModal"
                                href="#">
                                Add Sale Item
                            </button>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($salesitem as $sale)
                                        <tr>
                                            <td>{{ $sale->product->name }}</td>
                                            <td>{{ $sale->quantity }}</td>
                                            <td>{{ number_format($sale->price, 0) }}</td>
                                            <td>{{ number_format($sale->quantity * $sale->price, 0) }}</td>
                                            <td>
                                                <!-- Button trigger Edit Modal -->
                                                <button type="button" class="btn btn-warning btn-sm" data-toggle="modal"
                                                    data-backdrop="false" data-target="#editSaleModal{{ $sale->id }}">
                                                    Edit
                                                </button>
                                                <form action="{{ route('sales.items.destroy', $sale->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Are you sure you want to delete this item?');">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <td colspan="5">
                                            <center>No items found</center>
                                        </td>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
    </section>

    <!-- Add Modal -->
    {{-- <div class="modal fade" id="addSaleModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('sales.items.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Sale Item</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Product</label>
                            <select name="product_id" id="product_id" class="form-control">
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="number" name="quantity" id="quantity" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" name="price" id="price" readonly class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Auto fill price in Add modal
            $('#product_id').on('change', function() {
                var price = $(this).find(':selected').data('price');
                $('#price').val(price);
            });

            // Auto fill price in Edit modals
            $('[id^="edit_product_id"]').on('change', function() {
                var price = $(this).find(':selected').data('price');
                var idSuffix = $(this).attr('id').replace('edit_product_id', '');
                $('#edit_price' + idSuffix).val(price);
            });
        });
    </script>
@endpush
