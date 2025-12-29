# 🛠️ COMMANDES UTILES - Test & Debug du Workflow

## Commandes de Base Laravel

```bash
# Démarrer le serveur
php artisan serve

# Voir toutes les routes
php artisan route:list

# Voir les routes du workflow
php artisan route:list | grep -E "(client|cuisinier|serveur)" 

# Tenter une migration si besoin
php artisan migrate

# Voir les erreurs récentes
tail -f storage/logs/laravel.log
```

---

## Commandes Tinker (Laravel Shell)

```bash
php artisan tinker
```

### Voir les Commandes Créées:

```php
# Toutes les commandes
>>> Commande::all();

# Commandes avec relations
>>> Commande::with('client', 'lignesCommandes.plat', 'facture')->get();

# Commandes en preparation
>>> Commande::where('statut', 'en_preparation')->get();

# Commandes pretes
>>> Commande::where('statut', 'prete')->get();

# Commandes servies
>>> Commande::where('statut', 'servie')->get();

# Commandes payees
>>> Commande::where('est_payee', true)->get();
```

### Voir les Factures:

```php
# Toutes les factures
>>> Facture::all();

# Factures avec commande
>>> Facture::with('commande.client', 'commande.lignesCommandes.plat')->get();

# Factures d'un client spécifique
>>> Facture::whereHas('commande', fn($q) => $q->where('client_id', 1))->get();
```

### Voir les Lignes de Commande:

```php
# Toutes les lignes
>>> LigneCommande::all();

# Lignes d'une commande spécifique
>>> LigneCommande::where('commande_id', 1)->with('plat')->get();
```

### Modifier les Statuts (Test):

```php
# Récupérer une commande
>>> $cmd = Commande::find(1);

# Voir son statut
>>> $cmd->statut;

# Changer le statut
>>> $cmd->update(['statut' => 'prete']);
>>> $cmd->save();

# Vérifier le changement
>>> $cmd->refresh()->statut;
```

### Créer une Facture Manuellement (Test):

```php
# Obtenir une commande
>>> $cmd = Commande::find(1);

# Créer une facture
>>> Facture::create(['commande_id' => 1, 'montant_ttc' => $cmd->montant_total_ttc, 'est_payee' => true, 'date_paiement' => now()]);

# Vérifier
>>> Facture::where('commande_id', 1)->first();
```

### Voir les Clients:

```php
# Tous les clients
>>> Client::all();

# Client avec ses commandes
>>> Client::with('commandes')->find(1);

# Commandes d'un client
>>> Client::find(1)->commandes;
```

### Effacer des Données (Test/Cleanup):

```php
# ⚠️ ATTENTION: Ceci supprime les données!

# Effacer une commande et ses lignes
>>> Commande::find(1)->delete();

# Effacer une facture
>>> Facture::find(1)->delete();

# Effacer TOUTES les commandes
>>> Commande::truncate();

# Effacer TOUTES les factures
>>> Facture::truncate();
```

---

## Tests via HTTP (cURL ou Postman)

### Créer une Commande (POST):

```bash
curl -X POST http://localhost:8000/client/checkout \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -H "X-CSRF-TOKEN: $(grep -oP '(?<=csrf-token" content=")[^"]+' /tmp/cookies)" \
  -b /tmp/cookies \
  -c /tmp/cookies \
  -d "type_commande=sur_place&table_id=1&adresse_livraison=&commentaires=test"
```

### Effectuer un Paiement (POST):

```bash
curl -X POST http://localhost:8000/client/payment/1 \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: $(grep -oP '(?<=csrf-token" content=")[^"]+' /tmp/cookies)" \
  -b /tmp/cookies \
  -d '{"payment_method":"carte"}'
```

### Télécharger une Facture (GET):

```bash
curl -X GET http://localhost:8000/client/invoice/1/download \
  -b /tmp/cookies \
  -o facture.html

# Ouvrir le fichier HTML
open facture.html  # macOS
xdg-open facture.html  # Linux
```

---

## Debugging & Logs

### Voir les Logs Laravel:

```bash
# Dernières 100 lignes
tail -n 100 storage/logs/laravel.log

# Logs en temps réel (suivi)
tail -f storage/logs/laravel.log

# Filtrer par erreur
grep "ERROR\|Exception" storage/logs/laravel.log

# Dernière erreur
tail -n 1 storage/logs/laravel.log
```

### Vérifier la Base de Données:

```bash
# Via SQLite (si utilisé)
sqlite3 database/database.sqlite

# Via MySQL (si utilisé)
mysql -u root -p database_name

# Voir les commandes
SELECT * FROM commandes;

# Voir les factures
SELECT * FROM factures;

# Voir les lignes de commande
SELECT * FROM lignes_commandes;

# Voir les clients
SELECT * FROM clients;
```

### Requête SQL Utile:

```sql
-- Voir le workflow complet d'une commande
SELECT 
  c.id, 
  c.numero, 
  cli.nom as client, 
  c.statut, 
  c.est_payee,
  f.id as facture_id,
  COUNT(lc.id) as nb_articles
FROM commandes c
LEFT JOIN clients cli ON c.client_id = cli.id
LEFT JOIN factures f ON c.id = f.commande_id
LEFT JOIN lignes_commandes lc ON c.id = lc.commande_id
GROUP BY c.id
ORDER BY c.created_at DESC;
```

---

## Test d'Intégration Manuel

### Scénario Complet avec Logs:

```bash
# Terminal 1: Serveur Laravel
php artisan serve

# Terminal 2: Logs en temps réel
tail -f storage/logs/laravel.log

# Terminal 3: Tests
php artisan tinker

# Créer une commande
>>> $client = Client::first();
>>> $cmd = Commande::create([
    'numero' => 'TEST-' . now()->timestamp,
    'client_id' => $client->id,
    'table_id' => 1,
    'type_commande' => 'sur_place',
    'statut' => 'en_preparation',
    'montant_total_ht' => 5000,
    'montant_tva' => 980,
    'montant_total_ttc' => 5980,
    'est_payee' => false,
    'heure_commande' => now()
]);

# Voir la commande créée
>>> $cmd;

# Ajouter une ligne
>>> LigneCommande::create([
    'commande_id' => $cmd->id,
    'plat_id' => 1,
    'quantite' => 2,
    'prix_unitaire_ht' => 2500,
    'taux_tva' => 19.6,
    'statut' => 'en_attente'
]);

# Voir dans les logs du Terminal 2

# Marquer comme prête
>>> $cmd->update(['statut' => 'prete']);

# Marquer comme servie
>>> $cmd->update(['statut' => 'servie']);

# Effectuer le paiement
>>> $cmd->update(['est_payee' => true, 'moyen_paiement' => 'carte']);

# Créer la facture
>>> Facture::create(['commande_id' => $cmd->id, 'montant_ttc' => 5980, 'est_payee' => true, 'date_paiement' => now()]);

# Vérifier tout
>>> $cmd->with('lignesCommandes.plat', 'facture', 'client')->first();
```

---

## Scripts de Test Rapide

### Test 1: Créer Workflow Complet

```bash
cat > test_workflow.php << 'EOF'
<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Commande;
use App\Models\Client;
use App\Models\LigneCommande;
use App\Models\Facture;
use Illuminate\Support\Facades\Auth;

// Récupérer ou créer un client
$client = Client::first() ?? Client::create([
    'email' => 'test@example.com',
    'nom' => 'Test',
    'prenom' => 'Client'
]);

// Créer une commande
$cmd = Commande::create([
    'numero' => 'WORKFLOW-' . time(),
    'client_id' => $client->id,
    'type_commande' => 'sur_place',
    'table_id' => 1,
    'montant_total_ht' => 10000,
    'montant_tva' => 1960,
    'montant_total_ttc' => 11960,
    'statut' => 'en_preparation',
    'est_payee' => false,
]);

echo "✓ Commande créée: #" . $cmd->numero . "\n";

// Ajouter une ligne
LigneCommande::create([
    'commande_id' => $cmd->id,
    'plat_id' => 1,
    'quantite' => 2,
    'prix_unitaire_ht' => 5000,
    'taux_tva' => 19.6,
]);

echo "✓ Ligne de commande ajoutée\n";

// Changer statut
$cmd->update(['statut' => 'prete']);
echo "✓ Marqué comme prête\n";

$cmd->update(['statut' => 'servie']);
echo "✓ Marqué comme servie\n";

// Payer
$cmd->update(['est_payee' => true, 'moyen_paiement' => 'carte']);
echo "✓ Commande payée\n";

// Créer facture
Facture::create([
    'commande_id' => $cmd->id,
    'montant_ttc' => $cmd->montant_total_ttc,
    'est_payee' => true,
    'date_paiement' => now()
]);

echo "✓ Facture créée\n";
echo "\n✅ Workflow complet réussi!\n";
echo "Commande ID: " . $cmd->id . "\n";
echo "Facture ID: " . $cmd->facture->id . "\n";
EOF

php test_workflow.php
```

### Test 2: Vérifier Visibilité

```bash
cat > test_visibility.php << 'EOF'
<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Commande;

echo "=== COMMANDES PAR STATUT ===\n";
echo "en_preparation: " . Commande::where('statut', 'en_preparation')->count() . "\n";
echo "prete: " . Commande::where('statut', 'prete')->count() . "\n";
echo "servie: " . Commande::where('statut', 'servie')->count() . "\n";
echo "payees: " . Commande::where('est_payee', true)->count() . "\n";
echo "avec facture: " . Commande::whereHas('facture')->count() . "\n";
EOF

php test_visibility.php
```

---

## Points de Contrôle (Checkpoints)

### ✅ Checkpoint 1: Base de Données
```bash
php artisan tinker
>>> Commande::count();  # Doit être > 0
>>> Facture::count();   # Doit être > 0
>>> LigneCommande::count();  # Doit être > 0
```

### ✅ Checkpoint 2: Routes
```bash
php artisan route:list | grep "client.store-order"
php artisan route:list | grep "cuisinier.marquer-prete"
php artisan route:list | grep "serveur.servir"
php artisan route:list | grep "client.download-invoice"
```

### ✅ Checkpoint 3: Vues
```bash
# Vérifier que les vues existent
ls -la resources/views/client/facture-pdf.blade.php
ls -la resources/views/client/order-detail.blade.php
ls -la resources/views/client/invoices.blade.php
```

### ✅ Checkpoint 4: Controllers
```bash
# Vérifier les méthodes
grep -n "downloadInvoice\|storeCommande\|processPayment" app/Http/Controllers/Client/ClientOrderController.php
grep -n "marquerPrete\|consulterCommandes" app/Http/Controllers/CuisinierController.php
grep -n "servir\|consulterCommandes" app/Http/Controllers/ServeurController.php
```

---

## Nettoyage et Reset

### Réinitialiser la Base de Données:

```bash
# ⚠️ DANGER: Ceci supprime TOUTES les données!

# Option 1: Truncate (garde la structure)
php artisan tinker
>>> Commande::truncate();
>>> Facture::truncate();
>>> LigneCommande::truncate();
>>> Client::truncate();

# Option 2: Migration rollback + migrate (recrée tout)
php artisan migrate:refresh

# Option 3: Seed avec données de test
php artisan db:seed
```

---

## 🎯 Checklists de Tests

### Test de Workflow:
- [ ] Commande créée avec statut `en_preparation`
- [ ] Cuisinier voit la commande
- [ ] Cuisinier peut la marquer `prête`
- [ ] Serveur voit la commande
- [ ] Serveur peut la marquer `servie`
- [ ] Client voit le bouton Payer
- [ ] Paiement crée la facture
- [ ] Facture s'affiche correctement
- [ ] PDF peut être téléchargé/imprimé

### Test de Sécurité:
- [ ] Client ne peut voir que ses propres commandes
- [ ] Client ne peut pas payer sans statut servie
- [ ] Facture protégée (403 si pas propriétaire)
- [ ] Validation des données (panier non vide, etc.)

### Test de Performance:
- [ ] Charger 100+ commandes sans lag
- [ ] Facture PDF charge < 2 secondes
- [ ] Images chargent correctement

---

## 📞 Support

Si un test échoue:
1. Vérifier les logs: `tail -f storage/logs/laravel.log`
2. Vérifier la base de données
3. Vérifier les routes: `php artisan route:list`
4. Redémarrer le serveur: `php artisan serve`
5. Vider le cache: `php artisan cache:clear`

Bonne chance! 🚀

