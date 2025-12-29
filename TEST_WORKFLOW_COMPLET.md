# 🚀 INSTRUCTIONS TEST - Workflow Complet Commande to PDF

## Prérequis
- Laravel application en cours d'exécution (`php artisan serve`)
- 3 navigateurs/onglets (ou 3 utilisateurs): CLIENT, SERVEUR, CUISINIER

## 🎬 Scénario de Test Complet

### ÉTAPE 1: CLIENT - Créer une Commande
**Navigateur 1: CLIENT**

1. Allez à `http://localhost:8000/client/menu`
2. Connectez-vous si demandé
3. Ajoutez 2-3 plats au panier (cliquez sur les plats)
4. Vérifiez le nombre d'articles dans le panier
5. Cliquez "Voir le panier" ou le lien du panier
6. Allez à `http://localhost:8000/client/checkout`
7. **Sélectionnez le type de commande**: "Sur place" 🪑
8. **Sélectionnez une table**: Choisissez une table (ex: Table 1)
9. Ajoutez un commentaire optionnel (ex: "Sans oignon")
10. **Cliquez "Confirmer la commande"**

### Résultat Attendu:
- ✅ Redirection vers `/client/order/{commande_id}`
- ✅ Message de succès: "Commande créée! Numéro: CMD-..."
- ✅ Statut de la commande: **`en_preparation`**
- ✅ La table est marquée comme occupée
- ✅ Le panier est vidé

### Récupérez l'ID de Commande:
Note l'ID ou le numéro de la commande pour les étapes suivantes.

---

### ÉTAPE 2: CUISINIER - Voir et Préparer la Commande
**Navigateur 2: CUISINIER**

1. Allez à `http://localhost:8000/cuisinier/dashboard`
2. Connectez-vous avec un compte CUISINIER
3. Cliquez sur "Commandes" dans le menu latéral
4. Ou allez directement à `http://localhost:8000/cuisinier/commandes`

### Résultat Attendu:
- ✅ La commande du CLIENT apparaît dans la liste
- ✅ Elle a le statut `en_preparation`
- ✅ On voit les plats à préparer (quantités)

### Marquent la Commande comme Prête:
5. Cliquez le bouton vert **"Prête"** pour la commande
6. Confirmez l'action

### Résultat Attendu:
- ✅ Message de succès
- ✅ La commande disparaît de la liste (car statut change à `prete`)

---

### ÉTAPE 3: SERVEUR - Servir la Commande
**Navigateur 3: SERVEUR**

1. Allez à `http://localhost:8000/serveur/dashboard`
2. Connectez-vous avec un compte SERVEUR
3. Cliquez sur "Commandes" dans le menu latéral
4. Ou allez directement à `http://localhost:8000/serveur/commandes`

### Résultat Attendu:
- ✅ La commande du CLIENT apparaît dans la liste
- ✅ Elle a le statut `prete`
- ✅ On voit les infos du client et les détails

### Marquer comme Servie:
5. Cliquez le bouton vert **"✓"** pour la commande (si visible)
6. Confirmez l'action

### Résultat Attendu:
- ✅ Message de succès
- ✅ La commande est maintenant `servie`

---

### ÉTAPE 4: CLIENT - Payer la Commande
**Navigateur 1: CLIENT**

Vous êtes toujours sur la page `/client/order/{commande_id}`

1. **Attendez que le statut se mette à jour** (rechargez si nécessaire avec F5)
2. Vérifiez que le statut est maintenant `servie` ✓
3. Cherchez le bouton **"💳 Payer maintenant"** (doit être visible)

### Résultat Attendu:
- ✅ Le bouton "Payer maintenant" est visible
- ✅ Il est actif si le statut est l'un de: `prete`, `prete_a_emporter`, `prete_a_livrer`, `servie`

### Effectuer le Paiement:
4. Cliquez **"Payer maintenant"**
5. Un modal s'affiche avec les options de paiement:
   - 💳 Carte bancaire
   - 💵 Espèces
   - 📱 Paiement mobile
   - 📄 Chèque
6. Choisissez une option (ex: Carte bancaire)

### Résultat Attendu:
- ✅ Message: "Paiement effectué!"
- ✅ La page se recharge
- ✅ La commande est maintenant `est_payee = true`
- ✅ Le nouveau bouton apparaît: **"📄 Télécharger la Facture"**

---

### ÉTAPE 5: CLIENT - Télécharger la Facture PDF
**Navigateur 1: CLIENT**

Vous êtes toujours sur `/client/order/{commande_id}`

1. Cherchez le bouton **"📄 Télécharger la Facture"**
2. **Cliquez sur le bouton**

### Résultat Attendu:
- ✅ Nouvelle fenêtre/onglet s'ouvre
- ✅ Affiche la facture formatée avec:
  - Numéro facture
  - Infos client
  - Détails commande
  - Table d'articles (nom, quantité, prix)
  - Sous-total HT
  - Montant TVA (19.6%)
  - **Total TTC en rouge**
  - Infos paiement (méthode, date)
  - Notes/commentaires
- ✅ Bouton **"🖨️ Imprimer / Télécharger en PDF"** visible en haut

### Télécharger le PDF:
3. Cliquez **"Imprimer / Télécharger en PDF"**
4. Une dialog d'impression s'ouvre
5. Sélectionnez **"Enregistrer en PDF"** ou une imprimante
6. Cliquez "Enregistrer" ou "Imprimer"

### Résultat Attendu:
- ✅ Le PDF est téléchargé ou imprimé
- ✅ Vous pouvez l'ouvrir dans votre lecteur PDF

---

### OPTION: Voir Toutes les Factures
**Navigateur 1: CLIENT**

1. Allez à `http://localhost:8000/client/invoices`
2. Vous devez voir la facture créée dans la liste
3. Elle affiche:
   - Numéro facture
   - Date
   - Statut: "Payée" ✓
   - Montant
4. Cliquez **"Télécharger"** pour ouvrir la facture

### Résultat Attendu:
- ✅ La facture s'ouvre dans une nouvelle fenêtre
- ✅ Vous pouvez imprimer/télécharger en PDF

---

## 📊 Résumé du Flux Complètement Testé

```
✅ CLIENT crée commande → Statut: en_preparation
   ↓
✅ CUISINIER la voit → La marque prête → Statut: prete
   ↓
✅ SERVEUR la voit → La marque servie → Statut: servie
   ↓
✅ CLIENT paie → Facture créée → est_payee: true
   ↓
✅ CLIENT télécharge PDF → Facture formatée → Imprimer/Télécharger
```

---

## 🔧 Dépannage

### Le bouton "Payer maintenant" n'apparaît pas
**Vérifier**:
- Le statut de la commande est-il l'un de: `prete`, `servie`, etc. ?
- La commande n'est-elle pas déjà payée ?
- Actualisez la page (F5)

### La facture ne s'affiche pas
**Vérifier**:
- La commande est-elle payée ? (`est_payee = true`)
- Regardez les logs: `storage/logs/laravel.log`
- Vérifiez que la relation `$commande->facture()` existe

### Les plats ne s'affichent pas dans la facture
**Vérifier**:
- La commande a-t-elle des `lignesCommandes` ?
- Chaque ligne a-t-elle un `plat` associé ?
- Consultez la base de données

### Le PDF ne télécharge pas
**Vérifier**:
- Utilisez plutôt l'impression du navigateur (Ctrl+P)
- Sélectionnez "Enregistrer en PDF"
- La vue HTML s'affiche-t-elle correctement ?

---

## 📱 Commandes Utiles (Terminal)

```bash
# Voir les routes client/cuisinier/serveur
php artisan route:list | grep -E "(client|cuisinier|serveur)"

# Voir les factures créées
php artisan tinker
>>> Facture::all();

# Voir les commandes
php artisan tinker
>>> Commande::with('client', 'facture', 'lignesCommandes.plat')->get();

# Voir les statuts des commandes
php artisan tinker
>>> Commande::pluck('numero', 'statut');
```

---

## ✨ Points Clés à Vérifier

1. **Statut de la commande** passe par les bons états
2. **Chaque rôle voit ses commandes** (cuisinier: en_preparation, serveur: tous)
3. **Paiement crée une facture**
4. **Facture affiche correctement les données**
5. **PDF s'ouvre et s'imprime**

---

## 🎉 Succès!

Si tout fonctionne comme décrit, le workflow complet est **100% opérationnel** ! 

Bravo! Le système de commande restaurant est maintenant complet.

