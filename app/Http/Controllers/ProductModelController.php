<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use App\Models\Brand;
use App\Models\Designation;
use Illuminate\Http\Request;

class ProductModelController extends Controller
{
    public function index()
    {
        $productModels = \App\Models\ProductModel::with(['brand', 'designation'])->orderBy('name')->get();
        return view('product-models.index', compact('productModels'));
    }
    
    public function edit(ProductModel $productModel)
    {
        $brands = Brand::orderBy('name')->get();
        $designations = Designation::orderBy('name')->get();

        return view('product-models.edit', compact('productModel', 'brands', 'designations'));
    }

    public function update(Request $request, ProductModel $productModel)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id',
            'designation_id' => 'required|exists:designations,id',
        ]);

        $productModel->update($validated);

        return redirect()->back()->with('success', 'Modelo actualizado correctamente.');
    }
}
