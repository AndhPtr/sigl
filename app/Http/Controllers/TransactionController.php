<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Store;
use App\Models\Product;
use App\Models\User;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        // Check if the user has permission to view all transactions
        if ($user->can('edit-all')) {
            // User with 'edit-all' permission can see all transactions
            $transactions = Transaction::with(['user', 'product', 'store'])->get(); // Include relationships
        } else {
            // Otherwise, show only transactions belonging to the current user
            $transactions = Transaction::with(['product', 'store'])
                ->where('users_id', $user->id) // Note: Ensure 'users_id' matches your database column
                ->get();
        }

        return view('transactions.index', [
            'transactions' => $transactions,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $stores = Store::all();
        $products = Product::all();
        return view('transactions.create', ['stores' => $stores, 'products' => $products]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'barcode' => 'required',
            'name' => 'required',
            'store_name' => 'required',
            'address' => 'required',
            'store_lat' => 'required', // Store Latitude
            'store_lng' => 'required', // Store Longitude
            'purchase_date' => 'required',
            'lat' => 'required', // User's latitude
            'lng' => 'required', // User's longitude
            'price' => 'required',
        ]);


        $product = Product::where('barcode', $request->input('barcode'))->first();

        if (!$product) {
            // If no product is found, create a new one
            $product = Product::create([
                'barcode' => $request->input('barcode'),
                'name' => $request->input('name'),
            ]);
        }

        // Retrieve the product ID
        $productId = $product->id;

        if (!$productId) {
            return redirect()->back()->withErrors('Product not found or failed to create a new product.');
        }

        // Check if the store exists
        $storeName = $request->input('store_name');
        $store = Store::where('name', $storeName)->first();

        if (!$store) {
            // Create a new store in the stores table
            $store = Store::create([
                'name' => $storeName,
                'address' => $request->input('address'),
                'lat' => $request->input('store_lat'),
                'lng' => $request->input('store_lng'),
            ]);
        }

        // Insert a new transaction into the transactions table
        Transaction::create([
            'products_id' => $productId,
            'stores_id' => $store->id, // Use the existing or newly created store ID
            'purchase_date' => $request->input('purchase_date'),
            'lat' => $request->input('lat'),
            'lng' => $request->input('lng'),
            'price' => $request->input('price'),
            'users_id' => auth()->id(), // Store the currently logged-in user's ID
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaction successfully added.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $transactions = Transaction::find($id);
        $stores = Store::all();
        $products = Product::all();
        return view('transactions.edit', ['stores' => $stores, 'products' => $products, 'transactions' => $transactions]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the incoming data
        $request->validate([
            'barcode' => 'required',
            'name' => 'required',
            'store_name' => 'required',
            'address' => 'required',
            'purchase_date' => 'required|date',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'price' => 'required|numeric',
        ]);

        $transaction = Transaction::findOrFail($id);

        // Update or create the product
        $product = Product::updateOrCreate(
            ['barcode' => $request->barcode],
            ['name' => $request->name]
        );

        // Update or create the store
        $store = Store::updateOrCreate(
            ['name' => $request->store_name],
            [
                'address' => $request->address,
                'lat' => $request->lat,
                'lng' => $request->lng,
            ]
        );

        // Update the transaction record
        $transaction->update([
            'products_id' => $product->id,
            'stores_id' => $store->id,
            'purchase_date' => $request->purchase_date,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'price' => $request->price,
        ]);

        return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Transaction::find($id)->delete();
        return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully.');
    }
}
