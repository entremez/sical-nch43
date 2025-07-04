<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Role;
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
    }
}
