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

    public function buscar(Request $request)
    {
        $q = $request->input('q');

        $modelos = ProductModel::with('brand', 'designation')
            ->where('name', 'like', "%{$q}%")
            ->orWhereHas('brand', fn($q2) => $q2->where('name', 'like', "%{$q}%"))
            ->orWhereHas('designation', fn($q3) => $q3->where('name', 'like', "%{$q}%"))
            ->limit(15)
            ->get();

        return response()->json($modelos->map(function ($m) {
            return [
                'id' => $m->id,
                'marca' => $m->brand->name,
                'modelo' => $m->name,
                'denominacion' => $m->designation->name,
            ];
        }));
    }
}
