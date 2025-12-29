<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=restaurant_gestion', 'root', '');
    
    // Essayer une nouvelle URL d'image plus fiable
    $imageUrl = 'https://images.unsplash.com/photo-1608270861620-7912c5f87b10?w=500&h=500&fit=crop';
    
    $stmt = $pdo->prepare('UPDATE categories SET image = ? WHERE nom = ?');
    $stmt->execute([$imageUrl, 'Boissons']);
    echo "Image de la catégorie Boissons mise à jour avec la nouvelle URL!\n";
    echo "Nouvelle URL: " . $imageUrl . "\n";
    
    // Vérifier la mise à jour
    $query = $pdo->query('SELECT nom, image FROM categories WHERE nom = "Boissons"');
    $result = $query->fetch(PDO::FETCH_ASSOC);
    echo "Vérification: " . $result['image'] . "\n";
} catch(Exception $e) {
    echo 'Erreur: ' . $e->getMessage();
}
?>
