# 🧪 DONNÉES DE TEST - Dashboard Client

Ce fichier contient les requêtes SQL pour créer des données de test permettant de vérifier le fonctionnement complet du dashboard client amélioré.

---

## 📋 Instructions

1. Connectez-vous à MySQL/PhpMyAdmin
2. Sélectionnez la base de données du restaurant
3. Exécutez les requêtes SQL ci-dessous

---

## 🔧 Configuration de Base

### Vérifier la structure des tables
```sql
-- Vérifier que les tables existent
SHOW TABLES LIKE 'commandes';
SHOW TABLES LIKE 'factures';
SHOW TABLES LIKE 'clients';

-- Vérifier les colonnes de la table commandes
DESCRIBE commandes;
```

---

## 📝 Données de Test

### 1. Créer un Client de Test
```sql
-- Insérer un client de test (ou récupérer un existant)
INSERT INTO clients (user_id, nom, email, telephone, adresse, created_at, updated_at) 
VALUES (1, 'Jean Dupont', 'jean.dupont@example.com', '+225 01 23 45 67', '123 Rue Test, Abidjan', NOW(), NOW())
ON DUPLICATE KEY UPDATE updated_at = NOW();

-- Récupérer l'ID du client
SELECT id FROM clients WHERE email = 'jean.dupont@example.com';
```

### 2. Commande Sur Place (EN COURS)
```sql
-- Créer une commande Sur Place en préparation
INSERT INTO commandes (
    numero,
    client_id,
    table_id,
    type_commande,
    statut,
    montant_total_ht,
    montant_tva,
    montant_total_ttc,
    est_payee,
    moyen_paiement,
    heure_commande,
    heure_remise_cuisine,
    heure_prete,
    heure_livraison_demandee,
    created_at,
    updated_at
) VALUES (
    CONCAT('CMD-', DATE_FORMAT(NOW(), '%Y%m%d'), '-001'),
    1, -- client_id (changer selon votre BD)
    2, -- table 2
    'sur_place',
    'en_preparation', -- État: en cours de préparation
    50000,
    9000,
    59000,
    0,
    NULL,
    NOW(),
    NOW(),
    NULL, -- Ne sera rempli que quand prête
    NULL,
    NOW(),
    NOW()
);
```

### 3. Commande à Emporter (PRÊTE - PAIEMENT)
```sql
-- Commande à emporter prête pour paiement
INSERT INTO commandes (
    numero,
    client_id,
    table_id,
    type_commande,
    statut,
    montant_total_ht,
    montant_tva,
    montant_total_ttc,
    est_payee,
    moyen_paiement,
    heure_commande,
    heure_remise_cuisine,
    heure_prete,
    heure_livraison_demandee,
    created_at,
    updated_at
) VALUES (
    CONCAT('CMD-', DATE_FORMAT(NOW(), '%Y%m%d'), '-002'),
    1,
    NULL,
    'a_emporter',
    'prete_a_emporter', -- Prête à emporter
    30000,
    5400,
    35400,
    0, -- Non payée
    NULL,
    NOW(),
    DATE_SUB(NOW(), INTERVAL 30 MINUTE),
    DATE_SUB(NOW(), INTERVAL 5 MINUTE), -- Prête depuis 5 min
    DATE_ADD(NOW(), INTERVAL 30 MINUTE), -- Retrait prévu dans 30 min
    NOW(),
    NOW()
);
```

### 4. Commande Livraison (EN LIVRAISON)
```sql
-- Commande en livraison
INSERT INTO commandes (
    numero,
    client_id,
    table_id,
    type_commande,
    statut,
    montant_total_ht,
    montant_tva,
    montant_total_ttc,
    est_payee,
    moyen_paiement,
    heure_commande,
    heure_remise_cuisine,
    heure_prete,
    heure_livraison_demandee,
    created_at,
    updated_at
) VALUES (
    CONCAT('CMD-', DATE_FORMAT(NOW(), '%Y%m%d'), '-003'),
    1,
    NULL,
    'livraison',
    'en_livraison', -- En cours de livraison
    75000,
    13500,
    88500,
    0,
    NULL,
    DATE_SUB(NOW(), INTERVAL 45 MINUTE),
    DATE_SUB(NOW(), INTERVAL 30 MINUTE),
    DATE_SUB(NOW(), INTERVAL 15 MINUTE),
    NOW(), -- Livraison estimée maintenant
    NOW(),
    NOW()
);
```

### 5. Commande Servie (PRÊTE POUR PAIEMENT - Sur Place)
```sql
-- Commande servie, prête pour paiement
INSERT INTO commandes (
    numero,
    client_id,
    table_id,
    type_commande,
    statut,
    montant_total_ht,
    montant_tva,
    montant_total_ttc,
    est_payee,
    moyen_paiement,
    heure_commande,
    heure_remise_cuisine,
    heure_prete,
    heure_livraison_demandee,
    created_at,
    updated_at
) VALUES (
    CONCAT('CMD-', DATE_FORMAT(NOW(), '%Y%m%d'), '-004'),
    1,
    1, -- Table 1
    'sur_place',
    'servie', -- Servie au client
    45000,
    8100,
    53100,
    0, -- Non encore payée
    NULL,
    DATE_SUB(NOW(), INTERVAL 25 MINUTE),
    DATE_SUB(NOW(), INTERVAL 20 MINUTE),
    DATE_SUB(NOW(), INTERVAL 10 MINUTE),
    NULL,
    NOW(),
    NOW()
);
```

### 6. Commande Payée (EXEMPLE DE FINALISÉE)
```sql
-- Commande complètement finalisée et payée
INSERT INTO commandes (
    numero,
    client_id,
    table_id,
    type_commande,
    statut,
    montant_total_ht,
    montant_tva,
    montant_total_ttc,
    est_payee,
    moyen_paiement,
    heure_commande,
    heure_remise_cuisine,
    heure_prete,
    heure_livraison_demandee,
    created_at,
    updated_at
) VALUES (
    CONCAT('CMD-', DATE_FORMAT(NOW(), '%Y%m%d'), '-005'),
    1,
    3,
    'sur_place',
    'payee', -- Complètement finalisée
    40000,
    7200,
    47200,
    1, -- PAYÉE
    'cash', -- Paiement en espèces
    DATE_SUB(NOW(), INTERVAL 2 HOUR),
    DATE_SUB(NOW(), INTERVAL 110 MINUTE),
    DATE_SUB(NOW(), INTERVAL 85 MINUTE),
    NULL,
    NOW(),
    NOW()
);
```

---

## 📊 Requête de Vérification

### Voir les commandes du client
```sql
SELECT 
    id,
    numero,
    type_commande,
    statut,
    montant_total_ttc,
    est_payee,
    moyen_paiement,
    heure_prete,
    created_at
FROM commandes
WHERE client_id = 1 -- ou votre client_id
ORDER BY created_at DESC;
```

### Voir les commandes "actives"
```sql
SELECT 
    id,
    numero,
    type_commande,
    statut,
    montant_total_ttc,
    est_payee,
    created_at
FROM commandes
WHERE client_id = 1
  AND statut IN ('enregistree', 'en_preparation', 'prete', 'prete_a_emporter', 'prete_a_livrer', 'en_livraison', 'servie')
ORDER BY created_at DESC;
```

### Vérifier les factures associées
```sql
SELECT 
    f.id,
    f.commande_id,
    f.montant_ttc,
    f.est_payee,
    f.date_paiement,
    c.numero as commande_numero
FROM factures f
JOIN commandes c ON f.commande_id = c.id
WHERE c.client_id = 1
ORDER BY f.created_at DESC;
```

---

## 🧪 Scénarios de Test

### Test 1: Affichage du Dashboard
1. **Connexion**: Se connecter avec un compte client
2. **Navigation**: Aller à `/client/dashboard`
3. **Vérification**: 
   - ✓ Section "Commandes En Cours" affiche 5 commandes
   - ✓ Section "Commandes Récentes" affiche les 5 récentes
   - ✓ Les timelines s'affichent correctement

### Test 2: Timeline Pour Chaque Type
1. **Sur Place**: Vérifier 7 étapes dans timeline
2. **À Emporter**: Vérifier 6 étapes, avec "Retrait à HH:MM"
3. **Livraison**: Vérifier 7 étapes, avec "En Livraison"

### Test 3: Section Paiement
1. **Commande Prête**: Vérifier que section paiement apparaît
2. **Sélection Méthode**: Cliquer sur chaque méthode (carte, espèces, mobile, chèque)
3. **Bouton Payer**: Vérifier activation du bouton
4. **Paiement**: Cliquer → Vérifier mise à jour statut

### Test 4: Facture Automatique
1. **Après Paiement**: Attendre refresh
2. **Vérifier**: Que la facture apparaît dans "Factures Récentes"
3. **Voir Facture**: Cliquer pour voir détails

### Test 5: Responsiveness
1. **Desktop**: 1920x1080 - Affichage normal
2. **Tablet**: 768x1024 - 2 colonnes
3. **Mobile**: 375x667 - 1 colonne, timeline verticale

---

## 🔍 Dépannage des Données

### Si les commandes ne s'affichent pas
```sql
-- Vérifier l'ID du client connecté
SELECT id, email FROM clients WHERE email = 'votre.email@example.com';

-- Vérifier les commandes
SELECT * FROM commandes WHERE client_id = X;

-- Vérifier les statuts valides
SELECT DISTINCT statut FROM commandes;
```

### Si le paiement ne fonctionne pas
```sql
-- Vérifier les champs de commande
DESCRIBE commandes;

-- Vérifier que est_payee est boolean/tinyint
SELECT COLUMN_NAME, COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME = 'commandes' AND COLUMN_NAME = 'est_payee';

-- Vérifier que moyen_paiement existe
SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME = 'commandes' AND COLUMN_NAME = 'moyen_paiement';
```

---

## 📈 Générer Plus de Données

### Script pour créer 10 commandes aléatoires
```sql
-- À exécuter plusieurs fois pour générer des commandes
DELIMITER //

CREATE PROCEDURE create_test_commands()
BEGIN
    DECLARE i INT DEFAULT 0;
    DECLARE type_cmd VARCHAR(20);
    DECLARE cmd_statut VARCHAR(30);
    DECLARE is_paid BOOLEAN;
    
    WHILE i < 10 DO
        -- Sélectionner un type aléatoire
        SET type_cmd = ELT(RAND()*3+1, 'sur_place', 'a_emporter', 'livraison');
        
        -- Sélectionner un statut aléatoire
        SET cmd_statut = CASE 
            WHEN type_cmd = 'sur_place' THEN ELT(RAND()*3+1, 'enregistree', 'en_preparation', 'servie')
            WHEN type_cmd = 'a_emporter' THEN ELT(RAND()*2+1, 'en_preparation', 'prete_a_emporter')
            ELSE ELT(RAND()*2+1, 'en_preparation', 'en_livraison')
        END;
        
        SET is_paid = RAND() > 0.7; -- 30% de chances payée
        
        INSERT INTO commandes (
            numero, client_id, type_commande, statut,
            montant_total_ht, montant_tva, montant_total_ttc,
            est_payee, moyen_paiement,
            created_at, updated_at
        ) VALUES (
            CONCAT('TEST-', DATE_FORMAT(NOW(), '%Y%m%d%H%i%s'), '-', i),
            1,
            type_cmd,
            cmd_statut,
            FLOOR(RAND()*100000)+10000,
            FLOOR(RAND()*20000)+1000,
            FLOOR(RAND()*120000)+11000,
            is_paid,
            IF(is_paid, ELT(RAND()*4+1, 'cash', 'card', 'mobile', 'check'), NULL),
            DATE_SUB(NOW(), INTERVAL FLOOR(RAND()*7) DAY),
            NOW()
        );
        
        SET i = i + 1;
    END WHILE;
END //

DELIMITER ;

-- Exécuter la procédure
CALL create_test_commands();
```

---

## ✅ Checklist de Validation

- [ ] Client de test créé avec ID connu
- [ ] 5 commandes créées avec différents statuts
- [ ] Chaque commande a un type_commande valide
- [ ] Au moins 1 commande prête pour paiement
- [ ] Les montants TTC sont > 0
- [ ] Les heure_remise_cuisine et heure_prete sont remplies
- [ ] Dashboard s'affiche sans erreurs
- [ ] Timelines affichent correctement
- [ ] Section paiement apparaît pour commandes prêtes

---

## 📱 Notes Importantes

1. **Client ID**: Remplacer `1` par l'ID réel de votre client
2. **Table ID**: Remplacer `2` par l'ID réel de vos tables
3. **Dates**: Les dates sont recalculées avec `NOW()` et `DATE_SUB`
4. **Montants**: En FCFA (sans décimales pour les centimes)

---

## 🚀 Après les Tests

1. Nettoyer les données de test:
```sql
DELETE FROM commandes WHERE client_id = 1 AND numero LIKE 'CMD-%';
DELETE FROM commandes WHERE numero LIKE 'TEST-%';
```

2. Vérifier que les vraies données fonctionnent correctement

3. Procéder aux tests en production avec de vraies commandes

