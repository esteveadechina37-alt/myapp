# 🚀 Guide de Vérification & Test - Table Commandes

## ✅ Étapes de Vérification

### 1. Vérifier la Migration
```bash
php artisan migrate:status
```

**Attendu**: Status `[3] Ran` pour `2024_12_30_000007_recreate_commandes_table`

### 2. Vérifier la Structure
```bash
php check_table_structure.php
```

**Attendu**:
- ✓ 40 colonnes
- ✓ 12 statuts disponibles
- ✓ Tous les index créés

### 3. Tester le Workflow
```bash
php test_complete_workflow.php
```

**Attendu**:
```
✅ FLUX COMPLET RÉUSSI!
```

---

## 🧪 Test Manuel en Web

### Étape 1: Créer une Commande
1. Accéder à `http://localhost:8000/client/menu`
2. Ajouter des plats au panier
3. Aller à `/client/checkout`
4. Sélectionner le type de commande (ex: "À emporter")
5. Cliquer **"Confirmer la commande"**

### Étape 2: Vérifier le Résultat
**✅ Attendu**:
- [ ] Pas de redirection vers checkout
- [ ] Redirection vers la page de détail
- [ ] Message de succès affiché
- [ ] Statut: **"Confirmée"**
- [ ] Facture générée
- [ ] Numéro facture visible

**❌ Non Attendu**:
- [ ] Redirection vers checkout
- [ ] Erreur de base de données
- [ ] Statut vide ou incorrect

---

## 🔍 Vérifications Spécifiques

### Par Type de Commande

#### 1. Sur Place
```
✓ Table sélectionnée
✓ Adresse: VIDE
✓ Type: sur_place
✓ Frais livraison: 0
```

#### 2. À Emporter
```
✓ Table: vide
✓ Adresse: vide
✓ Type: a_emporter
✓ Frais livraison: 0
```

#### 3. Livraison
```
✓ Table: vide
✓ Adresse: remplie
✓ Téléphone: rempli
✓ Type: livraison
✓ Frais livraison: 5000 CFA
```

---

## 📊 Vérifications en Base de Données

### Compter les commandes
```sql
SELECT COUNT(*) as total, statut, type_commande 
FROM commandes 
GROUP BY statut, type_commande;
```

### Voir une commande
```sql
SELECT 
    id, numero, statut, type_commande,
    montant_total_ttc, est_payee, facture_generee,
    heure_confirmation, heure_remise_cuisine,
    moyen_paiement, numero_facture
FROM commandes 
WHERE id = 1;
```

### Voir les lignes
```sql
SELECT l.*, p.nom 
FROM lignes_commandes l
JOIN plats p ON l.plat_id = p.id
WHERE l.commande_id = 1;
```

---

## 🛠️ Troubleshooting

### ❌ Migration Non Exécutée
```bash
php artisan migrate --path=database/migrations/2024_12_30_000007_recreate_commandes_table.php
```

### ❌ Erreur Clés Étrangères
```bash
php artisan migrate:fresh --path=database/migrations
```

### ❌ Données Perdues
```sql
-- Voir les données soft-deletées
SELECT * FROM commandes WHERE deleted_at IS NOT NULL;

-- Restaurer
UNDELETE FROM commandes WHERE id = X;
```

### ❌ Cache à Nettoyer
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

---

## 📈 Checklist de Production

- [ ] Migration exécutée
- [ ] Table vérifié: 40 colonnes
- [ ] Model Commande: 15+ méthodes
- [ ] Controller mis à jour
- [ ] Test workflow: RÉUSSIES
- [ ] Aucun client ne voit d'erreur
- [ ] Factures générées automatiquement
- [ ] Paiement enregistré
- [ ] Logs clean (aucune erreur)
- [ ] Performance: OK

---

## 💾 Backup Avant Nouvelle Migration

Si vous devez revenir en arrière:

```bash
# Dump de la base
mysqldump -u root gestion_restaurant > backup_$(date +%Y%m%d_%H%M%S).sql

# Rollback migration
php artisan migrate:rollback --path=database/migrations/2024_12_30_000007_recreate_commandes_table.php

# Restore
mysql -u root gestion_restaurant < backup_XXXXXXXX_XXXXXX.sql
```

---

## 📝 Logs à Vérifier

```bash
# Voir les logs récents
tail -f storage/logs/laravel.log

# Filtrer les erreurs
grep -i error storage/logs/laravel.log

# Filtrer les commandes
grep "CHECKOUT" storage/logs/laravel.log
```

---

## ✨ Points Clés à Rappeler

1. **Statut Initial**: `confirmee` (pas `en_preparation`)
2. **Factures**: Générées automatiquement
3. **Workflow**: 12 statuts pour meilleur suivi
4. **Livraison**: Infos complètes (adresse, tel, nom)
5. **Paiement**: 6 moyens disponibles

---

## 📞 Support

Pour toute question ou problème:
1. Vérifier les logs: `storage/logs/laravel.log`
2. Vérifier la base: `check_table_structure.php`
3. Tester le workflow: `test_complete_workflow.php`
4. Consulter: `SYNTHESE_SOLUTION_COMMANDES.md`

---

**Dernière Mise à Jour**: 30 décembre 2024  
**Status**: ✅ PRÊT POUR PRODUCTION
