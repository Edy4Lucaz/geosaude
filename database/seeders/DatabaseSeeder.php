<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Doenca;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. CRIAR ADMINISTRADOR (Role 1)
        User::create([
            'name' => 'Administrador Central',
            'email' => 'admin@geosaude.it',
            'password' => Hash::make('admin123'),
            'role' => 1,
            'provincia' => 'Luanda',
            'municipio' => 'Luanda',
        ]);

        // 2. CRIAR MÉDICO (Role 2)
        User::create([
            'name' => 'Dr. Silvano João',
            'email' => 'medico@geosaude.it',
            'password' => Hash::make('123456'),
            'role' => 2,
            'provincia' => 'Benguela',
            'municipio' => 'Lobito',
        ]);

        // 3. CRIAR DOENÇA DE TESTE
        Doenca::create([
            'nome_doenca' => 'Malária',
            'sintomas' => 'Febre, calafrios, dor de cabeça',
            'prevencao' => 'Uso de mosquiteiros e repelente'
        ]);
        
        Doenca::create([
            'nome_doenca' => 'Cólera',
            'sintomas' => 'Diarreia aguda, vómitos',
            'prevencao' => 'Higiene das mãos e água fervida'
        ]);
    }
}