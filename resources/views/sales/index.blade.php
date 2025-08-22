@extends('master')

@section('title')
    <title>Sales Transactions</title>
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Sales Transactions</h1>
        <div class="section-header-breadcrumb">
            <a href="{{ route('sales.create') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> New Sale
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="section-body">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>SO Number</th>
                                <th>Customer</th>
                                <th>Cashier</th>
                                <th>Date</th>
                                <th>Items</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sales as $sale)
                                <tr>
                                    <td>#{{ $sale->id }}</td>
                                    <td>{{ $sale->so_no }}</td>
                                    <td>{{ $sale->customer->namecustomer }}</td>
                                    <td>{{ $sale->user->name }}</td>
                                    <td>{{ $sale->created_at->format('d-m-Y') }}</td>
                                    <td>{{ $sale->items->count() }}</td>
                                    <td><span class="badge badge-success">{{ $sale->status }}</span></td>
                                    <td>
                                        <a href="{{ route('sales.show', $sale) }}" class="btn btn-info btn-sm">View</a>
                                        <a href="{{ route('sales.edit', $sale) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('sales.destroy', $sale) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No transactions found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $sales->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection