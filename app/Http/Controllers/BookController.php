<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // deklarasikan variabel buku 
        $buku = Book::all(); // select * from buku

        //passing data dari tabel buku di database
        return view('buku',compact('buku'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //redirect ke create page 
        return view('create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validasi data 
        $data = $request->validate([
            'nama' => 'required|string|max:100',
            'pengarang'=>'required|string|max:33',
            'penerbit'=>'required|string|max:33'

        ]);

        //simpan data ke table book 
        Book::create($data); // INSERT INTO books 

        //redirect ke halaman index 
        return redirect()->route('books.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //indentifikasi idnya 
        $buku = Book::findOrFail($id);
        return view('edit', compact('buku'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //validasi data 
        $data = $request->validate([
            'nama' => 'required|string|max:100',
            'pengarang'=>'required|string|max:33',
            'penerbit'=>'required|string|max:33'

        ]);

        //update datanya 
        $book = Book::findOrFail($id);
        $book->update($data);

        //redirect ke halaman index
        return redirect()->route('books.index');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //delete 
        $buku = Book::findOrFail($id); 

        //delete 
        $buku->delete();

        //redirect
        return redirect()->route('books.index');
    }
}
