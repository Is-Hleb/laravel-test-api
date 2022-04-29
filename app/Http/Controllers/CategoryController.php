<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryApiCollection;
use App\Http\Resources\CategoryApiResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {

        return new CategoryApiCollection(Category::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'string|required|unique:categories',
        ]);
        $category = Category::create($request->input());
        return new JsonResponse($category);
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     */
    public function show(Category $category)
    {
        return new CategoryApiResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Product $product
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'string|required|unique:categories',
        ]);
        $category->name = $request->input('name');
        $category->save();
        return new CategoryApiResource($category);
    }

    public function destroy(Category $category)
    {
        if ($category->products->count() === 0) {
            return new JsonResponse([
                'message' => 'This Category has products'
            ], 451);
        }
        return new JsonResponse(['status' => 'ok']);
    }
}
