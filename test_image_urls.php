<?php
// Testons plusieurs URLs pour trouver une qui fonctionne
$urls = [
    'https://images.pexels.com/photos/312418/pexels-photo-312418.jpeg?w=500&h=500&fit=crop',
    'https://images.pexels.com/photos/3407817/pexels-photo-3407817.jpeg?w=500&h=500&fit=crop',
    'https://images.unsplash.com/photo-1535958636474-b021ee887b13?w=500&h=500&fit=crop',
    'https://images.unsplash.com/photo-1554866585-c69b73cee580?w=500&h=500&fit=crop',
    'https://images.unsplash.com/photo-1514432324607-2e467f4af445?w=500&h=500&fit=crop',
    'https://images.unsplash.com/photo-1608270861620-7912c5f87b10?w=500&h=500&fit=crop',
];

echo "=== Test des URLs d'images ===\n\n";

foreach ($urls as $index => $url) {
    echo "Test " . ($index + 1) . ": " . $url . "\n";
    
    $context = stream_context_create([
        'http' => [
            'method' => 'HEAD',
            'timeout' => 5
        ]
    ]);
    
    $headers = @get_headers($url, 1, $context);
    
    if ($headers && strpos($headers[0], '200') !== false) {
        echo "✓ FONCTIONNE - Code: " . $headers[0] . "\n";
    } else {
        echo "✗ ERREUR\n";
    }
    echo "\n";
}
?>
