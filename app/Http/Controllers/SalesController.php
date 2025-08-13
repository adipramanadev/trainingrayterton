<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\Product;
use App\Models\Sales_Item;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //get currency
        $currency = $request->input('currency', 'IDR');

        // $sales = Sales::all();
        $customers = Customer::all();
        $salesitem = Sales_Item::all();
        $products = Product::all();
        return view('sales.index', compact('products', 'salesitem', 'customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'currency' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.price' => ['nullable', 'numeric', 'min:0'],
        ]);

        $userId = auth()->id(); // kasir yang login

        $sale = null;

        DB::transaction(function () use ($validated, $userId, &$sale) {
            // 1) Buat 1 record sales
            $sale = Sale::create([
                'user_id' => $userId,
                'currency' => $validated['currency'] ?? null,
                'status' => $validated['status'] ?? 'input',
                'description' => $validated['description'] ?? null,
            ]);

            // 2) Masukkan banyak items dengan sale_id yang SAMA
            $rows = collect($validated['items'])->map(function ($it) use ($sale) {
                return [
                    'sale_id' => $sale->id,
                    'product_id' => $it['product_id'],
                    'quantity' => $it['quantity'],
                    'price' => $it['price'] ?? 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->all();

            // boleh pakai relation:
            // $sale->items()->createMany($rows);

            // atau bulk insert:
            Sales_Item::insert($rows);
        });
        dd($sale);

        // return redirect()
        //     ->route('sales.index')
        //     ->with('success', "Sale #{$sale->id} berhasil dibuat dengan " . count($validated['items']) . " item.");
    }
    /**
     * Display the specified resource.
     */
    public function show(Sales $sales)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sales $sales)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sales $sales)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sales $sales)
    {
        //
    }
}
