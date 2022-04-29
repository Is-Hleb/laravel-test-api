<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductApiCollection;
use App\Http\Resources\ProductApiResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index(Request $request)
    {
        $settings = $request->input('settings');
        if($settings !== null) {
            $output = ProductApiCollection::apiFilter($settings);
        } else {
            $output = new ProductApiCollection(Product::all());
        }
        return $output;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function store(Request $request)
    {
        $request->validate([
            'categories' => 'array|required|between:2,10',
            'categories.*.id' => 'distinct|required|exists:categories,id',
            'name' => 'string|required|max:256',
            'price' => 'numeric|required',
            'description' => 'string|required'
        ]);

        $data = $request->input();
        $product = new Product([
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price']
        ]);

        $categoriesIds = array_map(fn ($category) => $category['id'], $data['categories']);

        $product->save();
        $product->categories()->attach($categoriesIds);
        return new ProductApiResource($product);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     */
    public function show(Product $product)
    {
        return new ProductApiResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'categories' => 'array',
            'categories.*.id' => 'distinct|required|exists:categories,id',
            'name' => 'string',
            'price' => 'numeric',
            'description' => 'string'
        ]);

        $data = $request->input();
        $product->update([[
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price']
        ]]);
        $categoriesIds = array_map(fn ($category) => $category['id'], $data['categories']);
        $product->save();
        try {
            $product->categories()->attach($categoriesIds);
        } catch (\Exception $exception) {
            return (new ProductApiResource($product))->additional([
                'message' => 'Categories already exists',
            ]);
        }

        return new ProductApiResource($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     */
    public function destroy(Product $product)
    {
        return $product->delete();
    }
}
