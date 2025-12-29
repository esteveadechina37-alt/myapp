# 📋 GUIDE D'IMPLÉMENTATION - Dashboard Client Amélioré

## ✅ Étapes Complétées

### 1. ✓ Vue Blade Mise à Jour
- **Fichier**: `resources/views/client/dashboard.blade.php`
- **Modifications**:
  - Ajout de 6 styles CSS pour timeline, badges, paiement
  - Section "Commandes En Cours" avec timeline visuelle
  - Support des 3 types de commandes
  - Formulaire de paiement intégré
  - JavaScript pour gestion du paiement

### 2. ✓ Contrôleur Amélioré
- **Fichier**: `app/Http/Controllers/ClientController.php`
- **Modifications**:
  - Méthode `dashboard()` avec récupération des `$activeCommands`
  - Méthode `processPayment()` corrigée et améliorée
  - Support de 4 méthodes de paiement
  - Création automatique de facture
  - Vérifications de sécurité

### 3. ✓ Routes Existantes
- `POST /client/payment/{commande}` - Déjà présente ✓
- `POST /client/mark-qr-scanned` - Déjà présente ✓

---

## 🔍 Vérifications à Effectuer

### 1. Base de Données - Structure Commandes
Vérifiez que la table `commandes` possède ces colonnes:

```sql
SHOW COLUMNS FROM commandes;
```

**Colonnes requises**:
- ✓ `id` - INT
- ✓ `numero` - VARCHAR (optionnel)
- ✓ `client_id` - INT (clé étrangère)
- ✓ `table_id` - INT (nullable, pour sur_place)
- ✓ `type_commande` - ENUM('sur_place', 'a_emporter', 'livraison')
- ✓ `statut` - VARCHAR ou ENUM
- ✓ `montant_total_ht` - DECIMAL
- ✓ `montant_tva` - DECIMAL
- ✓ `montant_total_ttc` - DECIMAL
- ✓ `est_payee` - BOOLEAN (default: false)
- ✓ `moyen_paiement` - VARCHAR (nullable)
- ✓ `heure_remise_cuisine` - DATETIME (nullable)
- ✓ `heure_prete` - DATETIME (nullable)
- ✓ `heure_livraison_demandee` - DATETIME (nullable)

### 2. Base de Données - Statuts Valides

Assurez-vous que le champ `statut` supporte au minimum:
```
- enregistree
- en_preparation
- prete
- prete_a_emporter
- prete_a_livrer
- en_livraison
- servie
- livree
- payee
```

### 3. Relation Client-Commande
Vérifiez dans le modèle `Commande`:

```php
public function client()
{
    return $this->belongsTo(Client::class);
}
```

### 4. Table Factures
Vérifiez la structure:
- `id`
- `commande_id` (FK vers commandes)
- `montant_ht`
- `montant_tva`
- `montant_ttc`
- `est_payee`
- `date_paiement`

---

## 🚀 Comment Tester

### Test 1: Affichage du Dashboard
1. Connectez-vous en tant que client
2. Allez à `/client/dashboard`
3. Vérifiez que la page charge sans erreurs

### Test 2: Commandes En Cours
1. Assurez-vous qu'il y a des commandes dans le statut `en_preparation`
2. Le dashboard devrait afficher la section "Commandes En Cours"
3. Vérifiez la timeline affichée

### Test 3: Paiement
1. Créez une commande dans statut `prete` ou `prete_a_emporter`
2. Allez au dashboard
3. Cliquez sur une méthode de paiement
4. Vérifiez que le bouton "Payer Maintenant" devient actif
5. Cliquez pour payer
6. Vérifiez que le statut passe à `payee`

### Test 4: Types de Commandes
1. Scanner un QR code
2. Testez les 3 types:
   - Sur place
   - À emporter
   - Livraison
3. Vérifiez que la timeline correspond au type

---

## 🔧 Configuration Required

### 1. Middleware `verify-qr`
Assurez-vous que ce middleware existe et fonctionne:
```php
Route::middleware(['verify-qr'])->group(function () {
    Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
    Route::get('/menu/plat/{id}', [MenuController::class, 'showPlat'])->name('menu.plat');
});
```

### 2. HTML5Qrcode Library
Vérifiez que la bibliothèque est incluse dans le layout:
```html
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.4/html5-qrcode.min.js"></script>
```

### 3. Bootstrap 5
Le dashboard utilise Bootstrap 5 pour les modales et styles.

---

## 📊 Points de Suivi Important

### État de chaque commande:
```
Sur Place:
enregistree → en_preparation → prete → servie → payee

À Emporter:
enregistree → en_preparation → prete_a_emporter → livree → payee

Livraison:
enregistree → en_preparation → prete_a_livrer → en_livraison → livree → payee
```

---

## 🎯 Problèmes Courants et Solutions

### Problème 1: "activeCommands" undefined in view
**Solution**: Assurez-vous que le contrôleur passe la variable:
```php
return view('client.dashboard', [
    'activeCommands' => $activeCommands,  // Cette ligne est obligatoire
    'recentCommands' => $recentCommands,
    'invoices' => $invoices,
]);
```

### Problème 2: Erreur 403 lors du paiement
**Solution**: Vérifiez que la commande appartient à l'utilisateur:
```php
if ($commande->client_id !== auth()->id()) {
    // Erreur - vérifier le champ utilisé
}
```

### Problème 3: Facture non créée après paiement
**Solution**: Assurez-vous que le modèle Facture a les bonnes colonnes:
```php
Facture::create([
    'commande_id' => $commande->id,
    'montant_ht' => $commande->montant_total_ht,
    'montant_tva' => $commande->montant_tva,
    'montant_ttc' => $commande->montant_total_ttc,
    'est_payee' => true,
    'date_paiement' => now()
]);
```

---

## 📱 Test sur Mobile

1. Accédez au dashboard sur un navigateur mobile
2. Testez le scanner QR (camera)
3. Vérifiez que la timeline s'affiche correctement
4. Testez le paiement sur écran mobile

---

## 🔐 Vérifications de Sécurité

- ✓ CSRF Token présent dans les formulaires
- ✓ Authentification vérifiée (`auth()->id()`)
- ✓ Autorisation vérifiée (`client_id` == `auth()->id()`)
- ✓ Validation des données de paiement
- ✓ Protection contre les paiements multiples

---

## 📞 Dépannage

### Logs à consulter:
```bash
# Vérifier les erreurs Laravel
tail -f storage/logs/laravel.log

# Vérifier les erreurs serveur
tail -f logs/php-errors.log
```

### Routes à tester:
```bash
# Lister toutes les routes client
php artisan route:list | grep client

# Vérifier la route de paiement
php artisan route:list | grep payment
```

---

## ✨ Fonctionnalités Futures (Phase 2)

- [ ] Notifications en temps réel (WebSocket)
- [ ] Estimation de temps de préparation
- [ ] Tracking GPS pour livraisons
- [ ] Avis et évaluations post-commande
- [ ] Historique détaillé des articles
- [ ] Récapitulatif de facturation

---

## 📝 Résumé

Le dashboard client a été entièrement refondu pour supporter:
1. **3 types de commandes** (sur place, à emporter, livraison)
2. **Timeline visuelle** du workflow complet
3. **Paiement intégré** directement dans le dashboard
4. **Gestion automatique** des factures
5. **Suivi en temps réel** de la préparation

Tous les changements sont **rétro-compatibles** et ne cassent pas le système existant.

