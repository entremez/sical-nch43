<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use App\Models\Brand;
use App\Models\Designation;
use Illuminate\Http\Request;

class ProductModelController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductModel::with(['brand', 'designation']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                ->orWhereHas('brand', fn($q) => $q->where('name', 'like', "%{$search}%"))
                ->orWhereHas('designation', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }

        $models = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('models.index', compact('models'));
    }

    public function create()
    {
        $brands = Brand::all();
        $designations = Designation::all();
        return view('models.create', compact('brands', 'designations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id',
            'designation_id' => 'required|exists:designations,id',
            'description' => 'nullable|string',
        ]);

        ProductModel::create($validated);

        return redirect()->route('models.index')->with('success', 'Modelo creado exitosamente.');
    }

    public function edit(ProductModel $model)
    {
        $brands = Brand::all();
        $designations = Designation::all();
        return view('models.edit', compact('model', 'brands', 'designations'));
    }

    public function update(Request $request, ProductModel $model)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,brand_id',
            'designation_id' => 'required|exists:designations,designation_id',
            'description' => 'nullable|string',
        ]);

        $model->update($validated);

        return redirect()->route('models.index')->with('success', 'Modelo actualizado correctamente.');
    }

    public function destroy(ProductModel $model)
    {
        $model->delete();
        return redirect()->route('models.index')->with('success', 'Modelo eliminado.');
    }

    public function search(Request $request)
    {
        $q = $request->input('q');

        return ProductModel::with(['brand', 'designation'])
            ->where('name', 'like', "%{$q}%")
            ->select('id', 'name', 'brand_id', 'designation_id')
            ->limit(10)
            ->get()
            ->map(function ($model) {
                return [
                    'id' => $model->id,
                    'name' => $model->name,
                    'brand' => ['name' => $model->brand->name],
                    'designation' => ['name' => $model->designation->name],
                ];
            });
    }
}
