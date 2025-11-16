#!/bin/bash

# Deployment script for The Lobby application
# Run this after pulling changes from git

echo "🚀 Starting deployment..."

# Remove bootstrap cache files that may have old service provider references
echo "📦 Clearing bootstrap cache..."
rm -f bootstrap/cache/config.php
rm -f bootstrap/cache/routes.php
rm -f bootstrap/cache/services.php
rm -f bootstrap/cache/packages.php

# Install composer dependencies (production mode)
echo "📚 Installing composer dependencies..."
composer install --no-dev --optimize-autoloader --no-scripts

# Regenerate autoloader
echo "🔄 Regenerating autoloader..."
composer dump-autoload --optimize --no-scripts

# Clear application caches
echo "🧹 Clearing application caches..."
php artisan route:clear 2>/dev/null || true
php artisan config:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true

# Rebuild frontend assets if package.json changed
if [ -f "package.json" ]; then
    echo "🎨 Building frontend assets..."
    npm install --silent
    npm run build
fi

echo "✅ Deployment complete!"
echo ""
echo "🔗 Application is ready at: https://thelobbys.mostech.net"
