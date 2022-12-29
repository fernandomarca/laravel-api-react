<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\ProductsRequest as Request;
use Illuminate\Support\Facades\Cache;

class ProductsController extends Controller
{
    public function index()
    {
        $minutes = \Carbon\Carbon::now()->addMinute(10);
        return Cache::remember('products', $minutes, function () {
            return Product::all();
        });
    }

    public function store(Request $request)
    {
        Cache::forget('products');
        return Product::create($request->all());
    }

    public function update(Request $request, Product $product): Product
    {
        $product->update($request->all());
        return $product;
    }

    public function show(Product $product): Product
    {
        return $product;
    }

    public function destroy(Product $product): Product
    {
        Cache::forget('products');

        $product->delete();
        return $product;
    }
}