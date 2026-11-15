# Electronics E-commerce — PHP OOP & MySQL

Site e-commerce dynamique de vente d'appareils électroniques, avec un espace client et un espace administrateur.

![PHP](https://img.shields.io/badge/PHP-OOP-777BB4) ![MySQL](https://img.shields.io/badge/MySQL-PDO-4479A1) ![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E)

## Fonctionnalités

**Côté client**
- Inscription, connexion et mise à jour du profil
- Catalogue par catégories, recherche et aperçu rapide d'un produit
- Panier, liste de souhaits et passage de commande (checkout)
- Suivi des commandes et formulaire de contact

**Côté administrateur**
- Tableau de bord
- Gestion des produits (ajout, modification, suppression, upload d'images)
- Gestion des commandes, des utilisateurs, des administrateurs et des messages

## Architecture

```
models/       classes métier (POO) : Database, products, cart, orders, users, wishlist…
views/        pages client et administrateur
components/   connexion PDO, headers, footer, logique panier / wishlist
css/ js/      styles et scripts
```

## Installation

1. Copier le projet dans `htdocs` (XAMPP) ou `www` (WAMP)
2. Créer la base de données MySQL `shop_db`
3. Adapter les identifiants dans `components/connect.php`
4. Ouvrir `http://localhost/ecommerce-electronics/views/home.php`

## Technologies

PHP (POO, PDO) · MySQL · HTML/CSS · JavaScript
