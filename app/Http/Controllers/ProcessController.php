<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\AplicationDocument;
use App\Models\PlanActivity;
use App\Models\Process;
use Illuminate\Http\Request;

class ProcessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $estado = $request->input('estado');

        $processes = Process::with(['aplicationDocument', 'planActivities'])->get();

        if ($estado === 'terminado') {
            $processes = $processes->filter(function ($process) {
                $total = $process->planActivities->count();
                $completadas = $process->planActivities->where('is_completed', true)->count();
                return $total > 0 && $total === $completadas;
            });
        }

        if ($estado === 'pendiente') {
            $processes = $processes->filter(function ($process) {
                $total = $process->planActivities->count();
                $completadas = $process->planActivities->where('is_completed', true)->count();
                return $total === 0 || $total !== $completadas;
            });
        }

        return view('processes.index', compact('processes'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $aplicationDocuments = AplicationDocument::with('activities')->get();
        $activities = Activity::all();

        return view('processes.create', compact('aplicationDocuments', 'activities'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'aplication_document_id' => 'required|exists:aplication_documents,id',
            'activities' => 'array',
        ]);

        $process = Process::create([
            'name' => $request->name,
            'aplication_document_id' => $request->aplication_document_id,
        ]);

        foreach ($request->activities as $activityId) {
            PlanActivity::create([
                'process_id' => $process->id,
                'activity_id' => $activityId,
            ]);
        }

        return redirect()->route('processes.show', $process)->with('success', 'Proceso creado.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $process = Process::with('aplicationDocument', 'planActivities.activity')->findOrFail($id);

        return view('processes.show', compact('process'));
    }

    public function edit(Process $process)
    {
        $aplicationDocuments = AplicationDocument::all();
        $activities = Activity::all();
        $process->load('planActivities.activity');

        return view('processes.edit', compact('process', 'aplicationDocuments', 'activities'));
    }

    public function update(Request $request, Process $process)
    {
        $request->validate([
            'name' => 'required|string',
            'aplication_document_id' => 'required|exists:aplication_documents,id',
            'activities' => 'array',
        ]);

        $process->update([
            'name' => $request->name,
            'aplication_document_id' => $request->aplication_document_id,
        ]);

        // Sync plan activities
        $process->planActivities()->delete();
        foreach ($request->activities ?? [] as $activityId) {
            $process->planActivities()->create(['activity_id' => $activityId]);
        }

        return redirect()->route('processes.index')->with('success', 'Proceso actualizado.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Process $process)
    {
        $process->delete(); // Esto también eliminará sus plan_activities por cascada si tienes la FK bien definida
        return redirect()->route('processes.index')->with('success', 'Proceso eliminado correctamente.');
    }
}
