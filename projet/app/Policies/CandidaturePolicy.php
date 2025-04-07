<?php

namespace App\Policies;

use App\Models\User;

class CandidaturePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }
    public function view(User $user, Candidature $candidature)
    {
        return $user->role === 'admin' || $user->id === $candidature->user_id;
    }

    public function create(User $user)
    {
        return $user->role === 'recruteur';
    }
}
