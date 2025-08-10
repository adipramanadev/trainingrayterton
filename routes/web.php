<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BelajarController;


//default laravel
Route::get('/', function () {
    return view('welcome');
});

//membuat rute baru / membuat jalan baru/ jalur baru 
//rute baru 
Route::get('belajar', function () {
    return '<h1>Hello world</h1>';
});

//rute baru menampilkan hello world di controller 
Route::get('master', [App\Http\Controllers\BelajarController::class, 'index']);
Route::get('belajar2', [BelajarController::class, 'view']);

Route::get('books',[App\Http\Controllers\BookController::class,'index'])->name('books.index'); //kasih nama di setiap route yang kita buat
// a href = '{{url('/books')}}' ->tanpa name
// a href = '{{route('book.index')}}' -> menggunakan name
// form action = {{url('/store')}} -> tanpa nama
// form action = {{route('book.store')}} -> dengan nama 
Route::get('create-book', [App\Http\Controllers\BookController::class,'create'])->name('book.create');
Route::post('storebook', [App\Http\Controllers\BookController::class, 'store'])->name('book.store');
Route::delete('destroybook/{id}',[App\Http\Controllers\BookController::class,'destroy'])->name('book.destroy');
Route::get('edit/{id}',[App\Http\Controllers\BookController::class,'edit'])->name('book.edit');
Route::put('update/{id}', [App\Http\Controllers\BookController::class,'update'])->name('book.update');