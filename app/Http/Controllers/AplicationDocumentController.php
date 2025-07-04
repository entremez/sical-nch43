<?php

namespace App\Http\Controllers;

use App\Models\AplicationDocument;
use Illuminate\Http\Request;

class AplicationDocumentController extends Controller
{
    public function index()
    {
        $aplicationDocuments = AplicationDocument::all();
        return view('aplication_documents.index', compact('aplicationDocuments'));
    }

    public function create()
    {
        return view('aplication_documents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        AplicationDocument::create($request->all());
        return redirect()->route('aplication-documents.index')->with('success', 'Documento de aplicación creado.');
    }

    public function show(AplicationDocument $aplicationDocument)
    {
        return view('aplication_documents.show', compact('aplicationDocument'));
    }

    public function edit(AplicationDocument $aplicationDocument)
    {
        return view('aplication_documents.edit', compact('aplicationDocument'));
    }

    public function update(Request $request, AplicationDocument $aplicationDocument)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $aplicationDocument->update($request->all());
        return redirect()->route('aplication-documents.index')->with('success', 'Documento de aplicación actualizado.');
    }

    public function destroy(AplicationDocument $aplicationDocument)
    {
        $aplicationDocument->delete();
        return redirect()->route('aplication-documents.index')->with('success', 'Documento de aplicación eliminado.');
    }
}
