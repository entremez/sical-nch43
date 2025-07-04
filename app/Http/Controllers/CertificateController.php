<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\ProductModel;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $certificates = Certificate::all();

        return view('certificates.index', compact('certificates'));

        // TODO
        $query = Certificate::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('cliente', 'like', '%' . $search . '%')
                ->orWhere('producto', 'like', '%' . $search . '%')
                ->orWhere('certificado', 'like', '%' . $search . '%');
        }

        // Filtros para 'area' y 'active'
        if ($request->has('area')) {
            $query->where('area', $request->input('area'));
        }
        if ($request->has('active')) {
            $query->where('active', $request->input('active'));
        }

        // Paginación
        $certificados = $query->orderByDesc('id')->paginate(10);

        return view('certificates.index', compact('certificados'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Certificate $certificate)
    {
        $usedIds = $certificate->productModels->pluck('id');
        $availableModels = ProductModel::whereNotIn('id', $usedIds)->with(['brand', 'designation'])->get();

        return view('certificates.show', compact('certificate', 'availableModels'));
    }

    public function attachModel(Request $request, Certificate $certificate)
    {
        $certificate->productModels()->attach($request->product_model_id);
        return back()->with('success', 'Modelo agregado correctamente.');
    }

    public function detachModel(Certificate $certificate, ProductModel $model)
    {
        $certificate->productModels()->detach($model->id);
        return back()->with('success', 'Modelo eliminado del certificado.');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Certificate $certificate)
    {
        $validated = $request->validate([
            'cliente' => 'required|string',
            'contacto' => 'required|string',
            'direccion' => 'required|string',
            'fabrica' => 'required|string',
            'direccion_fabrica' => 'required|string',
            'producto' => 'required|string',
            'marca' => 'required|string',
            'modelo' => 'required|string',
            'active' => 'required|boolean',
            // certificado y qr no se actualizan
        ]);

        $certificate->update($validated);

        return redirect()
            ->route('certificates.index')
            ->with('success', 'Certificado actualizado correctamente.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
