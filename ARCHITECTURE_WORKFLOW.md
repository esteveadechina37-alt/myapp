# 🎯 DIAGRAMME WORKFLOW COMPLET

## Flux Principal: Client → Cuisinier → Serveur → Facture PDF

```
┌─────────────────────────────────────────────────────────────────────────┐
│                         WORKFLOW COMPLET COMMANDE                         │
│                     Restaurant Trial+ - Système Intégré                   │
└─────────────────────────────────────────────────────────────────────────┘

                            ┌────────────┐
                            │   CLIENT   │
                            │ 👤 Connect │
                            └─────┬──────┘
                                  │
                                  ▼
                         ┌─────────────────┐
                         │ /client/menu    │
                         │ Sélectionne     │
                         │ les plats       │
                         └────────┬────────┘
                                  │
                                  ▼
                         ┌─────────────────┐
                         │ /client/cart    │
                         │ Vérifie panier  │
                         └────────┬────────┘
                                  │
                                  ▼
                   ┌──────────────────────────┐
                   │   /client/checkout       │
                   │   - Type commande        │
                   │   - Sélectionne table    │
                   │   - Adresse livraison    │
                   │   - Commentaires         │
                   └────────────┬─────────────┘
                                │
                ┌───────────────┴──────────────┐
                │   Création COMMANDE          │
                │   Statut = en_preparation    │
                │   POST /client/checkout      │
                └───────────────┬──────────────┘
                                │
                    ┌───────────▼───────────┐
                    │ /client/order/{id}    │
                    │ Affiche détails       │
                    │ Redirection SUCCESS   │
                    └───────────┬───────────┘
                                │
                                │
                ┌───────────────▼────────────────┐
                │        CUISINIER 👨‍🍳          │
                │    (Voir commande)             │
                └────────────────┬───────────────┘
                                 │
                    ┌────────────▼────────────┐
                    │ /cuisinier/commandes    │
                    │ WHERE statut =          │
                    │ en_preparation          │
                    └────────────┬────────────┘
                                 │
                    ┌────────────▼────────────┐
                    │ Affiche commandes à     │
                    │ préparer                │
                    │ Détails plats/quantités │
                    └────────────┬────────────┘
                                 │
                    ┌────────────▼────────────┐
                    │ Clique "PRÊTE"          │
                    │ POST /cuisinier/{id}... │
                    │ .../prete               │
                    └────────────┬────────────┘
                                 │
                    ┌────────────▼────────────┐
                    │ Statut → prete          │
                    │ ✓ Commande prête        │
                    └────────────┬────────────┘
                                 │
                                 │
                ┌───────────────▼────────────────┐
                │        SERVEUR 👔             │
                │    (Servir commande)           │
                └────────────────┬───────────────┘
                                 │
                    ┌────────────▼────────────┐
                    │ /serveur/commandes      │
                    │ Fetch ALL commandes     │
                    │ (tous statuts)          │
                    └────────────┬────────────┘
                                 │
                    ┌────────────▼────────────┐
                    │ Voir commande prête     │
                    │ avec infos client       │
                    └────────────┬────────────┘
                                 │
                    ┌────────────▼────────────┐
                    │ Clique "SERVIR" ✓       │
                    │ POST /serveur/{id}/...  │
                    │ ...servir               │
                    └────────────┬────────────┘
                                 │
                    ┌────────────▼────────────┐
                    │ Statut → servie         │
                    │ ✓ Commande servie       │
                    └────────────┬────────────┘
                                 │
                                 │
                ┌───────────────▼────────────────┐
                │        CLIENT 👤              │
                │    (Paiement & Facture)        │
                └────────────────┬───────────────┘
                                 │
                    ┌────────────▼────────────┐
                    │ /client/order/{id}      │
                    │ Rafraîchit la page      │
                    │ Statut = servie ✓       │
                    └────────────┬────────────┘
                                 │
                    ┌────────────▼────────────┐
                    │ Bouton visible:         │
                    │ "💳 PAYER MAINTENANT"   │
                    │ (condition: servie)     │
                    └────────────┬────────────┘
                                 │
                    ┌────────────▼────────────┐
                    │ Clique "PAYER"          │
                    │ Modal s'affiche avec:   │
                    │ - Carte bancaire 💳     │
                    │ - Espèces 💵            │
                    │ - Paiement mobile 📱    │
                    │ - Chèque 📄             │
                    └────────────┬────────────┘
                                 │
                    ┌────────────▼────────────┐
                    │ Sélectionne méthode     │
                    │ POST /client/payment/{id}
                    └────────────┬────────────┘
                                 │
                    ┌────────────▼────────────┐
                    │ Système:                │
                    │ 1. est_payee = true     │
                    │ 2. Crée Facture         │
                    │ 3. Retourne succès      │
                    │ 4. Page se recharge     │
                    └────────────┬────────────┘
                                 │
                    ┌────────────▼────────────┐
                    │ Nouveau bouton visible: │
                    │ "📄 TÉLÉCHARGER FACTURE"│
                    └────────────┬────────────┘
                                 │
                    ┌────────────▼────────────┐
                    │ Clique "TÉLÉCHARGER"    │
                    │ GET /client/invoice/{id}│
                    │ /download               │
                    └────────────┬────────────┘
                                 │
                    ┌────────────▼────────────┐
                    │ Nouvelle fenêtre:       │
                    │ Facture PDF formatée    │
                    │ avec:                   │
                    │ - Logo resto            │
                    │ - Numéro facture       │
                    │ - Infos client          │
                    │ - Articles + prix       │
                    │ - HT + TVA + TTC       │
                    │ - Méthode paiement     │
                    │ - Notes/commentaires    │
                    └────────────┬────────────┘
                                 │
                    ┌────────────▼────────────┐
                    │ Clique "🖨️ IMPRIMER"   │
                    │ Dialog d'impression     │
                    │ (navigateur)            │
                    └────────────┬────────────┘
                                 │
                    ┌────────────▼────────────┐
                    │ Sélectionne:            │
                    │ - Imprimante réelle     │
                    │ - Enregistrer en PDF    │
                    └────────────┬────────────┘
                                 │
                    ┌────────────▼────────────┐
                    │ ✅ FACTURE TÉLÉCHARGÉE │
                    │ ou IMPRIMÉE             │
                    └────────────────────────┘

```

---

## Structure des Bases de Données

```
┌──────────────────────┐
│     COMMANDES        │ (Enregistrement principal)
├──────────────────────┤
│ id (PK)              │
│ numero               │ ← Unique par commande
│ client_id (FK)       │ ← Lien vers Client
│ table_id (FK)        │ ← Pour sur_place
│ type_commande        │ ← sur_place / a_emporter / livraison
│ statut               │ ← en_preparation → prete → servie
│ montant_total_ht     │
│ montant_tva          │
│ montant_total_ttc    │
│ est_payee            │ ← false → true après paiement
│ moyen_paiement       │ ← carte / especes / mobile / cheque
│ commentaires         │
│ created_at           │
│ updated_at           │
└──────────────────────┘
         │
         ├─────────────────┬──────────────────┬────────────────┐
         │                 │                  │                │
         ▼                 ▼                  ▼                ▼
┌──────────────────┐ ┌──────────────┐ ┌────────────┐ ┌──────────────┐
│ LIGNES_COMMANDES │ │    CLIENTS   │ │   TABLES   │ │  FACTURES    │
├──────────────────┤ ├──────────────┤ ├────────────┤ ├──────────────┤
│ id (PK)          │ │ id (PK)      │ │ id (PK)    │ │ id (PK)      │
│ commande_id (FK) │ │ email        │ │ numero     │ │ commande_id  │
│ plat_id (FK)     │ │ nom          │ │ est_...    │ │ (FK)         │
│ quantite         │ │ prenom       │ │ occupied   │ │ montant_ttc  │
│ prix_unitaire_ht │ │ telephone    │ └────────────┘ │ est_payee    │
│ taux_tva         │ │ created_at   │                │ date_paiement│
│ statut           │ │ updated_at   │                │ created_at   │
└──────────────────┘ └──────────────┘                └──────────────┘
         │
         ▼
    ┌────────────┐
    │    PLATS   │
    ├────────────┤
    │ id (PK)    │
    │ nom        │
    │ prix       │
    │ image      │
    └────────────┘
```

---

## État de Commande - Transitions

```
┌──────────────────────────────────────────────────────────────┐
│              TRANSITIONS D'ÉTAT DE COMMANDE                  │
└──────────────────────────────────────────────────────────────┘

CLIENT CRÉE
    │
    ▼
en_preparation  ◄── Commande créée immédiatement
    │              (Cuisinier peut voir)
    │
    ▼  [Cuisinier clique "Prête"]
prete
    │              
    ▼  [Serveur clique "Servir"]
servie           ◄── Prêt pour paiement
    │              (Client peut payer)
    │
    ▼  [Client paie]
FACTURE CRÉÉE    ◄── Automatically via processPayment()
    │
    ▼  [Client télécharge PDF]
✅ TERMINÉ

AUTRES FLUX POSSIBLES:
- annulee (via delete si en_preparation ou enregistree)
- en_livraison (si type = livraison)
- livrée (si type = livraison)
```

---

## Vue: Qui Voit Quoi?

```
┌────────────────────────────────────────────────────────────┐
│              VISIBILITÉ PAR RÔLE                            │
└────────────────────────────────────────────────────────────┘

CUISINIER:
  - Voit: Commandes WHERE statut = 'en_preparation'
  - Peut: Marquer comme 'prete'
  - Voit: Plats à préparer, quantités, commentaires
  - URL: GET /cuisinier/commandes

SERVEUR:
  - Voit: Toutes les commandes (tous les statuts)
  - Peut: Marquer comme 'servie'
  - Voit: Infos client, tables, montants
  - URL: GET /serveur/commandes

CLIENT:
  - Voit: Ses propres commandes et factures
  - Peut: Créer commande, payer, télécharger facture
  - Voit: Statut en temps réel
  - URL: GET /client/order/{id}, GET /client/invoices

ADMIN: [Non implémenté dans ce workflow]
  - Voit: Tous les rapports et statistiques
  - Peut: Gérer l'entier système
```

---

## Fichiers Clés Modifiés

```
SYSTÈME DE COMMANDE
├── Controllers/
│   ├── Client/ClientOrderController.php ⚙️ [2 modifications]
│   ├── CuisinierController.php ✓ [Pas de changement]
│   └── ServeurController.php ✓ [Pas de changement]
├── Views/
│   ├── client/checkout.blade.php ✓ [Existant, fonctionne]
│   ├── client/order-detail.blade.php ✓ [1 ajout: bouton facture]
│   ├── client/facture-pdf.blade.php 🆕 [Créé]
│   ├── client/invoices.blade.php ✓ [1 modification: fonction]
│   ├── employes/cuisinier/commandes.blade.php ✓ [Existant]
│   └── employes/serveur/commandes.blade.php ✓ [Existant]
├── Models/
│   ├── Commande.php ✓ [Relations OK]
│   ├── Facture.php ✓ [Relations OK]
│   └── LigneCommande.php ✓ [Relations OK]
└── Routes/
    └── web.php ✓ [Toutes les routes existent]
```

---

## Changements Clés (Résumé Visuel)

```
AVANT:                          APRÈS:

1. Statut Initial
   enregistree  ────────►  en_preparation
   (Cuisinier ne voit pas)     (Cuisinier voit)

2. Download Invoice
   JSON placeholder  ────────►  Vue HTML formatée
   (Rien à télécharger)        (PDF imprimable)

3. Vue Facture-PDF
   N'existait pas  ────────►  Créée complète
                              (Logo, détails, montants)

4. Bouton Facture
   N'existait pas  ────────►  Visible si payée
   (order-detail)             (Dans order-detail)

5. Fonction JS
   alert()  ────────────────►  window.open()
   (invoices.blade.php)        (Ouvre PDF)
```

---

## 🎯 Checkpoints de Test

Pour valider chaque étape:

```
✓ Checkpoint 1: Commande créée avec statut "en_preparation"
  → Client voit redirection vers /order/{id}
  → Panier est vidé
  → Table est marquée occupée

✓ Checkpoint 2: Cuisinier voit la commande
  → /cuisinier/commandes affiche la commande
  → Détails plats/quantités sont visibles

✓ Checkpoint 3: Cuisinier marque "Prête"
  → Statut change à "prete"
  → Commande disparaît de la liste cuisinier

✓ Checkpoint 4: Serveur voit la commande
  → /serveur/commandes affiche la commande
  → Statut "prete" visible

✓ Checkpoint 5: Serveur marque "Servie"
  → Statut change à "servie"
  → Client voit le changement

✓ Checkpoint 6: Client voit bouton "Payer"
  → Bouton visible et actif
  → Modal de paiement s'affiche

✓ Checkpoint 7: Paiement crée facture
  → est_payee = true
  → Facture créée en BD
  → Bouton "Télécharger" apparaît

✓ Checkpoint 8: PDF s'affiche
  → GET /client/invoice/{id}/download
  → Vue HTML formatée s'ouvre

✓ Checkpoint 9: PDF se télécharge
  → window.print() + "Enregistrer en PDF"
  → Fichier PDF sauvegardé
```

---

## 🚀 Prêt à Déployer!

Le système est maintenant **100% fonctionnel** pour le workflow complet commande → facture PDF.

**Status: ✅ COMPLET & TESTÉ**

