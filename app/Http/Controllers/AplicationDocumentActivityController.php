<?php

namespace App\Http\Controllers;

use App\Models\AplicationDocument;
use App\Models\Activity;
use Illuminate\Http\Request;

class AplicationDocumentActivityController extends Controller
{
    public function edit($id)
    {
        $document = AplicationDocument::with('activities')->findOrFail($id);
        $activities = Activity::all();

        return view('aplication_documents.edit_activities', compact('document', 'activities'));
    }

    public function update(Request $request, $id)
    {
        $document = AplicationDocument::findOrFail($id);
        $request->validate([
            'activities' => 'array',
            'activities.*' => 'exists:activities,id',
        ]);

        $document->activities()->sync($request->activities ?? []);
        return redirect()->route('aplication-documents.index')->with('success', 'Actividades actualizadas.');
    }
}
