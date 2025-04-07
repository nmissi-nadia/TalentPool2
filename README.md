# TalentPool
Développement d’une API Laravel pour gérer les annonces, les candidatures et le suivi des recrutements pour une entreprise.

# API de Gestion des Recrutements

Ce projet est une API RESTful développée avec Laravel pour faciliter la mise en relation entre recruteurs et candidats. L'API permet la gestion des annonces, des candidatures et le suivi des statuts, tout en assurant une sécurité renforcée grâce à des outils comme Sanctum.

## Fonctionnalités

### Gestion des Annonces
- Les recruteurs peuvent :
  - Ajouter des annonces.
  - Modifier et supprimer des annonces.
- Les candidats peuvent :
  - Récupérer la liste des annonces.
  - Consulter les détails d'une annonce.

### Gestion des Candidatures
- Les candidats peuvent :
  - Postuler à une annonce en envoyant un CV et une lettre de motivation.
  - Retirer leur candidature.
- Les recruteurs peuvent :
  - Filtrer et récupérer les candidatures associées à leurs annonces.

### Suivi des Candidatures
- Les recruteurs peuvent :
  - Mettre à jour le statut des candidatures (par exemple : acceptée, refusée, en attente).
- Les candidats :
  - Reçoivent une notification par e-mail lors d'un changement de statut.

### Authentification et Sécurité
- Système d'inscription et de connexion avec **Sanctum**.
- Permissions basées sur **Laravel Gates & Policies**.
- Réinitialisation de mot de passe.
- Les utilisateurs choisissent leur rôle (candidat ou recruteur) lors de l'inscription.

### Statistiques et Rapports
- Les recruteurs peuvent consulter des statistiques sur leurs annonces et candidatures.
- Les administrateurs ont accès à des statistiques globales sur l'utilisation de la plateforme.

## Prérequis

- PHP >= 8.0
- Composer
- MySQL
- Node.js (pour la gestion des dépendances frontend, si nécessaire)
- Laravel 10

## Installation

1. **Cloner le dépôt** :
   ```bash
   git clone https://github.com/nmissi-nadia/TalentPool.git
   cd TalentPool
   ```

2. **Installer les dépendances backend** :
   ```bash
   composer install
   ```

3. **Configurer l'environnement** :
   - Copier le fichier `.env.example` en `.env` :
     ```bash
     cp .env.example .env
     ```
   - Configurer les variables de connexion à la base de données dans le fichier `.env`.

4. **Générer la clé de l'application** :
   ```bash
   php artisan key:generate
   ```

5. **Exécuter les migrations** :
   ```bash
   php artisan migrate
   ```

6. **Démarrer le serveur de développement** :
   ```bash
   php artisan serve
   ```

## Points d'accès API

### Routes principales
| Méthode | Endpoint                | Description                                  |
|---------|-------------------------|----------------------------------------------|
| GET     | /api/annonces           | Récupérer toutes les annonces.              |
| POST    | /api/annonces           | Créer une nouvelle annonce (recruteur).     |
| GET     | /api/annonces/{id}      | Récupérer une annonce spécifique.           |
| PUT     | /api/annonces/{id}      | Mettre à jour une annonce (recruteur).      |
| DELETE  | /api/annonces/{id}      | Supprimer une annonce (recruteur).          |
| POST    | /api/candidatures       | Postuler à une annonce (candidat).          |
| GET     | /api/candidatures       | Récupérer toutes les candidatures.          |
| PUT     | /api/candidatures/{id}  | Mettre à jour une candidature (recruteur).  |
| DELETE  | /api/candidatures/{id}  | Supprimer une candidature (candidat).       |

### Authentification
| Méthode | Endpoint                | Description                                  |
|---------|-------------------------|----------------------------------------------|
| POST    | /api/register           | Inscription d'un utilisateur.               |
| POST    | /api/login              | Connexion d'un utilisateur.                 |
| POST    | /api/logout             | Déconnexion de l'utilisateur.               |

## Tests

Pour exécuter les tests unitaires :
```bash
php artisan test
```

## Structure du Projet

- **Models** :
  - `Annonce` : Gère les annonces publiées par les recruteurs.
  - `Candidature` : Gère les candidatures soumises par les candidats.
- **Controllers** :
  - `AnnonceController` : Gestion des annonces.
  - `CandidatureController` : Gestion des candidatures.
  - `ApiController` : Gestion de l'authentification et de la sécurité.
- **Migrations** : Définit la structure des tables `annonces` et `candidatures`.

## Contribution

1. Forker le dépôt.
2. Créer une branche pour vos modifications :
   ```bash
   git checkout -b ma-branche
   ```
3. Soumettre une pull request.


