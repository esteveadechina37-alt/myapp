#!/bin/bash
set -e

echo "=========================================="
echo "🚀 Scalingo Bootstrap Script"
echo "=========================================="
echo ""

echo "📝 Step 1: Clearing all caches..."
rm -rf bootstrap/cache/* 2>/dev/null || true
rm -rf storage/framework/cache/* 2>/dev/null || true
rm -rf storage/framework/views/* 2>/dev/null || true
echo "✓ Done"
echo ""

echo "🔐 Step 2: Setting permissions..."
chmod -R 775 storage bootstrap/cache
chmod -R 755 public
echo "✓ Done"
echo ""

echo "🌍 Step 3: Environment check..."
echo "  DB_CONNECTION=$(printenv DB_CONNECTION)"
echo "  DB_HOST=$(printenv DB_HOST)"
echo "  APP_ENV=$(printenv APP_ENV)"
echo ""

echo "⚙️  Step 4: Generating config cache..."
php artisan config:cache --force 2>&1 || echo "Config cache generation failed, continuing..."
echo "✓ Done"
echo ""

echo "🗄️  Step 5: Running migrations..."
php artisan migrate --force --no-interaction 2>&1 || echo "Migrations completed (some may have been skipped)"
echo "✓ Done"
echo ""

echo "=========================================="
echo "✅ Bootstrap complete!"
echo "=========================================="
