<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(12);

        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required',
            'harga' => 'required|numeric',
            'foto' => 'required|image|mimes:jpeg,png,jpg'
        ]);

        $foto = $request->file('foto');
        $foto->storeAs('public', $foto->hashName());

        Product::create([
            'nama' => $request->nama,
            'harga' => str_replace(".", "", $request->harga),
            'deskripsi' => $request->deskripsi,
            'foto' => $foto->hashName()
        ]);

        return redirect()->route('products.index')->with('success', 'Add Product Success');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $this->validate($request, [
            'nama' => 'required',
            'harga' => 'required|numeric',
        ]);

        // dd(str_replace(".", "", $request->harga));

        $product->nama = $request->nama;
        $product->harga = str_replace(".", "", $request->harga);
        $product->deskripsi = $request->deskripsi;

        if ($request->file('foto')) {

            if ($product->foto !== "noimage.png") {
                Storage::disk('local')->delete('public/' . $product->foto);
            }
            $foto = $request->file('foto');
            $foto->storeAs('public', $foto->hashName());
            $product->foto = $foto->hashName();
        }

        $product->update();

        return redirect()->route('products.index')->with('success', 'Update Product Success');
    }

    public function destroy(Product $product)
    {
        if ($product->foto !== "noimage.png") {
            Storage::disk('local')->delete('public/' . $product->foto);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Delete Product Success');
    }
}
