<?php

// Test d'accès au dashboard
echo "\n=== TEST D'ACCÈS AU DASHBOARD ADMIN ===\n\n";

$pdo = new PDO("mysql:host=localhost;dbname=restaurant_gestion", "root", "");

// Test 1: Vérifier toutes les colonnes de commandes
echo "Test 1: Colonnes de la table commandes\n";
try {
    $result = $pdo->query("DESCRIBE commandes");
    $columns = $result->fetchAll(PDO::FETCH_ASSOC);
    $column_names = array_column($columns, 'Field');
    
    echo "Colonnes trouvées:\n";
    foreach ($column_names as $col) {
        echo "  - $col\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

// Test 2: Tester les requêtes du dashboard
echo "\n\nTest 2: Exécuter les requêtes du dashboard\n";

try {
    // Total clients
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM users WHERE role = 'client'");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "✅ Total clients: " . $result['total'] . "\n";
} catch (Exception $e) {
    echo "❌ Clients: " . $e->getMessage() . "\n";
}

try {
    // Total commandes
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM commandes");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "✅ Total commandes: " . $result['total'] . "\n";
} catch (Exception $e) {
    echo "❌ Commandes: " . $e->getMessage() . "\n";
}

try {
    // Total plats
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM plats");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "✅ Total plats: " . $result['total'] . "\n";
} catch (Exception $e) {
    echo "❌ Plats: " . $e->getMessage() . "\n";
}

try {
    // Total categories
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM categories");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "✅ Total catégories: " . $result['total'] . "\n";
} catch (Exception $e) {
    echo "❌ Catégories: " . $e->getMessage() . "\n";
}

try {
    // Commandes par statut
    $stmt = $pdo->query("SELECT statut, COUNT(*) as total FROM commandes GROUP BY statut");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "✅ Commandes par statut:\n";
    foreach ($results as $row) {
        echo "   - " . $row['statut'] . ": " . $row['total'] . "\n";
    }
} catch (Exception $e) {
    echo "❌ Statuts: " . $e->getMessage() . "\n";
}

try {
    // Dernieres commandes
    $stmt = $pdo->query("SELECT * FROM commandes ORDER BY heure_commande DESC LIMIT 10");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "✅ Dernières commandes: " . count($results) . " trouvées\n";
} catch (Exception $e) {
    echo "❌ Dernières commandes: " . $e->getMessage() . "\n";
}

try {
    // Plats populaires
    $stmt = $pdo->query("
        SELECT plats.nom, COUNT(*) as total, SUM(lignes_commandes.quantite) as quantite_totale 
        FROM lignes_commandes 
        JOIN plats ON lignes_commandes.plat_id = plats.id 
        GROUP BY plats.id, plats.nom 
        ORDER BY total DESC 
        LIMIT 5
    ");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "✅ Plats populaires: " . count($results) . " trouvés\n";
} catch (Exception $e) {
    echo "❌ Plats populaires: " . $e->getMessage() . "\n";
}

try {
    // Utilisateurs récents
    $stmt = $pdo->query("SELECT * FROM users WHERE role = 'client' ORDER BY created_at DESC LIMIT 5");
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "✅ Utilisateurs récents: " . count($results) . " trouvés\n";
} catch (Exception $e) {
    echo "❌ Utilisateurs: " . $e->getMessage() . "\n";
}

try {
    // Revenue
    $stmt = $pdo->query("SELECT SUM(montant_ttc) as revenue FROM factures");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "✅ Revenue total: " . ($result['revenue'] ?? 0) . " CFA\n";
} catch (Exception $e) {
    echo "❌ Revenue: " . $e->getMessage() . "\n";
}

echo "\n=== FIN DES TESTS ===\n";
echo "\n✅ Si tous les tests passent, le dashboard devrait fonctionner!\n\n";
?>
