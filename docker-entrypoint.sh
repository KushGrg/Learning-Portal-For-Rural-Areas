#!/bin/bash

echo "🚀 Starting Laravel Docker Setup..."

# --- Step 1: Clear old config cache ---
# Prevents stale DB_HOST=127.0.0.1 from cached config
echo "🧹 Clearing old config cache..."
php artisan config:clear 2>/dev/null || true

# --- Step 2: Copy Environment File ---
echo "📋 Copying .env.docker to .env..."
cp /var/www/.env.docker /var/www/.env

# --- Step 3: Install Dependencies if Missing ---
if [ ! -d "/var/www/vendor" ] || [ -z "$(ls -A /var/www/vendor 2>/dev/null)" ]; then
    echo "📦 Installing composer dependencies..."
    composer install --no-dev --optimize-autoloader --no-scripts
fi

# --- Step 3b: Install Node Dependencies & Build Assets ---
if [ ! -d "/var/www/node_modules" ] || [ -z "$(ls -A /var/www/node_modules 2>/dev/null)" ]; then
    echo "📦 Installing node dependencies..."
    yarn install
fi
# --- Step 4: Generate Application Key ---
if ! grep -q "APP_KEY=base64:" /var/www/.env 2>/dev/null; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# --- Step 5: Wait for MySQL ---
echo "⏳ Waiting for MySQL to be ready..."
MAX_ATTEMPTS=30
ATTEMPT=1

while [ $ATTEMPT -le $MAX_ATTEMPTS ]; do
    if php -r "new PDO('mysql:host=mysql;port=3306', 'root', 'root'); echo 'connected';" 2>/dev/null | grep -q "connected"; then
        echo "✅ MySQL is ready!"
        break
    fi

    if [ $ATTEMPT -eq $MAX_ATTEMPTS ]; then
        echo "❌ MySQL not ready after $MAX_ATTEMPTS seconds"
        exit 1
    fi

    echo "   Attempt $ATTEMPT/$MAX_ATTEMPTS..."
    sleep 1
    ATTEMPT=$((ATTEMPT + 1))
done

# --- Step 6: Run Migrations ---
echo "🗄️ Running database migrations..."
php artisan migrate --force

# --- Step 7: Cache Config ---
echo "📦 Caching config..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# --- Step 8: Link Storage ---
php artisan storage:link 2>/dev/null || true

echo "✅ Setup complete! Starting Laravel server..."

# --- Step 9: Execute Main Command ---
exec "$@"
