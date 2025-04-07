<?php

namespace Database\Factories;

use App\Models\Candidature;
use Illuminate\Database\Eloquent\Factories\Factory;

class CandidatureFactory extends Factory
{
    protected $model = Candidature::class;

    public function definition()
    {
        return [
            'annonce_id' => $this->faker->numberBetween(1, 10),
            'candidat_id' => $this->faker->numberBetween(1, 10),
            'cv' => 'CV.pdf',
            'lettre_motivation' => 'Motivation.txt',
            'statut' => $this->faker->randomElement(['en_attente', 'acceptée', 'refusée']),
        ];
    }
}