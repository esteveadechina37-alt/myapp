# ✅ CHECKLIST - SYSTÈME DE COMMANDE CLIENT

## 🚀 AVANT DE COMMENCER

- [ ] Vérifier que Laravel est installé: `php -v`
- [ ] Vérifier que Composer est installé: `composer -v`
- [ ] Vérifier que le serveur MySQL/MariaDB est actif
- [ ] Vérifier le fichier `.env` est configuré
- [ ] Vérifier les migrations sont exécutées: `php artisan migrate`

---

## 📋 INSTALLATION

### Étape 1: Préparer l'environnement
```bash
cd "c:\Users\PRIVE\Desktop\Apprentissage\restaurant - gestionTP"
composer install          # Si packages manquants
php artisan cache:clear  # Nettoyer le cache
```

### Étape 2: Vérifier la base de données
```bash
php test_db_count.php
```
✅ Vous devez voir:
- [ ] Catégories: 5
- [ ] Plats: 5
- [ ] Clients: 20
- [ ] Tables: 30

### Étape 3: Vérifier les routes
```bash
php artisan route:list | Select-String "client"
```
✅ Vous devez voir 18 routes `/client/*`

---

## 🧪 TESTS BASIQUES

### Test 1: Lancer le serveur
```bash
php artisan serve
```
✅ Message attendu: "Server running at [http://127.0.0.1:8000]"

### Test 2: Accéder à l'home
```
http://localhost:8000
```
✅ La page d'accueil doit s'afficher

### Test 3: Authentification
```
Créer un compte ou se connecter avec existant
```
✅ Redirection après authentification

### Test 4: Accéder au dashboard client
```
http://localhost:8000/client/dashboard
```
✅ Le dashboard doit s'afficher avec:
- [ ] Titre "Bienvenue"
- [ ] 4 carrés action rapides
- [ ] Statistiques
- [ ] Aucune erreur 500

---

## 📖 TESTS DE FONCTIONNALITÉS

### Test Menu
- [ ] GET `/client/menu` → affiche catégories et plats
- [ ] Recherche fonctionne
- [ ] Filtrage par catégorie fonctionne
- [ ] Aucune erreur dans la console

### Test Panier
- [ ] POST `/client/order/add/1` → ajoute plat
- [ ] GET `/client/cart` → affiche articles
- [ ] Modification quantité fonctionne
- [ ] Suppression article fonctionne
- [ ] Calculs HT/TVA/TTC corrects

### Test Commande
- [ ] GET `/client/checkout` → affiche formulaire
- [ ] POST `/client/checkout` → crée commande en BD
- [ ] GET `/client/order/{id}` → affiche détail
- [ ] Timeline visible
- [ ] Statut correct

### Test Paiement
- [ ] Bouton "Payer" visible (si commande prête)
- [ ] Modal paiement s'affiche
- [ ] POST `/client/payment/{id}` → marque comme payée
- [ ] Facture créée automatiquement
- [ ] Confirmé par message de succès

### Test Historique
- [ ] GET `/client/orders` → liste commandes
- [ ] GET `/client/invoices` → liste factures
- [ ] Pagination fonctionne
- [ ] Liens de détail fonctionnent

---

## 🔍 VÉRIFICATIONS BD

### Créer une commande manuelle (optionnel)
```bash
php artisan tinker
```

```php
$commande = App\Models\Commande::create([
    'client_id' => 1,
    'numero' => 'CMD-TEST-' . now()->format('YmdHis'),
    'type_commande' => 'sur_place',
    'table_id' => 1,
    'statut' => 'enregistree',
    'montant_total_ht' => 10500,
    'montant_tva' => 2058,
    'montant_total_ttc' => 12558,
]);

App\Models\LigneCommande::create([
    'commande_id' => $commande->id,
    'plat_id' => 1,
    'quantite' => 1,
    'prix_unitaire_ht' => 10500,
    'taux_tva' => 19.6,
]);
```

✅ Vérifier dans le dashboard que la commande apparaît

---

## 🐛 DÉPANNAGE

### Erreur: "Route not found"
```bash
php artisan cache:clear
php artisan route:cache --clear
php artisan serve  # Relancer
```

### Erreur: "Connection refused" (DB)
```bash
# Vérifier la connexion
php test_db_count.php

# Vérifier .env
cat .env | Select-String "DB_"

# Relancer MySQL si nécessaire
```

### Erreur: "Class not found"
```bash
composer dump-autoload
php artisan serve
```

### Session cart ne persiste pas
```bash
# Vérifier la configuration session
php artisan tinker
> config('session.driver')  # Doit être 'file' ou 'cookie'
```

### Vues ne s'affichent pas
```bash
# Vérifier que les fichiers blade existent
ls resources/views/client/

# Vérifier la syntaxe
php -l resources/views/client/dashboard.blade.php
```

---

## 📊 PERFORMANCE

### Optimisation
```bash
# Cache les routes
php artisan route:cache

# Cache la config
php artisan config:cache

# Optimise autoloader
composer install --optimize-autoloader --no-dev
```

### Monitoring
```bash
# Voir les queries
php artisan tinker
> DB::enableQueryLog()
> // exécuter requête
> DB::getQueryLog()
```

---

## 🔐 SÉCURITÉ

### Vérifier authentification
- [ ] Ne pas accessibles sans auth
- [ ] Ne voir que ses propres commandes
- [ ] Ne pas modifier commande d'un autre

### Test d'autorisation
```bash
# Connecté comme client 1
GET /client/order/5  (commande de client 2)
# Doit retourner 403 Forbidden
```

---

## 📝 DOCUMENTATION

Fichiers de référence:
- [ ] CLIENT_SYSTEM_GUIDE.md → Guide complet
- [ ] FINAL_RECAP.md → Récapitulatif
- [ ] USEFUL_COMMANDS.txt → Commandes
- [ ] Cette checklist → Vérifications

---

## ✨ PRÊT POUR PRODUCTION?

- [ ] Tous les tests passent
- [ ] Aucune erreur en BD
- [ ] Aucune erreur en logs
- [ ] Authentification fonctionne
- [ ] Paiement fonctionne
- [ ] Factures générées correctement
- [ ] Performance acceptable
- [ ] Documentation complète

---

## 🎯 PROCHAINES ACTIONS

### Immédiat
1. [ ] Lancer `php artisan serve`
2. [ ] Accéder à `/client/dashboard`
3. [ ] Créer une commande complète
4. [ ] Tester le paiement

### Court terme
1. [ ] Améliorer le design (custom CSS)
2. [ ] Ajouter notifications email
3. [ ] Implémenter PDF téléchargement
4. [ ] Ajouter système note/avis

### Moyen terme
1. [ ] Intégrer vrai gateway paiement
2. [ ] Ajouter gestion promotions
3. [ ] Notifications temps réel
4. [ ] Analytics complet

---

## 📞 EN CAS D'URGENCE

1. **Tous les fichiers supprimes?**
   ```bash
   git status  # Vérifier version contrôlée
   git restore .  # Restaurer si possible
   ```

2. **BD corrompue?**
   ```bash
   php artisan migrate:fresh --seed
   ```

3. **Tout cassé?**
   ```bash
   composer install
   php artisan cache:clear
   php artisan migrate
   ```

---

## 🎉 BON DÉVELOPPEMENT!

Le système est prêt. Bienvenue dans le workflow de commande client! 🚀

Toute question → Voir CLIENT_SYSTEM_GUIDE.md
