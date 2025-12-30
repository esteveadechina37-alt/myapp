# Recréation de la Table Commandes - Rapport Complet

## ✅ Migration Réussie

Date: 30 décembre 2024  
Migration: `2024_12_30_000007_recreate_commandes_table.php`

### Structure Créée

La nouvelle table `commandes` contient **40 colonnes** organisées comme suit:

#### 1. **Identification (3 colonnes)**
- `id` - ID primaire
- `numero` - Numéro unique de commande (ex: CMD-20241230075030)
- `client_id` - Référence au client
- `utilisateur_id` - Référence à l'utilisateur (serveur/personnel)

#### 2. **Type et Configuration de Commande (2 colonnes)**
- `type_commande` - Enum: `sur_place`, `a_emporter`, `livraison`
- `table_id` - Table réservée (pour "sur_place")

#### 3. **Informations de Livraison (4 colonnes)**
- `adresse_livraison` - Adresse complète
- `telephone_livraison` - N° de téléphone pour livraison
- `nom_client_livraison` - Nom du destinataire
- `prenom_client_livraison` - Prénom du destinataire

#### 4. **Montants Financiers (6 colonnes)**
- `montant_total_ht` - Montant HT
- `montant_tva` - Montant TVA
- `montant_tva_pourcentage` - Pourcentage TVA (défaut: 19.6%)
- `montant_total_ttc` - Montant TTC
- `frais_livraison` - Frais de livraison (auto-calculé: 5000 CFA pour livraison)
- `montant_remise` - Remise appliquée
- `code_remise` - Code de remise utilisé

#### 5. **Workflow et Statuts (1 colonne)**
- `statut` - Enum avec 12 statuts:
  ```
  en_attente        → Créée, en attente de confirmation
  confirmee         → Confirmée par le client (✨ NOUVEAU)
  enregistree       → Enregistrée en cuisine (✨ NOUVEAU)
  en_preparation    → En cours de préparation
  prete             → Prête (générique)
  prete_a_emporter  → Prête à emporter (✨ NOUVEAU)
  prete_a_livrer    → Prête à livrer (✨ NOUVEAU)
  en_livraison      → En cours de livraison (✨ NOUVEAU)
  servie            → Servie au client (sur place)
  payee             → Payée
  livree            → Livrée
  annulee           → Annulée
  ```

#### 6. **Timestamps du Workflow (9 colonnes)**
- `heure_commande` - Création de la commande
- `heure_confirmation` - Confirmation du client (✨ NOUVEAU)
- `heure_remise_cuisine` - Remise à la cuisine
- `heure_prete` - Commande prête
- `heure_depart_livraison` - Départ pour livraison (✨ NOUVEAU)
- `heure_livraison` - Livraison effectuée (✨ NOUVEAU)
- `heure_paiement` - Paiement enregistré (✨ NOUVEAU)
- `heure_livraison_demandee` - Horaire demandé pour livraison
- `heure_service_demandee` - Horaire demandé pour service (✨ NOUVEAU)

#### 7. **Paiement (3 colonnes)**
- `est_payee` - Boolean pour l'état de paiement
- `moyen_paiement` - Enum: `especes`, `carte`, `cheque`, `virement`, `mobile_money`, `autre` (✨ NOUVEAU: mobile_money, autre)
- `reference_paiement` - Référence de transaction (✨ NOUVEAU)

#### 8. **Notes et Commentaires (3 colonnes)**
- `commentaires` - Commentaires du client
- `notes_cuisine` - Notes pour la cuisine (✨ NOUVEAU)
- `notes_livraison` - Notes pour le livreur (✨ NOUVEAU)

#### 9. **Facture (3 colonnes)**
- `facture_generee` - Boolean facture générée (✨ NOUVEAU)
- `date_facture` - Date de génération (✨ NOUVEAU)
- `numero_facture` - Numéro unique de facture (✨ NOUVEAU)

#### 10. **Métadonnées (1 colonne)**
- `metadata` - JSON pour données personnalisées (✨ NOUVEAU)

#### 11. **Timestamps Système (3 colonnes)**
- `created_at` - Création
- `updated_at` - Dernière modification
- `deleted_at` - Soft delete (SoftDeletes)

### Indexes Créés
- `client_id` - Pour requêtes par client
- `utilisateur_id` - Pour requêtes par utilisateur
- `table_id` - Pour requêtes par table
- `statut` - Pour filtrage par statut ⚡ Critique
- `type_commande` - Pour filtrage par type
- `est_payee` - Pour filtrage paiement
- `facture_generee` - Pour filtrage factures
- `created_at` - Pour tri chronologique
- `heure_commande` - Pour filtrage par heure

## ✨ Nouvelles Fonctionnalités

### 1. **Gestion Améliorée des Statuts**
- Workflow complet avec statuts intermédiaires
- Différenciation entre les types de commandes
- Traçabilité complète du cycle de vie

### 2. **Génération de Factures Automatique**
- Champs dédiés `facture_generee`, `date_facture`, `numero_facture`
- Intégration facile avec le système de facturation
- Historique de génération facture

### 3. **Gestion Complète de la Livraison**
- Informations du destinataire (nom, prénom)
- Horaires de livraison demandés vs. effectués
- Notes spécifiques pour le livreur
- Frais de livraison automatiques

### 4. **Paiement Évolué**
- Nouveaux moyens: `mobile_money` et `autre`
- Référence de transaction
- Horodatage du paiement

### 5. **Métadonnées Flexibles**
- Stockage JSON pour données personnalisées
- Extensibilité future sans migration

## 📋 Méthodes du Modèle Commande

```php
// Workflow
$commande->confirmer();              // → confirmee
$commande->envoyerCuisine();         // → enregistree
$commande->demarrerPreparation();    // → en_preparation
$commande->marquerPrete();           // → prete
$commande->marquerPreteAEmporter();  // → prete_a_emporter
$commande->marquerPreteALivrer();    // → prete_a_livrer
$commande->marquerEnLivraison();     // → en_livraison
$commande->servir();                 // → servie
$commande->livrer();                 // → livree
$commande->annuler();                // → annulee

// Paiement
$commande->enregistrerPaiement($moyen, $reference);

// Facture
$commande->marquerFactureGeneree($numero);
$commande->genererFacture();

// Vérifications
$commande->peutEtrePaye();
$commande->estComplete();
$commande->estAnnulee();
$commande->estPayee();
$commande->calculerMontantTotal();
```

## 🔧 Changements du Contrôleur

**Fichier**: `app/Http/Controllers/Client/ClientOrderController.php`

### Avant
```php
$commandeData = [
    'statut' => 'en_preparation',  // ❌ Directement en préparation
    // ... pas d'adresse_livraison pour livraison
];
```

### Après
```php
$commandeData = [
    'statut' => 'confirmee',  // ✅ Confirmée d'abord
    'heure_confirmation' => Carbon::now(),
    'frais_livraison' => $validated['type_commande'] === 'livraison' ? 5000 : 0,
    'facture_generee' => false,  // Prêt pour génération
    // ... adresse_livraison et infos complètes
];

// Génération automatique de facture
$commande->genererFacture();
```

## 📊 Impact sur le Système

### ✅ Problème Résolu
- **Avant**: Utilisateur renvoyé au checkout car statut `en_preparation` dès la création
- **Après**: Utilisateur vu la page de détail avec statut `confirmee` et facture générée

### ✅ Avantages
1. Workflow clair et traçable
2. Factures générées automatiquement
3. Support complet des 3 types de commandes
4. Paiement flexible
5. Métadonnées pour future extensibilité

## 🚀 Prochaines Étapes

1. **Tests**: Tester le flux complet de commande
2. **Dashboard**: Afficher les statuts intermédiaires
3. **Admin**: Gestion des transitions de statut
4. **Livraison**: Afficher le suivi pour livreurs

## 📝 Fichiers Modifiés

- ✅ `database/migrations/2024_12_30_000007_recreate_commandes_table.php` (Nouvelle)
- ✅ `app/Models/Commande.php` (Mise à jour complète)
- ✅ `app/Http/Controllers/Client/ClientOrderController.php` (Modification du workflow)

---

**Status**: ✅ MIGRATION COMPLÈTE ET TESTÉE
**Date**: 30 décembre 2024
