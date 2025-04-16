@extends('layouts.app')

@section('title', 'Dashboard Admin')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js">
    <style>
        .dashboard-card {
            transition: transform 0.3s ease;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        .stat-card {
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 1rem;
            border-radius: 10px;
        }
        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        .stat-number {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .stat-label {
            color: #6c757d;
        }
        .chart-container {
            height: 300px;
            margin: 1rem 0;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2 mb-3">Dashboard Admin</h1>
        </div>
    </div>

    <!-- Statistiques principales -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card bg-primary text-white">
                <div class="card-body">
                    <i class="fas fa-bullhorn stat-icon"></i>
                    <h3 class="stat-number" id="total-annonces">0</h3>
                    <p class="stat-label">Total Annonces</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-success text-white">
                <div class="card-body">
                    <i class="fas fa-user-check stat-icon"></i>
                    <h3 class="stat-number" id="total-candidatures">0</h3>
                    <p class="stat-label">Total Candidatures</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-warning text-dark">
                <div class="card-body">
                    <i class="fas fa-exclamation-triangle stat-icon"></i>
                    <h3 class="stat-number" id="annonces-inactives">0</h3>
                    <p class="stat-label">Annonces Inactives</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-danger text-white">
                <div class="card-body">
                    <i class="fas fa-exclamation-circle stat-icon"></i>
                    <h3 class="stat-number" id="candidatures-inactives">0</h3>
                    <p class="stat-label">Candidatures Inactives</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Évolution des Annonces</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="annoncesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Répartition des Candidatures</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="candidaturesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gestion des Annonces -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Gestion des Annonces Inactives</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Entreprise</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="annonces-inactives-tbody">
                        <!-- Les annonces inactives seront chargées ici via JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Gestion des Candidatures -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Gestion des Candidatures Inactives</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Candidat</th>
                            <th>Annonce</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="candidatures-inactives-tbody">
                        <!-- Les candidatures inactives seront chargées ici via JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Configuration Axios pour les requêtes CSRF
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;

        // Variables pour les graphiques
        let annoncesChart, candidaturesChart;
        let datesAnnonces = [];
        let nombreAnnonces = [];
        let statutsCandidatures = [];
        let nombreCandidatures = [];

        // Chargement des données au chargement de la page
        document.addEventListener('DOMContentLoaded', () => {
            chargerStatistiques();
            chargerAnnoncesInactives();
            chargerCandidaturesInactives();
        });

        // Fonction pour charger les statistiques
        async function chargerStatistiques() {
            try {
                // Récupérer les stats des annonces
                const responseAnnonces = await axios.get('/api/stats/annonces');
                const dataAnnonces = responseAnnonces.data;
                
                // Récupérer les stats des candidatures
                const responseCandidatures = await axios.get('/api/stats/candidatures');
                const dataCandidatures = responseCandidatures.data;
                
                // Mettre à jour les statistiques principales
                document.getElementById('total-annonces').textContent = dataAnnonces['total annonces'] || 0;
                document.getElementById('total-candidatures').textContent = dataCandidatures['total candidatures'] || 0;
                
                // Compter les annonces inactives
                let annoncesInactives = 0;
                if (dataAnnonces['statistiques par statut']) {
                    const statInactif = dataAnnonces['statistiques par statut'].find(s => s.statut === 'inactif' || s.statut === 'fermée');
                    if (statInactif) {
                        annoncesInactives = statInactif.count;
                    }
                }
                document.getElementById('annonces-inactives').textContent = annoncesInactives;
                
                // Compter les candidatures inactives
                let candidaturesInactives = 0;
                if (dataCandidatures['statistiques par statut']) {
                    const statInactif = dataCandidatures['statistiques par statut'].find(s => s.statut === 'inactif' || s.statut === 'refusée' || s.statut === 'refusee');
                    if (statInactif) {
                        candidaturesInactives = statInactif.count;
                    }
                }
                document.getElementById('candidatures-inactives').textContent = candidaturesInactives;
                
                // Préparer les données pour les graphiques
                if (dataAnnonces['statistiques par statut']) {
                    statutsCandidatures = dataCandidatures['statistiques par statut'].map(s => s.statut);
                    nombreCandidatures = dataCandidatures['statistiques par statut'].map(s => s.count);
                    
                    // Simulation d'évolution temporelle pour les annonces (dans un cas réel, on pourrait avoir des données datées)
                    datesAnnonces = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin']; 
                    nombreAnnonces = [
                        Math.floor(Math.random() * 10),
                        Math.floor(Math.random() * 10) + 5,
                        Math.floor(Math.random() * 10) + 8,
                        Math.floor(Math.random() * 10) + 12,
                        Math.floor(Math.random() * 10) + 15,
                        dataAnnonces['total annonces']
                    ];
                }
                
                // Initialiser les graphiques
                initialiserGraphiques();
                
            } catch (error) {
                console.error('Erreur lors du chargement des statistiques:', error);
                alert('Une erreur est survenue lors du chargement des statistiques.');
            }
        }

        // Fonction pour charger les annonces inactives
        async function chargerAnnoncesInactives() {
            try {
                const response = await axios.get('/api/annonces');
                const annonces = response.data;
                
                const tbody = document.getElementById('annonces-inactives-tbody');
                tbody.innerHTML = '';
                
                annonces.filter(annonce => annonce.statut === 'inactif' || annonce.statut === 'fermée').forEach(annonce => {
                    const tr = document.createElement('tr');
                    tr.dataset.id = annonce.id;
                    
                    const dateCreation = new Date(annonce.created_at).toLocaleDateString('fr-FR');
                    
                    tr.innerHTML = `
                        <td>${annonce.titre}</td>
                        <td>${annonce.entreprise?.nom || 'Non spécifié'}</td>
                        <td>${dateCreation}</td>
                        <td>
                            <button class="btn btn-danger btn-sm" onclick="supprimerAnnonce(${annonce.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    `;
                    
                    tbody.appendChild(tr);
                });
            } catch (error) {
                console.error('Erreur lors du chargement des annonces:', error);
                alert('Une erreur est survenue lors du chargement des annonces.');
            }
        }

        // Fonction pour charger les candidatures inactives
        async function chargerCandidaturesInactives() {
            try {
                const response = await axios.get('/api/candidatures');
                const candidatures = response.data;
                
                const tbody = document.getElementById('candidatures-inactives-tbody');
                tbody.innerHTML = '';
                
                candidatures.filter(candidature => candidature.statut === 'inactif' || candidature.statut === 'refusée' || candidature.statut === 'refusee').forEach(candidature => {
                    const tr = document.createElement('tr');
                    tr.dataset.id = candidature.id;
                    
                    const dateCreation = new Date(candidature.created_at).toLocaleDateString('fr-FR');
                    
                    tr.innerHTML = `
                        <td>${candidature.user?.name || candidature.candidat?.name || 'Non spécifié'}</td>
                        <td>${candidature.annonce?.titre || 'Non spécifié'}</td>
                        <td>${dateCreation}</td>
                        <td>
                            <button class="btn btn-danger btn-sm" onclick="supprimerCandidature(${candidature.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    `;
                    
                    tbody.appendChild(tr);
                });
            } catch (error) {
                console.error('Erreur lors du chargement des candidatures:', error);
                alert('Une erreur est survenue lors du chargement des candidatures.');
            }
        }

        // Fonction pour supprimer une annonce
        async function supprimerAnnonce(id) {
            if (confirm('Voulez-vous vraiment supprimer cette annonce ?')) {
                try {
                    await axios.delete(`/api/annonces/${id}`);
                    
                    // Supprimer la ligne du tableau
                    const tr = document.querySelector(`#annonces-inactives-tbody tr[data-id="${id}"]`);
                    if (tr) tr.remove();
                    
                    // Recharger les statistiques
                    chargerStatistiques();
                    
                    alert('Annonce supprimée avec succès.');
                } catch (error) {
                    console.error('Erreur lors de la suppression de l\'annonce:', error);
                    alert('Une erreur est survenue lors de la suppression de l\'annonce.');
                }
            }
        }

        // Fonction pour supprimer une candidature
        async function supprimerCandidature(id) {
            if (confirm('Voulez-vous vraiment supprimer cette candidature ?')) {
                try {
                    await axios.delete(`/api/candidatures/${id}`);
                    
                    // Supprimer la ligne du tableau
                    const tr = document.querySelector(`#candidatures-inactives-tbody tr[data-id="${id}"]`);
                    if (tr) tr.remove();
                    
                    // Recharger les statistiques
                    chargerStatistiques();
                    
                    alert('Candidature supprimée avec succès.');
                } catch (error) {
                    console.error('Erreur lors de la suppression de la candidature:', error);
                    alert('Une erreur est survenue lors de la suppression de la candidature.');
                }
            }
        }

        // Fonction pour initialiser les graphiques
        function initialiserGraphiques() {
            // Graphique des annonces
            const ctxAnnonces = document.getElementById('annoncesChart').getContext('2d');
            
            // Détruire le graphique existant s'il existe
            if (window.annoncesChart) {
                window.annoncesChart.destroy();
            }
            
            window.annoncesChart = new Chart(ctxAnnonces, {
                type: 'line',
                data: {
                    labels: datesAnnonces,
                    datasets: [{
                        label: 'Nombre d\'annonces',
                        data: nombreAnnonces,
                        borderColor: '#1e88e5',
                        backgroundColor: 'rgba(30, 136, 229, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#1e88e5',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#1e88e5',
                        pointHoverBorderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Évolution des Annonces'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            // Graphique des candidatures
            const ctxCandidatures = document.getElementById('candidaturesChart').getContext('2d');
            
            // Détruire le graphique existant s'il existe
            if (window.candidaturesChart) {
                window.candidaturesChart.destroy();
            }
            
            window.candidaturesChart = new Chart(ctxCandidatures, {
                type: 'doughnut',
                data: {
                    labels: statutsCandidatures,
                    datasets: [{
                        data: nombreCandidatures,
                        backgroundColor: [
                            '#1e88e5',
                            '#43a047',
                            '#ffa726',
                            '#ef5350'
                        ],
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                boxWidth: 12,
                                padding: 15
                            }
                        },
                        title: {
                            display: true,
                            text: 'Répartition des Candidatures'
                        }
                    }
                }
            });
        }
    </script>
@endpush