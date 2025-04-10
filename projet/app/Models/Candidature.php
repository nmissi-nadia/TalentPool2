<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidature extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'annonce_id',
        'candidat_id',
        'cv',
        'lettre_motivation',
        'statut'
    ];
    
    protected $with = ['annonce', 'candidat'];
    
    // Convertir les valeurs de statut pour le frontend
    protected function getStatutAttribute($value)
    {
        // Convertir de la base de données (avec espaces et accents) vers le frontend (avec underscores sans accents)
        $statusMap = [
            'en attente' => 'en_attente',
            'acceptée' => 'acceptee',
            'refusée' => 'refusee'
        ];
        
        return $statusMap[$value] ?? $value;
    }
    
    // Convertir les valeurs de statut pour la base de données
    public function setStatutAttribute($value)
    {
        // Convertir du frontend (avec underscores sans accents) vers la base de données (avec espaces et accents)
        $statusMap = [
            'en_attente' => 'en attente',
            'acceptee' => 'acceptée',
            'refusee' => 'refusée'
        ];
        
        $this->attributes['statut'] = $statusMap[$value] ?? $value;
    }
    
    public function annonce()
    {
        return $this->belongsTo(Annonce::class, 'annonce_id');
    }
    
    public function candidat()
    {
        return $this->belongsTo(User::class, 'candidat_id');
    }
}
