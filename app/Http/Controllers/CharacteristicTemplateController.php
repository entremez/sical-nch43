<?php

namespace App\Http\Controllers;

use App\Models\CharacteristicTemplate;
use App\Models\Category;
use Illuminate\Http\Request;

class CharacteristicTemplateController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $templates = CharacteristicTemplate::with('category')->get();
        return view('characteristic_templates.index', compact('templates', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('characteristic_templates.create', compact('categories'));
    }

    public function editByCategory(Category $category)
    {
        $templates = $category->characteristicTemplates;

        return view('characteristic_templates.create', [
            'category' => $category,
            'templates' => $templates,
            'categories' => Category::all(),
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'fields' => 'required|array',
            'fields.*.nombre' => 'required|string|max:255',
            'fields.*.unidad' => 'nullable|string|max:255',
            'fields.*.tipo' => 'required|in:text,number,checkbox,select',
            'fields.*.opciones' => 'nullable|string',
        ]);

        // 💥 Esto eliminará todos los templates de la categoría seleccionada
        CharacteristicTemplate::where('category_id', $request->category_id)->delete();

        // 💾 Insertar los nuevos
        foreach ($request->fields as $field) {
            CharacteristicTemplate::create([
                'category_id' => $request->category_id,
                'nombre' => $field['nombre'],
                'unidad' => $field['unidad'] ?? null,
                'tipo' => $field['tipo'],
                'opciones' => $field['tipo'] === 'select'
                    ? json_encode(array_map('trim', explode(',', $field['opciones'] ?? '')))
                    : null,
                'obligatorio' => boolval($field['obligatorio'] ?? false),
            ]);
        }

        return redirect()->route('characteristic-templates.index')->with('success', 'Características guardadas.');
    }




    public function edit(CharacteristicTemplate $characteristicTemplate)
    {
        $categories = Category::all();
        return view('characteristic_templates.edit', compact('characteristicTemplate', 'categories'));
    }

    public function update(Request $request, CharacteristicTemplate $characteristicTemplate)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:string,number,boolean,select',
            'opciones' => 'nullable|array',
            'requerido' => 'boolean'
        ]);

        $characteristicTemplate->update([
            'category_id' => $request->category_id,
            'nombre' => $request->nombre,
            'tipo' => $request->tipo,
            'opciones' => $request->opciones,
            'requerido' => $request->boolean('requerido'),
        ]);

        return redirect()->route('characteristic-templates.index')->with('success', 'Plantilla actualizada correctamente.');
    }

    public function destroy(CharacteristicTemplate $characteristicTemplate)
    {
        $characteristicTemplate->delete();
        return redirect()->route('characteristic-templates.index')->with('success', 'Plantilla eliminada correctamente.');
    }
}
