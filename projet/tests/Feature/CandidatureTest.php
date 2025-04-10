<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Candidature;
use App\Models\Annonce;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

class CandidatureTest extends TestCase
{
    use RefreshDatabase;

    protected $recruteur;
    protected $candidat;
    protected $annonce;
    protected $candidature;

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
        
        // Créer un candidat
        $this->candidat = User::factory()->create(['role' => 'candidat']);
        
        // Créer une candidature
        $this->candidature = Candidature::factory()->create([
            'annonce_id' => $this->annonce->id,
            'candidat_id' => $this->candidat->id,
            'statut' => 'en attente'
        ]);
    }

    public function testCanGetAllCandidatures()
    {
        // Générer un token JWT pour le candidat
        $token = JWTAuth::fromUser($this->candidat);
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson('/api/candidatures');
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'annonce_id',
                    'candidat_id',
                    'cv',
                    'lettre_motivation',
                    'statut',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    public function testCanCreateNewCandidature()
    {
        // Générer un token JWT pour le candidat
        $token = JWTAuth::fromUser($this->candidat);
        
        $data = [
            'annonce_id' => $this->annonce->id,
            'cv' => 'CV.pdf',
            'lettre_motivation' => 'Motivation.txt',
            'statut' => 'en attente'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/candidatures', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'annonce_id',
                'candidat_id',
                'cv',
                'lettre_motivation',
                'statut',
                'created_at',
                'updated_at'
            ]);
    }

    public function testCanUpdateCandidature()
    {
        // Générer un token JWT pour le recruteur
        $token = JWTAuth::fromUser($this->recruteur);
        
        $updatedData = [
            'annonce_id' => $this->annonce->id,
            'cv' => 'CV_updated.pdf',
            'lettre_motivation' => 'Motivation_updated.txt',
            'statut' => 'acceptée'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->putJson("/api/candidatures/{$this->candidature->id}", $updatedData);

        $response->assertStatus(200)
            ->assertJson([
                'statut' => 'acceptée'
            ]);
    }

    public function testCanDeleteCandidature()
    {
        // Générer un token JWT pour le candidat
        $token = JWTAuth::fromUser($this->candidat);
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->deleteJson("/api/candidatures/{$this->candidature->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('candidatures', [
            'id' => $this->candidature->id
        ]);
    }

    public function testCanGetCandidatureById()
    {
        // Générer un token JWT pour le candidat
        $token = JWTAuth::fromUser($this->candidat);
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson("/api/candidatures/{$this->candidature->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'annonce_id',
                'candidat_id',
                'cv',
                'lettre_motivation',
                'statut',
                'created_at',
                'updated_at'
            ]);
    }

    public function testCanUpdateCandidatureStatus()
    {
        // Générer un token JWT pour le recruteur
        $token = JWTAuth::fromUser($this->recruteur);
        
        $updatedData = [
            'statut' => 'acceptée'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->putJson("/api/candidatures/{$this->candidature->id}/status", $updatedData);

        $response->assertStatus(200)
            ->assertJson([
                'statut' => 'acceptée'
            ]);
    }

    public function testCanGetCandidaturesByAnnonce()
    {
        // Générer un token JWT pour le recruteur
        $token = JWTAuth::fromUser($this->recruteur);
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson("/api/candidatures/annonce/{$this->annonce->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'annonce_id',
                    'candidat_id',
                    'cv',
                    'lettre_motivation',
                    'statut',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    public function testCanGetCandidaturesByCandidat()
    {
        // Générer un token JWT pour le candidat
        $token = JWTAuth::fromUser($this->candidat);
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->getJson("/api/candidatures/candidat/{$this->candidat->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'annonce_id',
                    'candidat_id',
                    'cv',
                    'lettre_motivation',
                    'statut',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }
}