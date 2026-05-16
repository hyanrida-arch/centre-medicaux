# 🏥 Centre Médicaux — Système de Gestion de Rendez-vous

## Installation

### 1. Créer le projet Laravel
```bash
composer create-project laravel/laravel centre-medicaux
cd centre-medicaux
```

### 2. Copier tous les fichiers de ce projet dans le dossier

### 3. Installer les dépendances
```bash
composer install
```

### 4. Configurer l'environnement
```bash
cp .env.example .env
php artisan key:generate
```
Modifier `.env` : `DB_DATABASE=centre_medicaux`

### 5. Créer la base de données
Dans phpMyAdmin : `CREATE DATABASE centre_medicaux;`

### 6. Migrations + Seed
```bash
php artisan migrate --seed
php artisan storage:link
```

### 7. Lancer
```bash
php artisan serve
```
→ http://localhost:8000

## Comptes de test
| Rôle | Email | Mot de passe |
|------|-------|-------------|
| Médecin | ahmed.benali@medecin.ma | password |
| Patient | mohammed.idrissi@patient.ma | password |

## Répartition des tâches
| Membre | Tâches |
|--------|--------|
| [Nom 1] | Migrations, Models, Auth |
| [Nom 2] | Controllers, Routes |
| [Nom 3] | Views, Design |
| [Nom 4] | Seeders, Tests, README |
