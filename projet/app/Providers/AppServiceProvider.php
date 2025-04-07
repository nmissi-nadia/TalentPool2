<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\AnnonceRepositoryInterface;
use App\Interfaces\CandidatureRepositoryInterface;
use App\Repositories\AnnonceRepository;
use App\Repositories\CandidatureRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AnnonceRepositoryInterface::class, AnnonceRepository::class);
        $this->app->bind(CandidatureRepositoryInterface::class, CandidatureRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
