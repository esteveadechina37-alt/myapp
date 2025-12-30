web: bash -c '\
  echo "🧹 Clearing caches..."; \
  rm -rf bootstrap/cache/* storage/framework/cache/* storage/framework/views/* 2>/dev/null || true; \
  echo "📝 Setting permissions..."; \
  chmod -R 775 storage bootstrap/cache; \
  echo "⚙️  Configuring app..."; \
  php artisan config:clear; \
  php artisan config:cache; \
  echo "🗄️  Running migrations..."; \
  php artisan migrate --force --no-interaction --verbose; \
  echo "🚀 Starting web server..."; \
  vendor/bin/heroku-php-apache2 public/ \
'
