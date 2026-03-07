#!/bin/bash

# Deploy Script untuk Hostinger
# Script ini akan mengupdate aplikasi dari GitHub repository

echo "🚀 Starting deployment process..."

# 1. Pull latest changes from GitHub
echo "📥 Pulling latest changes from GitHub..."
git pull origin main

# 2. Install/Update Composer dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

# 3. Install/Update NPM dependencies and build assets
echo "🎨 Building frontend assets..."
npm install
npm run build

# 4. Clear and cache Laravel configurations
echo "⚙️ Optimizing Laravel..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Run database migrations (if any)
echo "🗄️ Running database migrations..."
php artisan migrate --force

# 6. Clear application cache
echo "🧹 Clearing application cache..."
php artisan cache:clear

# 7. Set proper permissions
echo "🔐 Setting file permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache

echo "✅ Deployment completed successfully!"
echo "🌐 Your application is now updated!"