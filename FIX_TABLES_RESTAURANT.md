# ✅ FIX: Table 'tables_restaurants' n'existe pas

## Problème
```
SQLSTATE[42S02]: Base table or view not found: 1146 
Table 'restaurant_gestion.tables_restaurants' doesn't exist
```

## Cause Trouvée
Discordance entre le nom de la table dans la migration et les références dans le code:

- **Migration** crée: `tables_restaurant` (singular)
- **Code utilisait**: `tables_restaurants` (plural)

## Fichiers Corrigés

### 1. ClientOrderController.php (Ligne 273)
**Avant**:
```php
'table_id' => 'nullable|exists:tables_restaurants,id',
```

**Après**:
```php
'table_id' => 'nullable|exists:tables_restaurant,id',
```

### 2. OrderController.php (Ligne 199)
**Avant**:
```php
'table_id' => 'nullable|integer|exists:tables_restaurants,id',
```

**Après**:
```php
'table_id' => 'nullable|integer|exists:tables_restaurant,id',
```

## Vérification
- ✅ Migration: `create_tables_restaurant_table.php` crée `tables_restaurant`
- ✅ Modèle: `TableRestaurant.php` utilise `protected $table = 'tables_restaurant'`
- ✅ Controllers: Tous les controllers utilisent maintenant `tables_restaurant`
- ✅ Pas d'erreurs de syntaxe

## Résultat
✅ Erreur résolue - La table `tables_restaurant` est maintenant correctement référencée partout.

