#!/bin/bash
set -e

echo "🚀 Initializing application..."

# Définir les permissions
echo "📝 Setting permissions..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || true
chmod -R 755 public 2>/dev/null || true

# Clear tous les caches
echo "🧹 Clearing caches..."
php artisan config:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

# Générer le cache de configuration
echo "⚙️  Caching configuration..."
php artisan config:cache

# Générer le cache des routes
echo "🛣️  Caching routes..."
php artisan route:cache

# Générer le cache des vues
echo "👁️  Caching views..."
php artisan view:cache

# Migrer la base de données
echo "🗄️  Running migrations..."
php artisan migrate --force --no-interaction

echo "✅ Application initialized successfully!"
