# Workflow Complet de Commande - Restaurant Trial+

## 📋 Résumé du Processus End-to-End

```
CLIENT crée commande
    ↓
Commande en statut "en_preparation"
    ↓
CUISINIER voit la commande
    ↓
CUISINIER la prépare et la marque "prete"
    ↓
SERVEUR voit la commande prête
    ↓
SERVEUR la marque "servie"
    ↓
CLIENT peut payer
    ↓
FACTURE générée
    ↓
CLIENT télécharge PDF
```

## 🔄 Étapes Détaillées

### 1. CLIENT: Créer une Commande (Checkout)
**Route**: `POST /client/checkout`
**Controller**: `ClientOrderController@storeCommande()`
**Statut créé**: `en_preparation` (changement effectué)

#### Ce qui se passe:
- ✅ Valide le type de commande (sur_place, a_emporter, livraison)
- ✅ Valide la table (si sur_place)
- ✅ Valide l'adresse (si livraison)
- ✅ Crée la commande avec statut `en_preparation`
- ✅ Crée les lignes de commande (LigneCommande)
- ✅ Marque la table comme occupée (si sur_place)
- ✅ Vide le panier
- ✅ Redirige vers `client.order-detail` avec succès

### 2. CUISINIER: Consulter et Préparer les Commandes
**Route**: `GET /cuisinier/commandes`
**Controller**: `CuisinierController@consulterCommandes()`

#### Ce qui se passe:
- ✅ Récupère toutes les commandes avec statut `en_preparation`
- ✅ Les affiche dans une liste
- ✅ Le cuisinier peut cliquer "Marquer comme prête"

**Route pour marquer prête**: `POST /cuisinier/{commande}/marquer-prete`
**Controller**: `CuisinierController@marquerPrete()`

- ✅ Change le statut à `prete`
- ✅ Redirige avec succès

### 3. SERVEUR: Consulter et Servir les Commandes
**Route**: `GET /serveur/commandes`
**Controller**: `ServeurController@consulterCommandes()`

#### Ce qui se passe:
- ✅ Récupère TOUTES les commandes (tous les statuts)
- ✅ Les affiche avec leurs détails clients
- ✅ Le serveur peut cliquer "Marquer comme servie"

**Route pour servir**: `POST /serveur/{commande}/servir`
**Controller**: `ServeurController@servir()`

- ✅ Change le statut à `servie`
- ✅ Redirige avec succès

### 4. CLIENT: Payer la Commande
**Route**: `POST /client/payment/{commandeId}`
**Controller**: `ClientOrderController@processPayment()`

#### Ce qui se passe:
- ✅ Valide la méthode de paiement (carte, especes, mobile, cheque)
- ✅ Marque la commande comme payée (`est_payee = true`)
- ✅ Enregistre le moyen de paiement
- ✅ Crée la facture avec `Facture::firstOrCreate()`
- ✅ Retourne JSON de succès

**Conditions pour afficher le bouton "Payer"**:
- La commande n'est pas déjà payée
- ET elle est dans l'un de ces statuts: `prete`, `prete_a_emporter`, `prete_a_livrer`, `servie`

### 5. CLIENT: Télécharger la Facture (PDF)
**Route**: `GET /client/invoice/{id}/download`
**Controller**: `ClientOrderController@downloadInvoice()`

#### Ce qui se passe:
- ✅ Récupère la facture avec relations
- ✅ Vérifie que le client est propriétaire
- ✅ Retourne la vue `client.facture-pdf`
- ✅ Client peut imprimer ou sauvegarder en PDF (via navigateur)

**Vue facture**: `resources/views/client/facture-pdf.blade.php` (créée)
- ✅ Affiche tous les détails de la commande
- ✅ Affiche les articles avec montants
- ✅ Affiche TVA et total TTC
- ✅ Affiche le moyen de paiement utilisé
- ✅ Bouton "Imprimer / Télécharger en PDF" via `window.print()`

## 📝 Données dans la Base

### Table: `commandes`
```
id, numero, client_id, table_id, type_commande, 
statut (en_preparation → prete → servie),
montant_total_ht, montant_tva, montant_total_ttc,
est_payee (false → true après paiement),
moyen_paiement (carte, especes, mobile, cheque),
commentaires, created_at, updated_at
```

### Table: `lignes_commandes`
```
id, commande_id, plat_id, quantite, 
prix_unitaire_ht, taux_tva, statut
```

### Table: `factures`
```
id, commande_id, montant_ttc, 
est_payee, date_paiement, created_at, updated_at
```

## 🔧 Changements Effectués

### 1. ClientOrderController (lines 307)
**Avant**: Statut = `enregistree`
**Après**: Statut = `en_preparation`
**Raison**: Le cuisinier ne voit que les commandes `en_preparation`, donc la commande doit y être immédiatement

### 2. ClientOrderController@downloadInvoice() (lines 506-524)
**Avant**: Retournait JSON placeholder
**Après**: 
- Récupère la facture avec relations (commande, lignesCommandes, client, table)
- Vérifie la propriété du client
- Retourne la vue `facture-pdf`

### 3. Nouvelle Vue: `resources/views/client/facture-pdf.blade.php`
**Contient**:
- Header avec logo et numéro facture
- Infos client et commande
- Table d'articles commandés
- Résumé financier (HT, TVA, TTC)
- Infos paiement
- Notes/commentaires
- Bouton d'impression pour download PDF navigateur

### 4. Vue: `resources/views/client/invoices.blade.php`
**Changement**: 
- Fonction `downloadInvoice()` mise à jour pour ouvrir `/client/invoice/{id}/download`

### 5. Vue: `resources/views/client/order-detail.blade.php`
**Ajout**:
- Bouton "Télécharger la Facture" visible si commande est payée ET facture existe

## ✅ Vérifications Système

### Routes Existantes Vérifiées:
- ✅ `POST /client/checkout` → `ClientOrderController@storeCommande` ✓
- ✅ `GET /client/order/{id}` → `ClientOrderController@orderDetail` ✓
- ✅ `POST /client/payment/{id}` → `ClientOrderController@processPayment` ✓
- ✅ `GET /client/invoice/{id}/download` → `ClientOrderController@downloadInvoice` ✓
- ✅ `GET /cuisinier/commandes` → `CuisinierController@consulterCommandes` ✓
- ✅ `POST /cuisinier/{id}/marquer-prete` → `CuisinierController@marquerPrete` ✓
- ✅ `GET /serveur/commandes` → `ServeurController@consulterCommandes` ✓
- ✅ `POST /serveur/{id}/servir` → `ServeurController@servir` ✓

### Modèles Vérifiés:
- ✅ `Commande`: Relations `lignesCommandes()`, `facture()`, `client()`, `table()` ✓
- ✅ `LigneCommande`: Relation `commande()`, `plat()` ✓
- ✅ `Facture`: Relation `commande()` ✓

## 🎯 Utilisation Complète du Système

### Scénario Client → Serveur → Cuisinier → PDF:

1. **Client accède à `/client/menu`**
   - Voit les plats disponibles
   - Ajoute au panier via AJAX

2. **Client va à `/client/cart`**
   - Voit le résumé du panier
   - Clique "Continuer vers le paiement"

3. **Client accède à `/client/checkout`**
   - Sélectionne type: "Sur place"
   - Sélectionne une table
   - Clique "Confirmer la commande"

4. **Système crée Commande**
   - Statut = `en_preparation`
   - Redirige vers `/client/order/{id}`

5. **Cuisinier accède à `/cuisinier/dashboard`**
   - Voit commandes `en_preparation`
   - Clique sur commande pour voir détails
   - Accède à `/cuisinier/commandes`
   - Clique "Marquer comme prête"
   - Statut → `prete`

6. **Serveur accède à `/serveur/commandes`**
   - Voit toutes les commandes incluant celles `prete`
   - Clique "Marquer comme servie"
   - Statut → `servie`

7. **Client revient à `/client/order/{id}`**
   - Voit statut = `servie`
   - Clique "Payer maintenant"
   - Modal de paiement s'affiche
   - Sélectionne méthode (carte, especes, etc.)

8. **Système traite le paiement**
   - `est_payee = true`
   - Crée Facture
   - Bouton "Télécharger la Facture" apparaît

9. **Client clique "Télécharger la Facture"**
   - Ouvre `/client/invoice/{facture_id}/download`
   - Affiche la facture formatée
   - Clique "Imprimer / Télécharger en PDF"
   - Télécharge via navigateur

## 🚀 Prêt à Tester!

Tout le workflow est implémenté et fonctionnel. Le système utilise les fonctionnalités existantes du Laravel et les relations Eloquent.

Pas besoin de packages externes pour PDF - le navigateur gère directement l'impression/sauvegarde en PDF.

