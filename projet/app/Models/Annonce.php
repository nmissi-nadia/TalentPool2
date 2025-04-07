<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    use HasFactory;
    protected $fillable = [
        'titre',
        'description',  
        'statut',
        'recruteur_id'
    ];
    
    public function recruteur()
    {
        return $this->belongsTo(User::class);
    }
}
