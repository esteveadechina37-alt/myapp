<?php
// Diagnostic page for Scalingo deployment
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Scalingo Diagnostic</title>
    <style>
        body { font-family: monospace; padding: 20px; background: #1e1e1e; color: #d4d4d4; }
        .good { color: #4ec9b0; }
        .bad { color: #f48771; }
        .info { color: #569cd6; }
        h1 { color: #dcdcaa; }
        pre { background: #252526; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>🔍 Scalingo Diagnostic</h1>
    
    <h2>Environment Variables</h2>
    <pre>
<?php
$env_vars = ['APP_ENV', 'APP_DEBUG', 'DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME'];
foreach ($env_vars as $var) {
    $val = getenv($var);
    if ($val === false) {
        echo "<span class=\"bad\">✗ $var: NOT SET</span>\n";
    } else {
        if (strpos($var, 'PASSWORD') !== false) {
            echo "<span class=\"good\">✓ $var: ****</span>\n";
        } else {
            echo "<span class=\"good\">✓ $var: " . htmlspecialchars($val) . "</span>\n";
        }
    }
}
?>
    </pre>

    <h2>Database Connection Test</h2>
    <pre>
<?php
try {
    $host = getenv('DB_HOST');
    $port = getenv('DB_PORT') ?: 3306;
    $db = getenv('DB_DATABASE');
    $user = getenv('DB_USERNAME');
    $pass = getenv('DB_PASSWORD');
    
    if (!$host || !$db || !$user) {
        echo "<span class=\"bad\">✗ Missing required database variables</span>\n";
    } else {
        $pdo = new PDO(
            "mysql:host=$host:$port;dbname=$db",
            $user,
            $pass,
            [PDO::ATTR_TIMEOUT => 5]
        );
        echo "<span class=\"good\">✓ Database connection successful!</span>\n";
        
        // Check sessions table
        $result = $pdo->query("SHOW TABLES LIKE 'sessions'");
        if ($result->rowCount() > 0) {
            echo "<span class=\"good\">✓ Sessions table exists</span>\n";
        } else {
            echo "<span class=\"bad\">✗ Sessions table NOT found (run migrations!)</span>\n";
        }
    }
} catch (Exception $e) {
    echo "<span class=\"bad\">✗ Database error: " . htmlspecialchars($e->getMessage()) . "</span>\n";
}
?>
    </pre>

    <h2>Laravel Configuration</h2>
    <pre>
<?php
if (file_exists(__DIR__ . '/../bootstrap/app.php')) {
    try {
        $app = require __DIR__ . '/../bootstrap/app.php';
        $db_driver = $app['config']['database.default'];
        $db_conns = $app['config']['database.connections'];
        
        echo "<span class=\"info\">Current DB Connection: " . htmlspecialchars($db_driver) . "</span>\n\n";
        
        if (isset($db_conns[$db_driver])) {
            echo "<span class=\"good\">✓ Configuration loaded</span>\n";
            echo "Driver: " . htmlspecialchars($db_conns[$db_driver]['driver']) . "\n";
        } else {
            echo "<span class=\"bad\">✗ Current connection not configured</span>\n";
        }
    } catch (Exception $e) {
        echo "<span class=\"bad\">✗ Error loading bootstrap: " . htmlspecialchars($e->getMessage()) . "</span>\n";
    }
} else {
    echo "<span class=\"bad\">✗ Bootstrap file not found</span>\n";
}
?>
    </pre>

    <h2>File Permissions</h2>
    <pre>
<?php
$paths = [
    'storage' => 'storage',
    'bootstrap/cache' => 'bootstrap/cache',
    'public' => 'public'
];

foreach ($paths as $name => $path) {
    $full_path = __DIR__ . '/../' . $path;
    if (is_writable($full_path)) {
        echo "<span class=\"good\">✓ $name: writable</span>\n";
    } else {
        echo "<span class=\"bad\">✗ $name: NOT writable</span>\n";
    }
}
?>
    </pre>

    <hr style="border-color: #444;">
    <p><span class="info">Generated: <?php echo date('Y-m-d H:i:s'); ?></span></p>
</body>
</html>
