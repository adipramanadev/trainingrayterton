@extends('master')

@section('title')
    <title>Add Categories</title>
@endsection

@section('content')
    <section class="section">
        <section class="section-header">
            <h1>Add Categories</h1>
        </section>
        <section class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Add Categories</h4>
                </div>
                <div class="card-body">
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('category.store') }}" method="post">
                        @csrf
                        @method('POST')
                        <table class="table table-bordered">
                            <tr>
                                <td>Nama</td>
                                <td><input type="text" class="form-control" name="name" autocomplete="off"></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td><button class="btn btn-primary" type="submit">Save</button></td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </section>
    </section>
@endsection
