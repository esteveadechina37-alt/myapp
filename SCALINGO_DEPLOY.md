# 🚀 SCALINGO DEPLOYMENT GUIDE

## Configuration requise dans le dashboard Scalingo

### 1. Variables d'environnement à copier
Copiez EXACTEMENT ces variables (copier depuis `.env.scalingo`):

```
APP_NAME="Gestion Restaurant"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:Kmpsn/3nTpZoZ5lahgpPdnlKmT5tiUjZVI1lXFEiSY8=
APP_URL=https://myappname.osc-fr1.scalingo.io
APP_LOCALE=fr
APP_FALLBACK_LOCALE=fr
APP_FAKER_LOCALE=fr_FR
APP_MAINTENANCE_DRIVER=file
BCRYPT_ROUNDS=12
LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug
DB_CONNECTION=mysql
DB_HOST=myappname-1024.mysql.c.osc-fr1.scalingo-dbs.com
DB_PORT=34327
DB_DATABASE=myappname_1024
DB_USERNAME=myappname_1024
DB_PASSWORD=lGJcUlNdE7Ue2Fx4olONFOBAqLksJsD0xGT0KtQa_BigkrCXfh1AFKXccF3gx4wZ
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
BROADCAST_CONNECTION=database
QUEUE_CONNECTION=database
CACHE_STORE=database
MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_SCHEME=null
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
VITE_APP_NAME="${APP_NAME}"
```

⚠️ **REMPLACEZ ces valeurs par les vôtres:**
- `APP_URL` → votre domaine Scalingo
- `DB_HOST` → depuis votre SCALINGO_MYSQL_URL
- `DB_PORT` → depuis votre SCALINGO_MYSQL_URL
- `DB_DATABASE` → depuis votre SCALINGO_MYSQL_URL
- `DB_USERNAME` → depuis votre SCALINGO_MYSQL_URL
- `DB_PASSWORD` → depuis votre SCALINGO_MYSQL_URL

### 2. Fichiers importants
- ✅ `Procfile` - Commandes de démarrage
- ✅ `composer.json` - Dépendances PHP
- ✅ `package.json` - Dépendances Node
- ✅ `.env.scalingo` - Modèle de configuration

## Étapes de déploiement

### 1. Commitez les changements
```bash
git add -A
git commit -m "Deploy: Scalingo configuration"
git push
```

### 2. Dans le dashboard Scalingo
- Allez dans **Settings > Environment Variables**
- Copiez-collez toutes les variables du `.env.scalingo`
- Remplacez les valeurs DB_* par vos vraies identifiants MySQL
- Remplacez `APP_URL` par votre vrai domaine

### 3. Relancez le déploiement
- Cliquez sur **Deploy** ou attendez que Scalingo détecte le push
- Observez les logs: `Waiting for your application to boot...`

### 4. Vérifiez que ça marche
Après le déploiement:
```bash
curl https://myappname.osc-fr1.scalingo.io/health.php
```

Vous devriez voir:
```json
{"status":"ok","message":"Application is healthy"}
```

## Dépannage

### L'app dit "Error deploying"
1. Ouvrez les **Application Logs** (pas les deployment logs) dans le dashboard
2. Cherchez les erreurs mentionnées
3. Vérifiez que:
   - `APP_KEY` commence par `base64:`
   - `DB_HOST`, `DB_USERNAME`, `DB_PASSWORD` sont corrects
   - `DB_PORT` est 34327 (pas 3306)

### "Waiting for your application to boot" puis erreur
- Les variables d'environnement ne sont pas définies correctement
- Ou la base de données n'est pas accessible
- Vérifiez que tous les `DB_*` sont définis

### 503 sur /health.php
C'est normal si la base n'est pas prête. C'est diagnostique.

## Support
- Logs détaillés: `scalingo logs -a myappname`
- SSH: `scalingo ssh -a myappname`
