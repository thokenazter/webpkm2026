#!/bin/bash

# Quick Deploy Script - Untuk deployment cepat tanpa build assets
# Cocok untuk update kode kecil yang tidak memerlukan rebuild frontend

echo "⚡ Quick deployment started..."

# Pull latest changes
echo "📥 Pulling from GitHub..."
git pull origin main

# Update composer (production mode)
echo "📦 Updating dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# Clear caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Run migrations if any
echo "🗄️ Running migrations..."
php artisan migrate --force --no-interaction

# Optimize for production
echo "⚡ Optimizing..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Quick deployment completed!"