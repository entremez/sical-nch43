<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Area;
use App\Models\Category;
use App\Models\Role;
use App\Models\Scope;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activities = [
            'Informe tipo',
            'informe control regular',
            'informe de auditoría de control de calidad',
            'informe de auditoría de línea de producción',
            'Informe de auditoría parcial',
            'Visita a terreno',
            'Muestreo',
            'Informe voluntario',
            'Declaración mensual',
            'Declaración de conformidad respecto al tipo',
        ];

        foreach ($activities as $name) {
            Activity::create(['name' => $name]);
        }

        Role::create([
            'name' => 'user'
        ]);        
        
        Role::create([
            'name' => 'admin'
        ]);

        User::create([
            'name' => 'Administrador',
            'email' => 'm.espinoza@laboratoriosilab.com',
            'password' => bcrypt('12345678'),
            'role_id' => 2 // Assuming role_id 1 is for admin
        ]);

        Area::create([
            'nombre' => 'Eléctrica',
            'descripcion' => '1'
        ]);

        Area::create([
            'nombre' => 'Combustible líquido',
            'descripcion' => '2'
        ]);

        Area::create([
            'nombre' => 'Combustible gas',
            'descripcion' => '2'
        ]);

        Area::create([
            'nombre' => 'Combustible leña',
            'descripcion' => '2'
        ]);

        Scope::create([
            'nombre' => 'Seguridad',
            'descripcion' => ''
        ]);

        Scope::create([
            'nombre' => 'Eficiencia energética',
            'descripcion' => ''
        ]);

        $areas = Area::pluck('id', 'nombre');

        // Eléctrica
        Category::insert([
            ['area_id' => $areas['Eléctrica'], 'name' => 'Artefactos de uso doméstico para ambiente normal'],
            ['area_id' => $areas['Eléctrica'], 'name' => 'Iluminación'],
            ['area_id' => $areas['Eléctrica'], 'name' => 'Herramientas eléctricas manuales'],
            ['area_id' => $areas['Eléctrica'], 'name' => 'Equipos electrónicos de audio/video, tecnología de la información y tecnología de la comunicación'],
            ['area_id' => $areas['Eléctrica'], 'name' => 'Materiales para baja tensión'],
        ]);

        // Combustible líquido
        Category::insert([
            ['area_id' => $areas['Combustible líquido'], 'name' => 'Componentes de Productos de Combustibles Líquidos'],
            ['area_id' => $areas['Combustible líquido'], 'name' => 'Artefactos Combustibles Líquidos'],
        ]);

        // Combustible gas
        Category::insert([
            ['area_id' => $areas['Combustible gas'], 'name' => 'Artefactos'],
            ['area_id' => $areas['Combustible gas'], 'name' => 'Cilindros'],
            ['area_id' => $areas['Combustible gas'], 'name' => 'Componentes de productos de Gas'],
            ['area_id' => $areas['Combustible gas'], 'name' => 'Dispositivos, accesorios, materiales de instalación'],
        ]);

        // Combustible leña
        Category::insert([
            ['area_id' => $areas['Combustible leña'], 'name' => 'Artefactos de combustibles sólidos'],
        ]);
    }
}
