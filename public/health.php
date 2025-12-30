<?php
// Health check endpoint for Scalingo
header('Content-Type: application/json');

try {
    // Check if app is bootstrapped
    if (!file_exists(__DIR__ . '/../bootstrap/app.php')) {
        throw new Exception('App bootstrap file not found');
    }

    // Check database connection
    if (!function_exists('env')) {
        require __DIR__ . '/../vendor/autoload.php';
    }

    $pdo = new PDO(
        sprintf(
            'mysql:host=%s:%s;dbname=%s',
            $_ENV['DB_HOST'] ?? env('DB_HOST'),
            $_ENV['DB_PORT'] ?? env('DB_PORT', 3306),
            $_ENV['DB_DATABASE'] ?? env('DB_DATABASE')
        ),
        $_ENV['DB_USERNAME'] ?? env('DB_USERNAME'),
        $_ENV['DB_PASSWORD'] ?? env('DB_PASSWORD'),
        [PDO::ATTR_TIMEOUT => 5]
    );

    http_response_code(200);
    echo json_encode(['status' => 'ok', 'message' => 'Application is healthy']);
} catch (Exception $e) {
    http_response_code(503);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>
