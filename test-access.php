<?php

// Test complet du système
echo "\n=== TEST D'ACCÈS AUX PAGES ===\n";

$base_url = 'http://localhost:8000';

// Fonction helper pour tester les URLs
function test_url($url, $description) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    try {
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if ($http_code >= 200 && $http_code < 400) {
            echo "✅ $description - HTTP $http_code\n";
            return true;
        } else {
            echo "❌ $description - HTTP $http_code\n";
            return false;
        }
    } catch (Exception $e) {
        echo "❌ $description - Erreur: " . $e->getMessage() . "\n";
        return false;
    }
}

// Tests
echo "\n--- Pages publiques ---\n";
test_url("$base_url/", "Accueil (/)");
test_url("$base_url/apropos", "À Propos (/apropos)");
test_url("$base_url/faq", "FAQ (/faq)");
test_url("$base_url/contact", "Contact (/contact) ✨ NOUVEAU");

echo "\n--- Routes protégées ---\n";
echo "ℹ️ Les routes admin nécessitent une authentification\n";
echo "   Routes: /admin/dashboard, /admin/employes, etc.\n";

echo "\n--- Base de données ---\n";
try {
    $pdo = new PDO("mysql:host=localhost;dbname=restaurant_gestion", "root", "");
    
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM users");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "✅ Total utilisateurs: " . $result['total'] . "\n";
    
    $stmt = $pdo->prepare("SELECT email, role, statut FROM users WHERE role = 'admin'");
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($admin) {
        echo "✅ Admin: " . $admin['email'] . " (Rôle: " . $admin['role'] . ", Statut: " . $admin['statut'] . ")\n";
    }
} catch (Exception $e) {
    echo "❌ Erreur BD: " . $e->getMessage() . "\n";
}

echo "\n=== PROCÉDURE DE TEST ===\n";
echo "1. Allez sur http://localhost:8000\n";
echo "2. Cliquez sur 'Contact' dans la navbar\n";
echo "3. Vous devriez voir la page de contact avec le formulaire ✅\n\n";
echo "4. Retournez à l'accueil et cliquez sur 'Connexion'\n";
echo "5. Connectez-vous avec:\n";
echo "   Email: admin@restaurant.com\n";
echo "6. Vous serez redirigé vers /admin/dashboard ✅\n\n";
echo "7. Dans le dashboard, cliquez sur 'Employés'\n";
echo "8. Vous pouvez créer/modifier/supprimer des employés ✅\n";

echo "\n✨ TOUS LES LIENS DOIVENT MAINTENANT FONCTIONNER!\n\n";
?>
