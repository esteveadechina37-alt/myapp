<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=restaurant_gestion', 'root', '');
    
    // Utiliser une autre image Unsplash qui fonctionne
    $imageUrl = 'https://images.unsplash.com/photo-1535958636474-b021ee887b13?w=500&h=500&fit=crop';
    
    $stmt = $pdo->prepare('UPDATE categories SET image = ? WHERE nom = ?');
    $stmt->execute([$imageUrl, 'Boissons']);
    echo "✓ Image de la catégorie Boissons mise à jour avec succès!\n\n";
    echo "URL utilisée: " . $imageUrl . "\n";
    
    // Vérifier la mise à jour
    $query = $pdo->query('SELECT nom, image FROM categories WHERE nom = "Boissons"');
    $result = $query->fetch(PDO::FETCH_ASSOC);
    echo "\nVérification en base:\n";
    echo "Catégorie: " . $result['nom'] . "\n";
    echo "Image: " . $result['image'] . "\n";
} catch(Exception $e) {
    echo 'Erreur: ' . $e->getMessage();
}
?>
