<?php

namespace Database\Seeders;

use App\Models\Designation;
use App\Models\Role;
use App\Models\ServiceOrder;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Designation::insert([
            ['name' => 'Plancha'],
            ['name' => 'Hidrolavadora'],
            ['name' => 'Refrigerador'],
            ['name' => 'Lavadora'],
            ['name' => 'Secadora'],
            ['name' => 'Aire Acondicionado'],
            ['name' => 'Calefactor'],
            ['name' => 'Estufa'],
            ['name' => 'Olla a presión'],
            ['name' => 'Olla (Olla arrocera, Olla de cocción lenta)'],
            ['name' => 'Extractor de Aire'],
            ['name' => 'Purificador de Aire'],
            ['name' => 'Ventilador'],
            ['name' => 'Termo eléctrico'],
            ['name' => 'Cocina a gas'],
            ['name' => 'Indeterminado - Eléctrico'],
            ['name' => 'Indeterminado - Combustible'],
        ]);
        
        ServiceOrder::insert([
            ['order_number' => 'SCP-E-12345-2025'],
            ['order_number' => 'SCP-E-12346-2025'],
            ['order_number' => 'SCP-E-12347-2025'],
            ['order_number' => 'SCP-E-12348-2025'],
            ['order_number' => 'SCP-E-12349-2025'],
            ['order_number' => 'SCP-E-12350-2025'],
            ['order_number' => 'SCP-E-12351-2025'],
            ['order_number' => 'SCP-E-12352-2025'],
        ]);

        Role::insert([
            ['name' => 'admin'],
            ['name' => 'user'],
            ['name' => 'viewer'],
        ]);

        User::insert([
            ['name' => 'Marcelo Espinoza', 'email' => 'm.espinoza@laboratoriosilab.com', 'password' => bcrypt('12345678'), 'role_id' => '1']
        ]);
    }     
}
