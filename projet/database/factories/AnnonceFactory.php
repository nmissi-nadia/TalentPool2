<?php

namespace Database\Factories;

use App\Models\Annonce;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnnonceFactory extends Factory
{
    protected $model = Annonce::class;

    public function definition()
    {
        return [
            'recruteur_id' => $this->faker->numberBetween(1, 10),
            'titre' => $this->faker->jobTitle,
            'description' => $this->faker->paragraph,
            'statut' => $this->faker->randomElement(['ouverte', 'fermée']),
        ];
    }
}