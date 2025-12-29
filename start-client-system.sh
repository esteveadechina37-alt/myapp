#!/bin/bash
# Script de démarrage rapide du système client

echo "============================================"
echo "  🚀 Démarrage Système Client Restaurant"
echo "============================================"
echo ""

# Vérifier que nous sommes dans le bon répertoire
if [ ! -f "artisan" ]; then
    echo "❌ Erreur: Veuillez exécuter ce script depuis la racine du projet"
    exit 1
fi

echo "1️⃣  Vérification de l'environnement..."
php artisan tinker --execute="echo 'Laravel OK';" 2>/dev/null > /dev/null
if [ $? -eq 0 ]; then
    echo "   ✓ Laravel OK"
else
    echo "   ❌ Laravel non accessible"
    exit 1
fi

echo ""
echo "2️⃣  Vérification des routes..."
routes=$(php artisan route:list 2>/dev/null | grep -c "client")
if [ "$routes" -gt 15 ]; then
    echo "   ✓ Routes client enregistrées ($routes routes)"
else
    echo "   ❌ Routes client non trouvées"
fi

echo ""
echo "3️⃣  Vérification des fichiers de vue..."
if [ -f "resources/views/client/dashboard.blade.php" ]; then
    echo "   ✓ dashboard.blade.php"
fi
if [ -f "resources/views/client/menu.blade.php" ]; then
    echo "   ✓ menu.blade.php"
fi
if [ -f "resources/views/client/cart.blade.php" ]; then
    echo "   ✓ cart.blade.php"
fi
if [ -f "resources/views/client/checkout.blade.php" ]; then
    echo "   ✓ checkout.blade.php"
fi
if [ -f "resources/views/client/order-detail.blade.php" ]; then
    echo "   ✓ order-detail.blade.php"
fi
if [ -f "resources/views/client/order-history.blade.php" ]; then
    echo "   ✓ order-history.blade.php"
fi
if [ -f "resources/views/client/invoices.blade.php" ]; then
    echo "   ✓ invoices.blade.php"
fi

echo ""
echo "4️⃣  Vérification du contrôleur..."
if [ -f "app/Http/Controllers/Client/ClientOrderController.php" ]; then
    echo "   ✓ ClientOrderController.php"
else
    echo "   ❌ ClientOrderController.php manquant"
fi

echo ""
echo "============================================"
echo "  ✅ Système Prêt!"
echo "============================================"
echo ""
echo "Prochaines étapes:"
echo "1. Créer un utilisateur client:"
echo "   $ php artisan tinker"
echo ""
echo "2. Démarrer le serveur:"
echo "   $ php artisan serve"
echo ""
echo "3. Accéder au dashboard:"
echo "   http://localhost:8000/client/dashboard"
echo ""
