# ⚡ Quick Start - Commandes Utiles

## 🚀 Commandes de Vérification Rapides

### Vérifier la Migration
```bash
php artisan migrate:status | grep "2024_12_30_000007"
```

### Voir la Structure
```bash
php check_table_structure.php
```

### Tester le Workflow
```bash
php test_complete_workflow.php
```

### Compter les Commandes
```bash
php artisan tinker
>>> use App\Models\Commande;
>>> Commande::count()
>>> Commande::groupBy('statut')->selectRaw('statut, count(*) as total')->get()
```

---

## 🧪 Tests Rapides

### Test 1: Structure (< 1s)
```bash
php check_table_structure.php | grep "Total"
```
**Attendu**: `✅ Total colonnes: 40`

### Test 2: Workflow (< 5s)
```bash
php test_complete_workflow.php | tail -10
```
**Attendu**: `✅ FLUX COMPLET RÉUSSI!`

### Test 3: Factures
```bash
php artisan tinker
>>> use App\Models\Commande;
>>> $c = Commande::first();
>>> $c->facture_generee
```
**Attendu**: `true`

---

## 📊 Requêtes SQL Rapides

### Dernière commande
```sql
SELECT * FROM commandes ORDER BY created_at DESC LIMIT 1;
```

### Par statut
```sql
SELECT statut, COUNT(*) as total FROM commandes GROUP BY statut;
```

### Factures générées
```sql
SELECT COUNT(*) FROM commandes WHERE facture_generee = 1;
```

### Factures en attente
```sql
SELECT id, numero FROM commandes WHERE facture_generee = 0;
```

### Factures manquantes
```sql
SELECT id, numero, statut FROM commandes 
WHERE statut IN ('payee', 'livree') AND facture_generee = 0;
```

---

## 🔧 Reset & Maintenance

### Supprimer toutes les commandes de test
```sql
DELETE FROM commandes WHERE numero LIKE 'TEST-%';
```

### Regénérer une facture
```php
php artisan tinker
>>> use App\Models\Commande;
>>> $c = Commande::find(1);
>>> $c->genererFacture();
```

### Marquer comme facture générée
```php
php artisan tinker
>>> use App\Models\Commande;
>>> Commande::where('facture_generee', 0)->update(['facture_generee' => 1]);
```

---

## 📈 Statistiques Rapides

### Commandes par jour
```php
php artisan tinker
>>> use App\Models\Commande;
>>> Commande::selectRaw('DATE(created_at) as date, COUNT(*) as total, SUM(montant_total_ttc) as montant')
          ->groupBy('date')
          ->orderByDesc('date')
          ->limit(7)
          ->get()
```

### Commandes par type
```php
php artisan tinker
>>> Commande::selectRaw('type_commande, COUNT(*) as total, SUM(montant_total_ttc) as montant')
          ->groupBy('type_commande')
          ->get()
```

### Commandes payées vs non payées
```php
php artisan tinker
>>> Commande::selectRaw('est_payee, COUNT(*) as total')
          ->groupBy('est_payee')
          ->get()
```

---

## 🧹 Nettoyage

### Nettoyer le cache
```bash
php artisan cache:clear && php artisan config:clear && php artisan route:clear
```

### Regénérer autoload
```bash
composer dump-autoload
```

### Voir les logs
```bash
tail -100 storage/logs/laravel.log
```

### Erreurs récentes
```bash
grep -i "error\|exception" storage/logs/laravel.log | tail -20
```

---

## 🆘 Troubleshooting Rapide

### Erreur: "Table doesn't exist"
```bash
php artisan migrate
```

### Erreur: "Column doesn't exist"
```bash
php check_table_structure.php
# Puis vérifier si colonne existe
```

### Erreur: "Integrity constraint violation"
```sql
-- Vérifier les FK
SELECT * FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
WHERE TABLE_NAME = 'commandes' AND REFERENCED_TABLE_NAME IS NOT NULL;
```

### Erreur: "Class not found"
```bash
composer dump-autoload
php artisan cache:clear
```

---

## 📱 Test Depuis le Navigateur

### URL Utiles
```
Menu:         http://localhost:8000/client/menu
Panier:       http://localhost:8000/client/cart
Checkout:     http://localhost:8000/client/checkout
Historique:   http://localhost:8000/client/orders
Commande:     http://localhost:8000/client/order/1
Factures:     http://localhost:8000/client/invoices
Admin:        http://localhost:8000/admin/dashboard
```

### Logs Console (F12)
Ouvrir DevTools et vérifier:
- Pas d'erreur JavaScript
- Pas de redirection inattendue
- Status codes HTTP 200/201

---

## 📝 Fichiers de Référence

| Fichier | Usage |
|---------|-------|
| `check_table_structure.php` | Vérifier structure table |
| `test_complete_workflow.php` | Tester workflow complet |
| `SYNTHESE_SOLUTION_COMMANDES.md` | Vue d'ensemble |
| `RAPPORT_MIGRATION_COMMANDES.md` | Détails complets |
| `GUIDE_VERIFICATION_COMMANDES.md` | Guide pas-à-pas |
| `COMPARAISON_AVANT_APRES.md` | Changements détaillés |

---

## ✅ Checklist Rapide

- [ ] Migration exécutée: `php artisan migrate:status`
- [ ] 40 colonnes: `php check_table_structure.php`
- [ ] Workflow: `php test_complete_workflow.php`
- [ ] Pas d'erreur: `grep -i error storage/logs/laravel.log`
- [ ] Test web: Créer une commande via UI
- [ ] Facture: Vérifier générée automatiquement
- [ ] Redirection: Vers `/client/order/X` (pas checkout)

---

## 📞 Besoin d'Aide?

1. **Vérification rapide**: `php check_table_structure.php`
2. **Test complet**: `php test_complete_workflow.php`
3. **Documentation**: Lire `SYNTHESE_SOLUTION_COMMANDES.md`
4. **Détails**: Consulter `RAPPORT_MIGRATION_COMMANDES.md`
5. **Troubleshoot**: Voir `GUIDE_VERIFICATION_COMMANDES.md`

---

**Dernière mise à jour**: 30 décembre 2024  
**Version**: 1.0  
**Status**: ✅ PRODUCTION READY
