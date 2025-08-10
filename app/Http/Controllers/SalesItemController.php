<?php

namespace App\Http\Controllers;

use App\Models\Sales_Item;
use App\Models\Sales;
use Illuminate\Http\Request;

class SalesItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //isi function sale itemnya
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        
        $sale = Sales::firstOrCreate(
            [
                'user_id' => 1, // Menggunakan ID pengguna yang sedang login
                // Anda bisa menambahkan kondisi lain di sini, misalnya 'status' => 'pending'
            ],
            [
                'total' => 0, // Inisialisasi total
                'paid' => 0,
                'change' => 0,
            ]
        );

        // Tambahkan data 'sale_id' ke data yang divalidasi
        $validatedData['sale_id'] = $sale->id;

        // Buat item penjualan baru
        Sales_Item::create($validatedData);

        // Di dunia nyata, Anda akan memperbarui total di tabel 'sales' di sini.
        // $sale->total += $validatedData['quantity'] * $validatedData['price'];
        // $sale->save();

        return redirect()->route('sales.index')->with('success', 'Sales item added successfully.');
        // return redirect()->route('sales.index')->with('success', 'Sales item added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sales_Item $sales_Item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sales_Item $sales_Item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sales_Item $sales_Item)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sales_Item $sales_Item)
    {
        //
    }
}
