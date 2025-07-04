<?php

namespace App\Http\Controllers;

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
        return view('type_certificates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'certificado' => 'required|unique:type_certificates,certificado',
            'cliente_id' => 'required|integer',
            'fabrica_id' => 'required|integer',
            'test_report_number' => 'nullable|string',
            'test_report_date' => 'nullable|date',
            'area' => 'required|integer',
        ]);

        TypeCertificate::create($validated);

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
}
