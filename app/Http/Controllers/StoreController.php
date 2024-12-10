<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stores = Store::all();
        return view('stores.index', ['stores' => $stores, 'elementActive' => 'stores']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('stores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'address' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ]);

        Store::create($data);

        return redirect()->route('stores.index')->with('success', 'Store inputed successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $store = Store::find($id);
        return view('stores.edit', ['store' => $store]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $store = Store::find($id);

        $data = $request->validate([
            'name' => 'required',
            'address' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ]);

        $store->update($data);

        return redirect()->route('stores.index')->with('success', 'Store updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Store::find($id)->delete();
        return redirect()->route('stores.index')->with('success', 'Store deleted successfully.');
    }

    public function suggest(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json([]);
        }

        // Fetch matching stores (you can adjust the limit or add more filters as needed)
        $stores = Store::where('name', 'LIKE', "%{$query}%")
            ->orderBy('name')
            ->limit(10)
            ->get();

        return response()->json($stores);
    }
}
