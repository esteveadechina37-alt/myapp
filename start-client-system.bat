@echo off
REM Script de démarrage rapide pour le système de commande client
REM Windows PowerShell version

echo.
echo ====================================
echo   SYSTEME DE COMMANDE CLIENT
echo   Restaurant Management System
echo ====================================
echo.

REM Vérifier que nous sommes dans le bon répertoire
if not exist "app\Http\Controllers\Client\ClientOrderController.php" (
    echo ERREUR: Vous n'êtes pas dans le répertoire racine du projet!
    pause
    exit /b 1
)

echo ✓ Projet trouvé
echo.

REM Menu
echo Sélectionnez une action:
echo.
echo 1 - Lancer le serveur Laravel
echo 2 - Vérifier les routes client
echo 3 - Vérifier les données BD
echo 4 - Voir le guide complet
echo 5 - Effacer le cache
echo 6 - Quitter
echo.

set /p choice="Votre choix (1-6): "

if "%choice%"=="1" (
    echo.
    echo Lancement du serveur...
    echo Le serveur démarre sur http://localhost:8000
    echo Appuyez sur Ctrl+C pour arrêter le serveur
    echo.
    timeout /t 2
    php artisan serve
    goto menu
)

if "%choice%"=="2" (
    echo.
    echo Affichage des routes client...
    echo.
    php artisan route:list | findstr /I "client"
    echo.
    pause
    goto menu
)

if "%choice%"=="3" (
    echo.
    echo Vérification des données...
    echo.
    php test_db_count.php
    echo.
    pause
    goto menu
)

if "%choice%"=="4" (
    echo.
    echo Ouverture du guide...
    echo.
    if exist "CLIENT_SYSTEM_GUIDE.md" (
        echo Guide disponible: CLIENT_SYSTEM_GUIDE.md
    ) else (
        echo Guide non trouvé!
    )
    echo.
    pause
    goto menu
)

if "%choice%"=="5" (
    echo.
    echo Effacement du cache...
    php artisan cache:clear
    php artisan route:cache --clear
    echo Cache effacé!
    echo.
    pause
    goto menu
)

if "%choice%"=="6" (
    echo.
    echo Au revoir!
    exit /b 0
)

echo Choix invalide!
pause
:menu
goto :eof
