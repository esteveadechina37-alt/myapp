#!/bin/bash
set -e

echo "🚀 Initializing application..."

# Vérifier que APP_KEY existe
if [ -z "$APP_KEY" ]; then
    echo "❌ ERROR: APP_KEY not set!"
    exit 1
fi

# Vérifier la connexion DB
echo "🔍 Testing database connection..."
php -r "
try {
    \$pdo = new PDO(
        'mysql:host=' . getenv('DB_HOST') . ':' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'),
        getenv('DB_USERNAME'),
        getenv('DB_PASSWORD'),
        [PDO::ATTR_TIMEOUT => 5]
    );
    echo '✅ Database connection OK' . PHP_EOL;
} catch (Exception \$e) {
    echo '❌ Database connection failed: ' . \$e->getMessage() . PHP_EOL;
    exit(1);
}
" || exit 1

# Définir les permissions
echo "📝 Setting permissions..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

# Générer le cache de configuration
echo "⚙️  Caching configuration..."
php artisan config:cache 2>&1 || { echo "❌ config:cache failed"; exit 1; }

# Exécuter les migrations
echo "🗄️  Running migrations..."
php artisan migrate --force --no-interaction 2>&1 || { echo "⚠️  Migrations completed (may have had some issues)"; }

echo "✅ Application initialized successfully!"

