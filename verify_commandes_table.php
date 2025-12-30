<?php
// Script de vérification de la structure de la table commandes

try {
    $host = 'localhost';
    $dbname = 'gestion_restaurant';
    $user = 'root';
    $pass = '';
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Vérifier la structure
    $stmt = $pdo->query("DESCRIBE commandes");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "=== STRUCTURE DE LA TABLE COMMANDES ===\n";
    echo str_pad("Colonne", 30) . " | " . str_pad("Type", 30) . " | Null | Défaut\n";
    echo str_repeat("-", 90) . "\n";
    
    foreach ($columns as $col) {
        echo str_pad($col['Field'], 30) . " | " 
            . str_pad($col['Type'], 30) . " | " 
            . str_pad($col['Null'], 5) . " | " 
            . $col['Default'] . "\n";
    }
    
    echo "\n✅ Total colonnes: " . count($columns) . "\n";
    
    // Vérifier les index
    $stmt = $pdo->query("SHOW INDEXES FROM commandes");
    $indexes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "\n=== INDEX ===\n";
    foreach ($indexes as $idx) {
        echo "- " . $idx['Key_name'] . " (" . $idx['Column_name'] . ")\n";
    }
    
    // Vérifier les clés étrangères
    $stmt = $pdo->query("SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME='commandes' AND REFERENCED_TABLE_NAME IS NOT NULL");
    $fks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "\n=== CLÉS ÉTRANGÈRES ===\n";
    foreach ($fks as $fk) {
        echo "- " . $fk['COLUMN_NAME'] . " -> " . $fk['REFERENCED_TABLE_NAME'] . "\n";
    }
    
    echo "\n✅ Migration réussie!\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
?>
