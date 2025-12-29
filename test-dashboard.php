<?php

// Test du dashboard admin
echo "\n=== TEST DU DASHBOARD ADMIN ===\n\n";

// Vérifier la BD
$pdo = new PDO("mysql:host=localhost;dbname=restaurant_gestion", "root", "");

// Test 1: Vérifier que la colonne montant_ttc existe
echo "Test 1: Vérifier les colonnes de la table factures\n";
try {
    $result = $pdo->query("DESCRIBE factures");
    $columns = $result->fetchAll(PDO::FETCH_ASSOC);
    $column_names = array_column($columns, 'Field');
    
    echo "Colonnes trouvées:\n";
    foreach ($column_names as $col) {
        echo "  - $col\n";
    }
    
    if (in_array('montant_ttc', $column_names)) {
        echo "✅ La colonne montant_ttc existe\n";
    } else {
        echo "❌ La colonne montant_ttc N'EXISTE PAS!\n";
    }
    
    if (in_array('montant_total', $column_names)) {
        echo "❌ La colonne montant_total existe (doit être supprimée)\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

// Test 2: Tester la requête de somme
echo "\n\nTest 2: Tester la requête SUM sur montant_ttc\n";
try {
    $result = $pdo->query("SELECT SUM(montant_ttc) as revenue_total FROM factures");
    $revenue = $result->fetch(PDO::FETCH_ASSOC);
    echo "✅ Revenue total (factures): " . ($revenue['revenue_total'] ?? 0) . " CFA\n";
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

// Test 3: Vérifier l'admin
echo "\n\nTest 3: Vérifier l'admin\n";
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'admin'");
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($admin) {
        echo "✅ Admin trouvé: " . $admin['email'] . "\n";
        
        // Tester la statistique des clients
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM users WHERE role = 'client'");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "✅ Clients: " . $result['total'] . "\n";
        
        // Tester les commandes
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM commandes");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "✅ Commandes: " . $result['total'] . "\n";
        
        // Tester les plats
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM plats");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "✅ Plats: " . $result['total'] . "\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}

echo "\n=== FIN DES TESTS ===\n";
echo "\n✅ Le dashboard admin devrait maintenant fonctionner!\n";
echo "   Allez à: http://localhost:8000/admin/dashboard après connexion\n\n";
?>
