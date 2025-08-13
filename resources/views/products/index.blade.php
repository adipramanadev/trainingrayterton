@extends('master')

@section('title')
    <title>Product Index</title>
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Product Index</h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('product.create') }}" class="btn btn-outline-primary">Create Product</a>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category->name }}</td>
                                    <td>
                                        <a href="{{ route('product.edit', $product->id) }}" class="btn btn-warning">Edit</a>
                                        <form action="{{ route('product.destroy', $product->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection