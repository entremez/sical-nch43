<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Protocol;
use App\Models\Protocolo;
use App\Models\Scope;
use App\Models\TypeCertificate;
use Illuminate\Http\Request;

class TypeCertificateController extends Controller
{
    public function index()
    {
        $typeCertificates = TypeCertificate::latest()->get();
        return view('type_certificates.index', compact('typeCertificates'));
    }

    public function create()
    {
        $areas = Area::all();
        $scopes = Scope::all();
        return view('type_certificates.create', compact('areas', 'scopes'));
    }

public function store(Request $request)
{
    $validated = $request->validate([
        'scope' => 'required|integer',
        'solicitud' => 'required|string',
        'fecha_solicitud' => 'required|date',
        'certificado' => 'required|unique:type_certificates,certificate',
        'fecha_certificado' => 'required|date',
        'sec' => 'required|string',
        'cliente_id' => 'required|string',
        'fabrica_id' => 'required|string',
        'test_report_number' => 'nullable|string',
        'test_report_date' => 'nullable|date',
        'laboratorio_id' => 'required|string',
        'area' => 'required|integer',
        'denominacion_comercial' => 'required|string',
        'protocolo_id' => 'required|string',
        'modelos' => 'nullable|array',
        'modelos.*' => 'exists:product_models,id',
        'caracteristicas' => 'nullable|array',
    ]);

    // Crear el certificado
    $cert = TypeCertificate::create([
        'scope_id' => $validated['scope'],
        'solicitud' => $validated['solicitud'],
        'fecha_solicitud' => $validated['fecha_solicitud'],
        'certificate' => $validated['certificado'],
        'certificate_date' => $validated['fecha_certificado'],
        'n_sec' => $validated['sec'],
        'cliente_id' => $validated['cliente_id'],
        'fabrica_id' => $validated['fabrica_id'],
        'test_report_number' => $validated['test_report_number'] ?? null,
        'test_report_date' => $validated['test_report_date'] ?? null,
        'laboratorio_id' => $validated['laboratorio_id'],
        'area' => $validated['area'],
        'denominacion_comercial' => $validated['denominacion_comercial'],
        'protocolo_id' => $validated['protocolo_id'],
    ]);

    // Asociar modelos si se enviaron
    if (!empty($validated['modelos'])) {
        $cert->productModels()->sync($validated['modelos']);
    }

    // Guardar características técnicas, si corresponde (opcional)
    if (!empty($validated['caracteristicas'])) {
        foreach ($validated['caracteristicas'] as $templateId => $valor) {
            $cert->characteristics()->create([
                'characteristic_template_id' => $templateId,
                'valor' => is_array($valor) ? json_encode($valor) : $valor,
            ]);
        }
    }

    return redirect()->route('type-certificates.index')->with('success', 'Certificado creado correctamente.');
}



    public function show(TypeCertificate $typeCertificate)
    {
        return view('type_certificates.show', compact('typeCertificate'));
    }

    public function edit(TypeCertificate $typeCertificate)
    {
        return view('type_certificates.edit', compact('typeCertificate'));
    }

    public function update(Request $request, TypeCertificate $typeCertificate)
    {
        $validated = $request->validate([
            'certificado' => 'required|unique:type_certificates,certificado,' . $typeCertificate->id,
            'cliente_id' => 'required|integer',
            'fabrica_id' => 'required|integer',
            'test_report_number' => 'nullable|string',
            'test_report_date' => 'nullable|date',
            'area' => 'required|integer',
        ]);

        $typeCertificate->update($validated);

        return redirect()->route('type-certificates.index')->with('success', 'Certificado actualizado.');
    }

    public function destroy(TypeCertificate $typeCertificate)
    {
        $typeCertificate->delete();
        return redirect()->route('type-certificates.index')->with('success', 'Certificado eliminado.');
    }

    public function assign(Request $request)
    {
        $request->validate([
            'area_id' => 'required|exists:areas,id',
            'category_id' => 'required|exists:categories,id',
            'protocol_ids' => 'nullable|array'
        ]);

        $categoryId = $request->category_id;
        $protocolIds = $request->protocol_ids ?? [];

        // 1. Eliminar todos los protocolos actuales de esta categoría
        Protocol::where('category_id', $categoryId)->delete();

        // 2. Obtener protocolos desde la tabla externa "protocolo"
        $protocolos = Protocolo::whereIn('IdProtocolo', $protocolIds)->get();

        // 3. Reasignar los nuevos protocolos a la categoría
        foreach ($protocolos as $p) {
            $codigo = strtoupper(trim($p->Codigo));

            Protocol::updateOrCreate(
                ['name' => $codigo], // clave única
                [
                    'producto' => $p->Producto,
                    'category_id' => $categoryId,
                    'activo' => $p->Estado == 1,
                ]
            );
        }

        return redirect()->back()->with('success', 'Protocolos asignados correctamente.');
    }

    public function showAssignForm()
    {
        $areas = \App\Models\Area::all();
        $categories = \App\Models\Category::all();
        $protocols = Protocolo::where('Estado', 1)->get();
        $protocolos_categorizados = Protocol::all();

        return view('category_protocols.assign', compact('areas', 'categories', 'protocols'));
    }
}
