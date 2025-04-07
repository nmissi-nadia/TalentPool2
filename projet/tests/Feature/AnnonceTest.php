<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Annonce;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AnnonceTest extends TestCase
{
    use RefreshDatabase;

    protected $recruteur;
    protected $annonce;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Créer un recruteur
        $this->recruteur = User::factory()->create(['role' => 'recruteur']);
        
        // Créer une annonce
        $this->annonce = Annonce::factory()->create([
            'recruteur_id' => $this->recruteur->id,
            'statut' => 'ouverte'
        ]);
    }

    public function testCanGetAllAnnonces()
    {
        // Créer un utilisateur candidat
        $candidat = User::factory()->create(['role' => 'candidat']);
        
        // Générer un token JWT
        $token = JWTAuth::fromUser($candidat);
        
        // Faire la requête avec le token JWT
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson('/api/annonces');
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'titre',
                    'description',
                    'statut'
                ]
            ]);
    }
    
    public function testCanCreateNewAnnonce()
    {
        // Générer un token JWT pour le recruteur
        $token = JWTAuth::fromUser($this->recruteur);
        
        $data = [
            'titre' => 'Développeur PHP',
            'description' => 'Recherche développeur PHP expérimenté',
            'statut' => 'ouverte'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/annonces', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'titre',
                'description',
                'statut',
                'recruteur_id',
                'created_at',
                'updated_at'
            ]);
    }

    public function testCanUpdateAnnonce()
    {
        // Générer un token JWT pour le recruteur
        $token = JWTAuth::fromUser($this->recruteur);
        
        $updatedData = [
            'titre' => 'Développeur PHP Senior',
            'description' => 'Recherche développeur PHP senior expérimenté',
            'statut' => 'fermée'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->putJson("/api/annonces/{$this->annonce->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJson([
                'titre' => 'Développeur PHP Senior',
                'statut' => 'fermée'
            ]);
    }

    public function testCanDeleteAnnonce()
    {
        // Générer un token JWT pour le recruteur
        $token = JWTAuth::fromUser($this->recruteur);
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->deleteJson("/api/annonces/{$this->annonce->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('annonces', [
            'id' => $this->annonce->id
        ]);
    }

    public function testCanGetAnnonceById()
    {
        // Créer un utilisateur candidat
        $candidat = User::factory()->create(['role' => 'candidat']);
        
        // Générer un token JWT
        $token = JWTAuth::fromUser($candidat);
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson("/api/annonces/{$this->annonce->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'titre',
                'description',
                'statut',
                'recruteur_id',
                'created_at',
                'updated_at'
            ]);
    }
}