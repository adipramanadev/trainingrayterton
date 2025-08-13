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
                            {{-- pesan success --}}
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <form action="{{ route('sales.store') }}" enctype="multipart/form-data" method="POST">
                                @csrf
                                @method('POST')
                                <table class="table table-bordered">
                                    <tr>
                                        <td>Sales</td>
                                        <td><input type="text" name="user_id" class="form-control"
                                                value="{{ Auth::user()->name }}" readonly></td>
                                    </tr>
                                    <tr>
                                        <td>Customer</td>
                                        <td>
                                            <select name="customer_id" id="customer_id" class="form-control">
                                                <option value="">Select Customer</option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}">{{ $customer->namecustomer }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
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
                                        <td><input type="text" name="status" id="status" class="form-control"
                                                readonly>
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
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <!-- Button trigger Add Modal -->
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
@endsection
