# 🎯 GUIDE COMPLET - SYSTÈME DE COMMANDE CLIENT

## ✅ État Actuel du Système

### Vérification Complète
```
✅ Contrôleur Client          : app/Http/Controllers/Client/ClientOrderController.php
✅ 7 Vues Blade              : resources/views/client/*
✅ 18 Routes Enregistrées    : /client/*
✅ Base de Données           : Connectée et opérationnelle
✅ Modèles Laravel           : Tous présents et configurés
```

### Statistiques Base de Données
```
- Catégories      : 5
- Plats           : 5
- Clients         : 20
- Tables          : 30
- Commandes       : 0 (à créer via le système)
- Factures        : 0 (générées après paiement)
```

---

## 🚀 DÉMARRAGE RAPIDE

### Étape 1: Lancer le serveur Laravel
```bash
cd "c:\Users\PRIVE\Desktop\Apprentissage\restaurant - gestionTP"
php artisan serve
```

Le serveur démarre sur `http://localhost:8000`

### Étape 2: S'authentifier
1. Aller sur `http://localhost:8000`
2. Se connecter avec les identifiants clients existants
3. Après authentification, accéder à `/client/dashboard`

### Étape 3: Accéder au Dashboard Client
```
http://localhost:8000/client/dashboard
```

---

## 📋 ROUTES CLIENT DISPONIBLES

| Route | Méthode | Description |
|-------|---------|-------------|
| `/client/dashboard` | GET | Tableau de bord principal |
| `/client/menu` | GET | Menu avec tous les plats |
| `/client/cart` | GET | Panier d'achat |
| `/client/checkout` | GET/POST | Formulaire de commande |
| `/client/order/{id}` | GET | Détail d'une commande |
| `/client/orders` | GET | Historique des commandes |
| `/client/invoices` | GET | Liste des factures |
| `/client/order/add/{platId}` | POST | Ajouter au panier |
| `/client/order/cart/update/{platId}` | POST | Modifier quantité |
| `/client/order/remove/{platId}` | POST | Supprimer du panier |
| `/client/order/clear` | POST | Vider le panier |
| `/client/payment/{commandeId}` | POST | Traiter le paiement |
| `/client/invoice/{id}/download` | GET | Télécharger facture |

---

## 💼 FLUX COMPLET D'UNE COMMANDE

### 1️⃣ Parcourir le Menu
```
GET /client/menu
├─ Affiche toutes les catégories
├─ Affiche tous les plats disponibles
├─ Recherche en temps réel
└─ Filtrage par catégorie
```

### 2️⃣ Ajouter au Panier
```
POST /client/order/add/{platId}
├─ Vérifie la disponibilité du plat
├─ Ajoute au panier (session)
├─ Retourne le nombre d'articles
└─ Affiche notification de succès
```

### 3️⃣ Voir le Panier
```
GET /client/cart
├─ Affiche tous les articles
├─ Permet modifier les quantités
├─ Calcule HT, TVA (19.6%), TTC
└─ Lien vers checkout
```

### 4️⃣ Checkout
```
GET/POST /client/checkout
├─ Sélection du type de commande:
│  ├─ Sur place (choisir table)
│  ├─ À emporter
│  └─ Livraison (adresse)
├─ Informations client pré-remplies
├─ Commentaires optionnels
└─ Création de la commande en BD
```

### 5️⃣ Détail de Commande
```
GET /client/order/{id}
├─ Affiche statut progression
├─ Timeline visuelle du statut
├─ Articles commandés
├─ Montants HT/TVA/TTC
└─ Options: Payer, Imprimer, Annuler
```

### 6️⃣ Paiement
```
POST /client/payment/{commandeId}
├─ Choix du moyen de paiement:
│  ├─ Carte bancaire
│  ├─ Espèces
│  ├─ Mobile money
│  └─ Chèque
├─ Crée une facture (Facture)
├─ Marque commande comme payée
└─ Confirmation au client
```

### 7️⃣ Historique & Factures
```
GET /client/orders         → Toutes les commandes
GET /client/invoices       → Toutes les factures
GET /client/invoice/{id}/download → Télécharger PDF
```

---

## 📊 STRUCTURE DES DONNÉES

### Commande
```php
[
    'numero'              => 'CMD-20251229-1234',
    'client_id'          => 1,
    'table_id'           => 5,  // si sur_place
    'type_commande'      => 'sur_place|a_emporter|livraison',
    'statut'             => 'enregistree|en_preparation|prete|servie|payee',
    'montant_total_ht'   => 10500.00,
    'montant_tva'        => 2058.00,  // 19.6%
    'montant_total_ttc'  => 12558.00,
    'est_payee'          => false,
    'moyen_paiement'     => 'carte|especes|mobile|cheque',
    'commentaires'       => 'Pas de piment svp'
]
```

### LigneCommande
```php
[
    'commande_id'       => 1,
    'plat_id'          => 3,
    'quantite'         => 2,
    'prix_unitaire_ht' => 5250.00,
    'taux_tva'         => 19.6,
    'statut'           => 'en_preparation|prete'
]
```

### Facture
```php
[
    'commande_id'   => 1,
    'montant_ttc'   => 12558.00,
    'est_payee'     => true,
    'date_paiement' => '2025-12-29 14:30:00'
]
```

---

## 🔒 SÉCURITÉ

### Authentification
- Routes sous middleware `auth`
- Vérification User → Client via `user_id`
- Chaque client ne voit que ses propres commandes

### Autorisations
- Propriété de commande vérifiée (abort 403 si accès non autorisé)
- Annulation limitée aux commandes en état enregistrée/en_preparation
- Paiement possible que si commande prête et non payée

### Session Cart
- Stocké en session (clé: `cart`)
- Format: `['platId' => quantité]`
- Validé à chaque opération
- Vidé après création de commande

---

## 🧪 TESTS

### Test 1: Accéder au Menu
```
1. GET /client/menu
2. Vérifier affichage de 5 catégories
3. Vérifier affichage de 5 plats
4. Rechercher "Riz"
```

### Test 2: Ajouter au Panier
```
1. POST /client/order/add/1 (platId=1)
2. Vérifier réponse JSON avec count cart
3. POST /client/order/add/1 (2ème article)
4. GET /client/cart (voir les 2 articles)
```

### Test 3: Créer une Commande
```
1. GET /client/checkout
2. Sélectionner type "sur_place"
3. Sélectionner table
4. POST /client/checkout
5. Redirection vers /client/order/{id}
```

### Test 4: Paiement
```
1. GET /client/order/{id}
2. Voir bouton "Payer"
3. POST /client/payment/{id} (avec moyen_paiement)
4. Vérifier Facture créée en BD
```

---

## 🐛 DÉPANNAGE

### Erreur: "Route not found" pour /client/dashboard
```bash
# Effacer le cache des routes
php artisan route:cache --clear
php artisan cache:clear

# Relancer le serveur
php artisan serve
```

### Erreur: "Client model not found"
```bash
# Vérifier que le modèle existe
ls app/Models/Client.php

# Vérifier les relations en BD
php test_db_count.php
```

### Erreur: "Panier vide"
```bash
# Vérifier que les plats existent et sont disponibles
php test_db_count.php

# Vérifier que le plat a est_disponible = true
php artisan tinker
> App\Models\Plat::where('est_disponible', false)->count()
```

---

## 📝 FICHIERS CLÉS

### Contrôleur
- [`app/Http/Controllers/Client/ClientOrderController.php`](../../app/Http/Controllers/Client/ClientOrderController.php) (362 lignes)

### Vues
- [`resources/views/client/dashboard.blade.php`](../../resources/views/client/dashboard.blade.php) - Tableau de bord
- [`resources/views/client/menu.blade.php`](../../resources/views/client/menu.blade.php) - Menu
- [`resources/views/client/cart.blade.php`](../../resources/views/client/cart.blade.php) - Panier
- [`resources/views/client/checkout.blade.php`](../../resources/views/client/checkout.blade.php) - Commande
- [`resources/views/client/order-detail.blade.php`](../../resources/views/client/order-detail.blade.php) - Détail
- [`resources/views/client/order-history.blade.php`](../../resources/views/client/order-history.blade.php) - Historique
- [`resources/views/client/invoices.blade.php`](../../resources/views/client/invoices.blade.php) - Factures

### Routes
- [`routes/web.php`](../../routes/web.php) - Lignes 155-183

### Modèles
- `app/Models/Commande.php`
- `app/Models/LigneCommande.php`
- `app/Models/Plat.php`
- `app/Models/Client.php`
- `app/Models/TableRestaurant.php`
- `app/Models/Facture.php`
- `app/Models/Categorie.php`

---

## ✨ PROCHAINES AMÉLIORATIONS

- [ ] Implémentation du téléchargement PDF pour factures
- [ ] Notifications en temps réel pour statut commande
- [ ] Historique détaillé des modifications
- [ ] Export CSV des commandes
- [ ] Intégration paiement réel (Stripe, etc.)
- [ ] Système de notation/avis
- [ ] Programmation de commandes futures
- [ ] Coupons et codes promotionnels

---

## 📞 SUPPORT

En cas de problème:
1. Vérifier `php test_db_count.php`
2. Vérifier `php artisan route:list | grep client`
3. Vérifier logs en `storage/logs/laravel.log`
4. Utiliser `php artisan tinker` pour debug
