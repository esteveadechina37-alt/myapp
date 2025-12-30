#!/bin/bash

# Script pour vérifier les logs Scalingo
# Usage: ./debug-scalingo.sh

echo "=== FETCHING SCALINGO LOGS ==="
echo ""
echo "Run this command to see application logs:"
echo "scalingo logs -a myappname --lines 100"
echo ""
echo "Or for real-time logs:"
echo "scalingo logs -a myappname -f"
echo ""
echo "=== DATABASE VARIABLES CHECK ==="
echo "scalingo env -a myappname | grep DB_"
echo ""
echo "=== SSH DEBUG (if needed) ==="
echo "scalingo ssh -a myappname"
echo ""
echo "Then in SSH:"
echo "  cd /app"
echo "  php artisan tinker"
echo "  >>> config('database.default')"
echo "  >>> config('database.connections')"
