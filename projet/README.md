# TalentPool

TalentPool est une plateforme de recrutement qui connecte les candidats avec les recruteurs. Cette application permet aux recruteurs de publier des offres d'emploi et aux candidats de postuler à ces offres.

## Structure du projet

Le projet est divisé en deux parties principales :

1. **Backend (API)** : Une API RESTful construite avec Laravel qui gère les données et l'authentification.
2. **Frontend** : Une interface utilisateur construite avec Blade et JavaScript natif qui consomme l'API.

## Fonctionnalités

### Gestion des Annonces
- Affichage de la liste des annonces avec filtres et recherche
- Création, modification et suppression d'annonces (pour les recruteurs)
- Vue détaillée des annonces

### Gestion des Candidatures
- Envoi de candidatures avec CV et lettre de motivation
- Liste des candidatures envoyées avec option de retrait
- Espace recruteur pour voir et filtrer les candidatures

### Suivi des Candidatures
- Affichage du statut de chaque candidature (en attente, acceptée, refusée)
- Système de notifications visuelles

### Authentification et Sécurité
- Inscription avec choix de rôle (candidat ou recruteur)
- Connexion avec email et mot de passe
- Réinitialisation du mot de passe
- Authentification JWT pour l'API

### Statistiques et Rapports
- Tableau de bord pour les candidats avec statistiques sur leurs candidatures
- Tableau de bord pour les recruteurs avec statistiques sur leurs annonces et candidatures reçues

## Installation

### Prérequis
- PHP 8.1 ou supérieur
- Composer
- MySQL ou PostgreSQL
- Node.js et NPM (pour la compilation des assets)

### Étapes d'installation

1. Cloner le dépôt :
```bash
git clone https://github.com/votre-utilisateur/talentpool.git
cd talentpool
```

2. Installer les dépendances PHP :
```bash
composer install
```

3. Copier le fichier d'environnement :
```bash
cp .env.example .env
```

4. Configurer la base de données dans le fichier `.env`

5. Générer la clé d'application :
```bash
php artisan key:generate
```

6. Générer la clé JWT :
```bash
php artisan jwt:secret
```

7. Exécuter les migrations et les seeders :
```bash
php artisan migrate --seed
```

8. Démarrer le serveur de développement :
```bash
php artisan serve
```

## Utilisation

### Accès à l'application

Accédez à l'application via l'URL : `http://localhost:8000`

### Comptes de test

- **Candidat** : candidat@example.com / password
- **Recruteur** : recruteur@example.com / password
- **Admin** : admin@example.com / password

## Structure du Frontend

Le frontend est organisé comme suit :

- `resources/views/layouts/app.blade.php` : Template principal
- `resources/views/welcome.blade.php` : Page d'accueil
- `resources/views/auth/` : Pages d'authentification
- `resources/views/annonces/` : Pages de gestion des annonces
- `resources/views/dashboard/` : Pages des tableaux de bord

## API Endpoints

### Authentification
- `POST /api/register` : Inscription
- `POST /api/login` : Connexion
- `POST /api/logout` : Déconnexion
- `POST /api/password/reset` : Réinitialisation du mot de passe

### Annonces
- `GET /api/annonces` : Liste des annonces
- `GET /api/annonces/{id}` : Détails d'une annonce
- `POST /api/annonces` : Création d'une annonce (recruteur)
- `PUT /api/annonces/{id}` : Modification d'une annonce (recruteur)
- `DELETE /api/annonces/{id}` : Suppression d'une annonce (recruteur)

### Candidatures
- `GET /api/candidatures` : Liste des candidatures de l'utilisateur
- `GET /api/candidatures/{id}` : Détails d'une candidature
- `POST /api/candidatures` : Envoi d'une candidature (candidat)
- `DELETE /api/candidatures/{id}` : Retrait d'une candidature (candidat)
- `PUT /api/candidatures/{id}/status` : Mise à jour du statut d'une candidature (recruteur)

## Technologies utilisées

- **Backend** : Laravel, JWT Auth
- **Frontend** : HTML, CSS, JavaScript, Bootstrap 5
- **Base de données** : MySQL/PostgreSQL

## Développement

### Structure du code JavaScript

Le code JavaScript est organisé de manière modulaire dans chaque vue Blade. Chaque page contient son propre script qui gère les fonctionnalités spécifiques à cette page.

Les fonctionnalités communes (authentification, notifications) sont définies dans le template principal (`app.blade.php`).

### Gestion de l'authentification

L'authentification est gérée via JWT (JSON Web Tokens). Le token est stocké dans le localStorage du navigateur et est envoyé avec chaque requête API via l'en-tête Authorization.

### Gestion des erreurs

Les erreurs sont gérées de manière centralisée avec des messages d'erreur clairs pour l'utilisateur. Les erreurs d'API sont affichées dans des alertes Bootstrap.

## Contribution

Pour contribuer au projet, veuillez suivre les étapes suivantes :

1. Forker le dépôt
2. Créer une branche pour votre fonctionnalité (`git checkout -b feature/ma-fonctionnalite`)
3. Committer vos changements (`git commit -m 'feat: ajout de ma fonctionnalité'`)
4. Pousser vers la branche (`git push origin feature/ma-fonctionnalite`)
5. Ouvrir une Pull Request

## Licence

Ce projet est sous licence MIT. Voir le fichier LICENSE pour plus de détails.