<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Sales_Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule; // Import Rule untuk validasi unik

class SalesController extends Controller
{
    // ... metode index dan create tidak berubah ...
    public function index()
    {
        $sales = Sale::with(['customer', 'user'])->latest()->paginate(10);
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        return view('sales.create', compact('customers', 'products'));
    }


    /**
     * **DIUBAH**: Menambahkan validasi dan penyimpanan so_no.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'so_no' => ['required', 'string', 'max:255', 'unique:sales,so_no'], 
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
                'so_no' => $validated['so_no'], // Simpan so_no
                'user_id' => auth()->id(),
                'customer_id' => $validated['customer_id'],
                'currency' => $validated['currency'],
                'status' => 'Input',
                'description' => $validated['description'] ?? null,
            ]);

            // Tambahkan so_no ke setiap item sebelum disimpan
            $items = collect($validated['items'])->map(function ($item) use ($sale) {
                return new Sales_Item($item + ['so_no' => $sale->so_no]);
            });
            $sale->items()->saveMany($items);
        });

        return redirect()->route('sales.show', $sale)->with('success', "Transaksi #{$sale->so_no} berhasil dibuat dengan status 'Input'.");
    }

    public function show(Sale $sale)
    {
        $sale->load(['customer', 'user', 'items.product']);
        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        $sale->load('items.product');
        $customers = Customer::all();
        $products = Product::all();
        return view('sales.edit', compact('sale', 'customers', 'products'));
    }

    /**
     * **DIUBAH**: so_no tidak bisa diedit, jadi tidak perlu divalidasi/diupdate.
     * Logika lain tetap sama.
     */
    public function update(Request $request, Sale $sale)
    {
        // so_no sebagai unique identifier sebaiknya tidak diubah.
        // Jika ingin bisa diubah, tambahkan validasi dan logika update di sini.
        if ($sale->status !== 'Input') {
            return redirect()->route('sales.show', $sale)->with('error', 'Transaksi yang sudah selesai tidak dapat diubah.');
        }

        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'currency' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['nullable', 'exists:sales_items,id'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($validated, $sale) {
            $sale->update([
                'customer_id' => $validated['customer_id'],
                'currency' => $validated['currency'],
                'description' => $validated['description'],
            ]);

            $incomingItemIds = collect($validated['items'])->pluck('id')->filter();
            $sale->items()->whereNotIn('id', $incomingItemIds)->delete();

            foreach ($validated['items'] as $itemData) {
                $sale->items()->updateOrCreate(
                    ['id' => $itemData['id'] ?? null],
                    $itemData + ['so_no' => $sale->so_no] // Pastikan so_no tetap ada untuk item baru
                );
            }
        });

        return redirect()->route('sales.show', $sale)->with('success', "Transaksi #{$sale->so_no} berhasil diperbarui.");
    }
    
    // ... metode destroy, complete, dan cancel tidak berubah ...
    public function destroy(Sale $sale)
    {
        DB::transaction(function () use ($sale) {
            $sale->items()->delete();
            $sale->delete();
        });
        return redirect()->route('sales.index')->with('success', "Transaksi #{$sale->so_no} berhasil dihapus.");
    }
    public function complete(Sale $sale)
    {
        if ($sale->status === 'Input') {
            $sale->status = 'completed';
            $sale->save();
            return redirect()->route('sales.show', $sale)->with('success', 'Transaksi berhasil diselesaikan!');
        }
        return redirect()->route('sales.show', $sale)->with('error', 'Transaksi ini sudah selesai sebelumnya.');
    }
    public function cancel(Sale $sale)
    {
        if ($sale->status === 'completed') {
            $sale->status = 'Input';
            $sale->save();
            return redirect()->route('sales.show', $sale)->with('success', 'Transaksi berhasil dibatalkan dan status dikembalikan ke Input.');
        }
        return redirect()->route('sales.show', $sale)->with('error', 'Hanya transaksi yang sudah selesai yang dapat dibatalkan.');
    }
}