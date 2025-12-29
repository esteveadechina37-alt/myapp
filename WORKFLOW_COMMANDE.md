# 📋 Workflow Complet de Commande - Restaurant Trial+

## Vue d'ensemble du processus

Ce document détaille les 12 étapes du flux de commande dans le système de gestion du restaurant.

---

## ✅ ÉTAPES DU WORKFLOW

### **ÉTAPE 1: Client consulte le menu via QR Code**
```
Route: GET /menu/qr-code
Contrôleur: MenuController@qrCodeMenu
```
- Client scanne le code QR avec son téléphone
- Affiche le menu interactif avec les 5 catégories
- Client visualise les 10 plats disponibles (5 africains + 5 européens)
- Images de haute qualité depuis Unsplash

---

### **ÉTAPE 2: Client passe commande**
```
Route: POST /commandes
Contrôleur: CommandeController@store
```
- Client sélectionne les plats et quantités
- Données envoyées au serveur
- Validation des plats et stocks

---

### **ÉTAPE 3: Type de commande ?**

#### **Option A: Sur place**
```php
type_commande = 'sur_place'
table_id = attribuée
```
- Client assigne une table disponible
- Table marquée comme occupée

#### **Option B: À emporter**
```php
type_commande = 'a_emporter'
heure_livraison_demandee = définie par client
```
- Client choisit l'heure de retrait
- Pas d'attribution de table

#### **Option C: Livraison**
```php
type_commande = 'livraison'
client_id = requis
```
- Vérifier la zone de livraison
- Gérer par le livreur

---

### **ÉTAPE 4: Enregistrer commande**
```php
// Modèle: Commande
Commande::create([
    'numero' => $numero_unique,          // COMM-001
    'client_id' => $client_id,           // Si applicable
    'table_id' => $table_id,             // Si sur place
    'utilisateur_id' => $serveur_id,     // Qui l'a enregistrée
    'type_commande' => $type,            // sur_place/a_emporter/livraison
    'montant_total_ht' => $montant_ht,
    'montant_tva' => $montant_tva,       // 20%
    'montant_total_ttc' => $montant_ttc,
    'statut' => 'en_attente',
    'heure_commande' => now(),
    'commentaires' => $notes
]);

// LignesCommandes avec tous les articles
foreach ($plats as $plat) {
    LigneCommande::create([
        'commande_id' => $commande_id,
        'plat_id' => $plat_id,
        'quantite' => $quantite,
        'prix_unitaire_ht' => $prix_ht,
        'prix_total_ht' => $quantite * $prix_ht,
        'commentaire' => $notes_plat
    ]);
}
```

---

### **ÉTAPE 5: Envoyer commande à la cuisine**
```php
// Méthode du modèle
$commande->envoyerCuisine();

// Changement d'état
statut: 'en_attente' → 'en_preparation'
heure_remise_cuisine: now()

// Notification
Cuisinier reçoit un avertissement
```

---

### **ÉTAPE 6: Préparation des plats**
```
Rôle: Cuisinier
Dashboard: /employes/cuisinier/dashboard
Vue: /employes/cuisinier/commandes
```
- Cuisinier voit les commandes en préparation
- Affiche les détails des plats
- Chronomètre de préparation
- Peut ajouter des notes

---

### **ÉTAPE 7: Commande prête?**

#### **Oui → Marquer comme PRÊTE**
```php
$commande->marquerPrete();

// État
statut: 'en_preparation' → 'prete'
heure_prete: now()

// Calcul temps préparation
$temps = $heure_prete - $heure_remise_cuisine
```

#### **Non → Continuer préparation**
- Mise à jour statut en temps réel
- Notification au serveur/client

---

### **ÉTAPE 8: Servir / Livrer commande**

#### **Sur place - SERVIR**
```php
Rôle: Serveur
$commande->servir();
statut: 'prete' → 'servie'

// Table reste marquée occupée jusqu'au paiement
```

#### **À emporter - RETRAIT**
```php
$commande->servir();
statut: 'prete' → 'servie'
```

#### **Livraison - LIVRER**
```php
Rôle: Livreur
$commande->livrer();
statut: 'prete' → 'livree'

// Enregistrement localisation
// Signature du client
```

---

### **ÉTAPE 9: Paiement**
```
Montant: montant_total_ttc
Moyen: espèces, carte, virement, etc.
```
- Cliente peut payer immédiatement ou à la fin
- Support multi-moyens de paiement
- Enregistrement sécurisé

---

### **ÉTAPE 10: Paiement validé?**

#### **Oui → GÉNÉRER FACTURE**
```php
$commande->enregistrerPaiement('carte');
// ou: 'especes', 'cheque', 'virement'

statut: 'servie' → 'payee'
est_payee: true
moyen_paiement: 'carte'

// Générer facture
$facture = $commande->genererFacture();

// Facture créée
numero_facture: 'FACT-COMM-001'
montant_ttc: 5200 CFA
date_facture: now()
statut_facture: 'emise'
```

#### **Non → Attendre validation**
- Client continue à consommer
- Paiement ultérieur possible

---

### **ÉTAPE 11: Mettre à jour le stock**
```php
// Pour chaque article de la commande
foreach ($commande->lignesCommandes as $ligne) {
    MouvementStock::create([
        'ingredient_id' => $ligne->plat_id,
        'type_mouvement' => 'sortie',
        'quantite' => $ligne->quantite,
        'motif' => 'Commande #COMM-001',
        'reference_commande' => $commande->id,
        'date_mouvement' => now()
    ]);
}

// Stock réduit automatiquement
// Alertes si stock faible
```

---

### **ÉTAPE 12: Archiver commande**
```php
$commande->archiver();

// Actions
1. Mouvements de stock finalisés
2. Facture archivée
3. Commande marquée comme complète
4. Logs d'audit créés
5. Commande soft-delete

statut_final: 'payee' → archivée
```

---

## 📊 Diagramme d'état de la commande

```
en_attente
    ↓
confirmee
    ↓
en_preparation (avec heure_remise_cuisine)
    ↓
prete (avec heure_prete)
    ↓
servie / livree
    ↓
payee (avec facture générée)
    ↓
archivee
```

---

## 🔄 États possibles et transitions

| État Actuel | Actions Possibles | État Suivant |
|---|---|---|
| **en_attente** | Confirmer | confirmee |
| **confirmee** | Envoyer cuisine | en_preparation |
| **en_preparation** | Marquer prête | prete |
| **prete** | Servir/Livrer | servie/livree |
| **servie** | Recevoir paiement | payee |
| **livree** | Recevoir paiement | payee |
| **payee** | Archiver | archivée |

---

## 🔐 Contrôles d'accès par rôle

| Rôle | Accès | Actions |
|---|---|---|
| **Serveur** | Commandes | Créer, consulter, envoyer cuisine, servir |
| **Cuisinier** | Préparation | Consulter, marquer prête, ajouter notes |
| **Livreur** | Livraisons | Marquer comme livrée, géolocaliser |
| **Gérant** | Toutes | Superviser, rapports, modifications |
| **Admin** | Toutes | Gestion complète, logs |

---

## 💾 Données persistantes

### Commande
- Montants (HT, TVA, TTC)
- Horaires (commande, cuisine, prête)
- Statut et historique
- Moyen de paiement

### LigneCommande
- Plat commandé
- Quantité et prix
- Commentaires spécifiques

### Facture
- Numéro unique
- Montants détaillés
- Date et statut
- Signature client (si applicable)

### MouvementStock
- Ingrédients utilisés
- Quantités sorties
- Traçabilité complète

---

## ✨ Optimisations et sécurité

- ✅ Transactions BD pour cohérence
- ✅ Validation des données à chaque étape
- ✅ Logs d'audit complets
- ✅ Notifications temps réel
- ✅ Timeouts et alertes
- ✅ Gestion des erreurs robuste
- ✅ Soft delete pour historique

---

## 📱 Intégration avec les interfaces

### Client (Menu QR)
- `/menu/qr-code` → Sélection plats
- Paiement intégré
- Suivi en temps réel

### Serveur
- `/employes/serveur/dashboard` → Commandes en cours
- `/employes/serveur/prendre-commande` → Nouvelle commande

### Cuisinier
- `/employes/cuisinier/dashboard` → Statistiques
- `/employes/cuisinier/commandes` → À préparer

### Livreur
- `/employes/livreur/dashboard` → Statistiques
- `/employes/livreur/livraisons` → À livrer

### Admin/Gérant
- `/admin/commandes` → Supervision complète
- `/admin/rapports` → Analyses

---

**Dernière mise à jour: 29/12/2025**
