<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=restaurant_gestion', 'root', '');
    $stmt = $pdo->prepare('UPDATE categories SET image = ? WHERE nom = ?');
    $stmt->execute(['https://cdn.pixabay.com/photo/2017/07/16/10/43/glass-2508584_1280.jpg', 'Boissons']);
    echo "Image de la catégorie Boissons mise à jour avec succès!\n";
    
    // Vérifier la mise à jour
    $query = $pdo->query('SELECT nom, image FROM categories WHERE nom = "Boissons"');
    $result = $query->fetch(PDO::FETCH_ASSOC);
    echo "Nouvelle URL: " . $result['image'] . "\n";
} catch(Exception $e) {
    echo 'Erreur: ' . $e->getMessage();
}
?>
