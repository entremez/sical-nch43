<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
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
    public function create(Request $request)
    {
        // Solo admin
        if ($request->user()->role_id !== 2) {
            abort(404);
        }

        return view('certificates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Solo admin
        if ($request->user()->role_id !== 2) {
            abort(404);
        }

        $validated = $request->validate([
            // Formato E-022-03-12345 o CL-022-03-1234; el segundo bloque es el sistema que usa el muestreo
            'certificado' => ['required', 'string', 'regex:/^[A-Z]{1,3}-\d{3}-\d{2}-\d+$/', 'unique:certificates,certificado'],
            'qr' => 'nullable|string',
            'cliente' => 'required|string',
            'contacto' => 'nullable|string',
            'direccion' => 'nullable|string',
            'fabrica' => 'nullable|string',
            'direccion_fabrica' => 'nullable|string',
            'producto' => 'required|string',
            'marca' => 'required|string',
            'modelo' => 'required|string',
            'active' => 'required|boolean',
        ], [
            'required' => 'El campo :attribute es obligatorio.',
            'certificado.regex' => 'El N° de certificado debe tener el formato E-022-03-12345, G-022-03-1234 o CL-022-03-1234.',
            'certificado.unique' => 'Ya existe un certificado con ese número.',
        ]);

        // Las columnas no aceptan NULL: los campos opcionales vacíos se guardan como texto vacío
        $certificate = Certificate::create(array_map(fn ($value) => $value ?? '', $validated) + [
            'area' => 1,
        ]);

        return redirect()
            ->route('certificates.index', ['search' => $certificate->certificado])
            ->with('success', 'Certificado creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $certificate = Certificate::findOrFail($id);

        return view('certificates.show', compact('certificate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
