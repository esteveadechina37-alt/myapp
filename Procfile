web: vendor/bin/heroku-php-apache2 -i /app/.platform/etc/php.ini public/
release: bash -c 'chmod -R 775 storage bootstrap/cache && php artisan config:cache && php artisan migrate --force --no-interaction 2>&1'
