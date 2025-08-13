<?php

namespace App\Http\Controllers;


use App\Models\Product;
use App\Models\Sales_Item;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Sale; // <-- Model yang benar
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sales = Sale::with(['customer', 'user'])->latest()->paginate(10);
        return view('sales.index', compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        return view('sales.create', compact('customers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'currency' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
        ]);

        $sale = null;

        DB::transaction(function () use ($validated, &$sale) {
            $sale = Sale::create([
                'user_id' => auth()->id(),
                'customer_id' => $validated['customer_id'],
                'currency' => $validated['currency'],
                'status' => 'completed',
                'description' => $validated['description'] ?? null,
            ]);

            $items = collect($validated['items'])->map(function ($item) use ($sale) {
                return [
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            });

            Sales_Item::insert($items->all());
        });

        return redirect()
            ->route('sales.show', $sale)
            ->with('success', "Transaksi #{$sale->id} berhasil dibuat.");
    }

    /**
     * Display the specified resource.
     * * @param \App\Models\Sale $sale
     * @return \Illuminate\Http\Response
     */
    // DIUBAH: dari "Sales $sales" menjadi "Sale $sale"
    public function show(Sale $sale)
    {
        $sale->load(['customer', 'user', 'items.product']);
        return view('sales.show', compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Sale $sale
     * @return \Illuminate\Http\Response
     */
    // DIUBAH: dari "Sales $sales" menjadi "Sale $sale"
    public function edit(Sale $sale)
    {
        $customers = Customer::all();
        return view('sales.edit', compact('sale', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Sale $sale
     * @return \Illuminate\Http\Response
     */
    // DIUBAH: dari "Sales $sales" menjadi "Sale $sale"
    public function update(Request $request, Sale $sale)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'currency' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
        ]);

        $sale->update($validated);

        return redirect()
            ->route('sales.show', $sale)
            ->with('success', "Transaksi #{$sale->id} berhasil diperbarui.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Sale $sale
     * @return \Illuminate\Http\Response
     */
    // DIUBAH: dari "Sales $sales" menjadi "Sale $sale"
    public function destroy(Sale $sale)
    {
        DB::transaction(function () use ($sale) {
            $sale->items()->delete();
            $sale->delete();
        });

        return redirect()
            ->route('sales.index')
            ->with('success', "Transaksi #{$sale->id} berhasil dihapus.");
    }
}