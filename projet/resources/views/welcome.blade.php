@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <!-- <div class="card"> -->
            <div class="card-body text-center">
                <h1 class="display-4 mb-4">Bienvenue sur TalentPool</h1>
                <p class="lead">La plateforme qui connecte les talents avec les meilleures opportunités professionnelles.</p>
                
                <div class="row mt-5">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h3>Vous êtes candidat ?</h3>
                                <p>Trouvez les meilleures opportunités d'emploi et postulez en quelques clics.</p>
                                <a href="/register?role=candidat" class="btn btn-primary">S'inscrire comme candidat</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h3>Vous êtes recruteur ?</h3>
                                <p>Publiez vos offres d'emploi et trouvez les meilleurs talents pour votre entreprise.</p>
                                <a href="/register?role=recruteur" class="btn btn-primary">S'inscrire comme recruteur</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-5">
                    <h2>Comment ça marche ?</h2>
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h4>1. Créez votre compte</h4>
                                    <p>Inscrivez-vous en tant que candidat ou recruteur pour accéder à toutes les fonctionnalités.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h4>2. Explorez les opportunités</h4>
                                    <p>Parcourez les offres d'emploi ou publiez vos annonces selon votre profil.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h4>3. Connectez-vous</h4>
                                    <p>Postulez aux offres ou consultez les candidatures reçues.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-5">
                    <a href="/annonces" class="btn btn-lg btn-primary">Voir les annonces</a>
                </div>
            </div>
        <!-- </div> -->
    </div>
</div>
@endsection