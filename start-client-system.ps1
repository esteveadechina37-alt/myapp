# Script de démarrage rapide du système client (Windows PowerShell)

Write-Host "============================================" -ForegroundColor Cyan
Write-Host "  🚀 Démarrage Système Client Restaurant" -ForegroundColor Green
Write-Host "============================================" -ForegroundColor Cyan
Write-Host ""

# Vérifier que nous sommes dans le bon répertoire
if (!(Test-Path "artisan")) {
    Write-Host "❌ Erreur: Veuillez exécuter ce script depuis la racine du projet" -ForegroundColor Red
    exit 1
}

Write-Host "1️⃣  Vérification de l'environnement..." -ForegroundColor Yellow
$result = php artisan tinker --execute="echo 'Laravel OK';" 2>&1
if ($result -match "Laravel OK") {
    Write-Host "   ✓ Laravel OK" -ForegroundColor Green
} else {
    Write-Host "   ❌ Laravel non accessible" -ForegroundColor Red
    exit 1
}

Write-Host ""
Write-Host "2️⃣  Vérification des routes..." -ForegroundColor Yellow
$routes = php artisan route:list 2>&1 | Select-String "client" | Measure-Object -Line
$routeCount = $routes.Lines
if ($routeCount -gt 15) {
    Write-Host "   ✓ Routes client enregistrées ($routeCount routes)" -ForegroundColor Green
} else {
    Write-Host "   ❌ Routes client non trouvées" -ForegroundColor Red
}

Write-Host ""
Write-Host "3️⃣  Vérification des fichiers de vue..." -ForegroundColor Yellow

$views = @(
    "dashboard.blade.php",
    "menu.blade.php",
    "cart.blade.php",
    "checkout.blade.php",
    "order-detail.blade.php",
    "order-history.blade.php",
    "invoices.blade.php"
)

foreach ($view in $views) {
    $path = "resources\views\client\$view"
    if (Test-Path $path) {
        Write-Host "   ✓ $view" -ForegroundColor Green
    } else {
        Write-Host "   ❌ $view manquant" -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "4️⃣  Vérification du contrôleur..." -ForegroundColor Yellow
if (Test-Path "app\Http\Controllers\Client\ClientOrderController.php") {
    Write-Host "   ✓ ClientOrderController.php" -ForegroundColor Green
} else {
    Write-Host "   ❌ ClientOrderController.php manquant" -ForegroundColor Red
}

Write-Host ""
Write-Host "============================================" -ForegroundColor Cyan
Write-Host "  ✅ Système Prêt!" -ForegroundColor Green
Write-Host "============================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Prochaines étapes:" -ForegroundColor Yellow
Write-Host "1. Créer un utilisateur client:"
Write-Host "   PS> php artisan tinker"
Write-Host ""
Write-Host "2. Démarrer le serveur:"
Write-Host "   PS> php artisan serve"
Write-Host ""
Write-Host "3. Accéder au dashboard:"
Write-Host "   http://localhost:8000/client/dashboard"
Write-Host ""

# Demander si démarrer le serveur
Write-Host "Voulez-vous démarrer le serveur maintenant? (y/n)" -ForegroundColor Cyan
$response = Read-Host
if ($response -eq "y" -or $response -eq "Y") {
    Write-Host ""
    Write-Host "Démarrage du serveur Laravel..." -ForegroundColor Green
    php artisan serve
}
