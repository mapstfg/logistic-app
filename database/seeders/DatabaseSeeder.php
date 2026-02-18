<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@logistic.com',
            'password' => Hash::make('password'),
            'role' => 'ADMIN',
        ]);

        User::factory()->create([
            'name' => 'Medico User',
            'email' => 'medico@logistic.com',
            'password' => Hash::make('password'),
            'role' => 'MEDICO',
        ]);

        User::factory()->create([
            'name' => 'Farmacia User',
            'email' => 'farmacia@logistic.com',
            'password' => Hash::make('password'),
            'role' => 'FARMACIA',
        ]);

        User::factory()->create([
            'name' => 'Bodega User',
            'email' => 'bodega@logistic.com',
            'password' => Hash::make('password'),
            'role' => 'BODEGA',
        ]);

        // Seed Patients
        \App\Models\Patient::create([
            'full_name' => 'Juan Perez',
            'document_id' => '12345678',
            'phone' => '555-0101',
            'notes' => 'Paciente recurrente',
        ]);

        // Seed Medicines
        \App\Models\Medicine::create([
            'name' => 'Paracetamol 500mg',
            'description' => 'Analgésico y antipirético',
            'stock_bodega' => 500,
            'stock_farmacia' => 100,
            'min_stock' => 50,
            'expires_at' => '2027-12-31',
            'location' => 'Estante A1',
        ]);

        \App\Models\Medicine::create([
            'name' => 'Ibuprofeno 400mg',
            'description' => 'Antiinflamatorio',
            'stock_bodega' => 300,
            'stock_farmacia' => 50,
            'min_stock' => 30,
            'expires_at' => '2026-06-30',
            'location' => 'Estante A2',
        ]);

        // Seed Supplies
        \App\Models\Supply::create([
            'name' => 'Jeringa 5ml',
            'description' => 'Suministro desechable',
            'stock_bodega' => 1000,
            'stock_farmacia' => 200,
            'min_stock' => 100,
            'location' => 'Caja B1',
        ]);
    }
}
