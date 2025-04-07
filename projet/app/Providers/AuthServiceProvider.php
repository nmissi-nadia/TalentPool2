<?php

namespace App\Providers;

use App\Models\Candidature;
use App\Policies\CandidaturePolicy;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    // protected $policies = [
    //     Candidature::class => CandidaturePolicy::class,
    // ];
    // Ajouter Gate

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
