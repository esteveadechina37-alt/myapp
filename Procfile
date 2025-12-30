web: bash -c '\
  echo "🔥 NUCLEAR CACHE CLEANUP"; \
  rm -rf bootstrap/cache/* storage/framework/cache/* storage/framework/views/* 2>/dev/null || true; \
  rm -rf /tmp/php-* 2>/dev/null || true; \
  echo "📝 Setting permissions..."; \
  chmod -R 775 storage bootstrap/cache; \
  echo "🌍 Verifying environment..."; \
  php -r "echo \"DB_CONNECTION=\" . getenv(\"DB_CONNECTION\") . PHP_EOL;"; \
  echo "⚙️  REGENERATING config cache..."; \
  php artisan config:cache --force; \
  echo "🗄️  Running migrations..."; \
  php artisan migrate --force --no-interaction --verbose 2>&1 || echo "Migrations completed with status: $?"; \
  echo "🚀 Starting web server..."; \
  vendor/bin/heroku-php-apache2 public/ \
'
