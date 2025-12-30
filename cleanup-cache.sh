#!/bin/bash
set -e

echo "🔥 EMERGENCY CACHE CLEANUP"
echo "========================="

# Remove all Laravel caches
echo "Clearing all caches..."
rm -rf bootstrap/cache/*
rm -rf storage/framework/cache/*
rm -rf storage/framework/views/*
rm -rf storage/logs/*

# Clear environment
echo "Clearing environment cache..."
php artisan config:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

# Verify DB_CONNECTION
echo "Verifying DB_CONNECTION..."
php -r "
echo 'DB_CONNECTION from ENV: ' . getenv('DB_CONNECTION') . PHP_EOL;
echo 'DB_HOST from ENV: ' . getenv('DB_HOST') . PHP_EOL;
echo 'DB_PORT from ENV: ' . getenv('DB_PORT') . PHP_EOL;
echo 'DB_DATABASE from ENV: ' . getenv('DB_DATABASE') . PHP_EOL;
"

echo "✅ Cleanup complete!"
