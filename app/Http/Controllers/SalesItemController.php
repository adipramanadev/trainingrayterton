<?php

namespace App\Http\Controllers;

use App\Models\Sales_Item;
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
        $validated = $request->validate([
            'sales_id' => 'required|exists:sales,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        // Sales_Item::create($validated);
        $salesItem = new Sales_Item($validated);
        $salesItem->sales_id = 1;
        $salesItem->product_id = $validated['product_id'];
        $salesItem->quantity = $validated['quantity'];
        $salesItem->price = $validated['price'];
        $salesItem->save();

        return redirect()->route('sales.index')->with('success', 'Sales item added successfully.');
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
