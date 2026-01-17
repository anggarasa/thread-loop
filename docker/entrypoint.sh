#!/bin/sh
set -e

echo "🚀 Starting ThreadLoop application..."

# Create storage directories if they don't exist
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/log/supervisor

# Set proper permissions
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Wait for database to be ready (if using MySQL)
if [ -n "$DB_HOST" ]; then
    echo "⏳ Waiting for database connection..."
    max_tries=30
    counter=0
    until mysql -h"$DB_HOST" -P"${DB_PORT:-3306}" -u"$DB_USERNAME" -p"$DB_PASSWORD" -e "SELECT 1" > /dev/null 2>&1; do
        counter=$((counter + 1))
        if [ $counter -ge $max_tries ]; then
            echo "❌ Could not connect to database after $max_tries attempts"
            exit 1
        fi
        echo "Waiting for database... (attempt $counter/$max_tries)"
        sleep 2
    done
    echo "✅ Database is ready!"
fi

# Generate application key if not set
if [ -z "$APP_KEY" ]; then
    echo "⚠️  APP_KEY not set, generating new key..."
    php artisan key:generate --force
fi

# Clear and cache configuration (production optimization)
echo "📦 Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force

# Create storage link
php artisan storage:link --force 2>/dev/null || true

echo "✅ Application is ready!"
echo "🌐 Starting Supervisor..."

# Start Supervisor to manage all processes
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
