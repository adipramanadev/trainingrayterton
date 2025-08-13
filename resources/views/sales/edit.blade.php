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
            <div class="card">
                <div class="card-header">
                    <h4>Update Sale Information</h4>
                </div>
                <div class="card-body">
                    {{-- Form points to the update route with PUT method --}}
                    <form action="{{ route('sales.update', $sale->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="customer_id">Customer</label>
                            <select name="customer_id" id="customer_id" class="form-control" required>
                                {{-- Loop through customers and select the current one --}}
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
                            {{-- Pre-fill the description --}}
                            <textarea name="description" class="form-control">{{ old('description', $sale->description) }}</textarea>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Update Sale</button>
                            <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="alert alert-warning mt-4">
                <strong>Note:</strong> To maintain transaction integrity, editing individual items (products, quantities, prices) of a completed sale is not supported through this form.
            </div>
        </div>
    </section>
@endsection