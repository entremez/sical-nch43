<?php

namespace App\Http\Controllers;

use App\Models\ActivityFile;
use App\Models\PlanActivity;
use App\Models\Process;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlanActivityController extends Controller
{
    public function update(Request $request, PlanActivity $plan)
    {
        $request->validate([
            'is_completed' => 'nullable|boolean',
            'files.*' => 'file|max:20480', // 20 MB máx
        ]);

        $plan->is_completed = $request->boolean('is_completed');
        $plan->completed_at = $plan->is_completed ? now() : null;
        $plan->save();

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('activities');

                $plan->files()->create([
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        return back()->with('success', 'Actividad actualizada correctamente.');
    }

    public function massUpdate(Request $request, Process $process)
    {
        foreach ($process->planActivities as $plan) {
            $planId = $plan->id;

            // Check si se marcó como completada
            $completed = $request->input("completed.$planId");
            $plan->is_completed = $completed ? true : false;
            $plan->completed_at = $completed ? now() : null;
            $plan->save();

            // Procesar archivos si existen
            if ($request->hasFile("files.$planId")) {
                foreach ($request->file("files.$planId") as $index => $file) {
                    $path = $file->store('activities');

                    ActivityFile::create([
                        'plan_activity_id' => $planId,
                        'path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'document_name' => $request->input("names.$planId.$index"),
                        'document_date' => $request->input("dates.$planId.$index"),
                    ]);
                }
            }
        }

        return back()->with('success', 'Actividades actualizadas correctamente.');
    }

    public function destroy(ActivityFile $file)
    {
        if (Storage::exists($file->path)) {
            Storage::delete($file->path);
        }

        $file->delete();

        return response()->json(['success' => true]);
    }
}
