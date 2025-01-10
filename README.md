# Projet Symfony

## Fonctionnalités

- **US1: Création du système d’authentification**
    - [x] L'utilisateur peut s'inscrire avec une adresse email valide et un mot de passe sécurisé (minimum 8 caractères).
    - [x] L'utilisateur peut se connecter avec son email et mot de passe.
    - [ ] Un message d'erreur est affiché en cas de mot de passe incorrect.
    - [x] L'utilisateur peut se déconnecter facilement depuis n'importe quelle page de la plateforme via le bouton prévu.
    - [x] Les mots de passe sont stockés de manière sécurisée (hashés).
- **US2: Ajout d'un livre en cours de lecture**
    - [x] En cliquant sur le bouton, la popup du formulaire s’ouvre.
    - [x] Lorsque le formulaire est validé, les données sont enregistrées en base de données.
    - [ ] Le livre en cours de lecture est ajouté au tableau sans aucun rechargement de page.
- **US4: Affichage du graphique radar**
    - [x] L’image actuellement présente en page d’accueil est remplacée par un vrai graphique avec les données en base de données.
- **US7: Correction du bug d'affichage de mes lectures en cours**
    - [x] Le nom du livre et sa description apparaissent dans le tableau.
- **US8: Affichage de mes lectures terminées dans le tableau de mes lectures**
    - [x] Le template déjà en place est respecté.
    - [x] Les livres lus sont chargés depuis la base de données.

## Prérequis

Avant de commencer, assurez-vous d'avoir installé les outils suivants sur votre machine :

- **PHP** (version >= 8.0)
- **Composer**
- **Symfony CLI**
- **Serveur de base de données** (MySQL)

## Étapes pour démarrer le projet

### 1. Installer les dépendances

```bash
composer install
```

### 2. Configurer l'environnement

```bash
cp .env .env.local
```

Modifiez le fichier .env.local pour configurer les paramètres de votre base de données et autres variables d'environnement :

```bash
DATABASE_URL="mysql://root:mot_de_pase@127.0.0.1:3307/nom_de_votre_base_de_donnees"
```

### 3. Préparer la base de données

Si vous n'avez pas encore cloné le projet, commencez par le faire via Git :

```bash
php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```

### 4. Démarrer le serveur Symfony

```bash
php bin/console server:run
```
