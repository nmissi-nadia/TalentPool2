<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@talentpool.com',
            'role' => 'admin',
        ]);
        
        // Create recruiter users
        $recruteurs = \App\Models\User::factory(3)->create([
            'role' => 'recruteur',
        ]);
        
        // Create candidate users
        \App\Models\User::factory(5)->create([
            'role' => 'candidat',
        ]);
        
        // Create job listings for each recruiter
        foreach ($recruteurs as $recruteur) {
            \App\Models\Annonce::factory(3)->create([
                'recruteur_id' => $recruteur->id,
            ]);
        }
    }
}
