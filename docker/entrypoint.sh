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
    echo "📋 Database config: HOST=$DB_HOST, PORT=${DB_PORT:-3306}, USER=$DB_USERNAME, DB=$DB_DATABASE"

    max_tries=60
    counter=0

    # Use PHP to test database connection (more reliable than mysql client)
    until php -r "
        \$host = getenv('DB_HOST');
        \$port = getenv('DB_PORT') ?: '3306';
        \$user = getenv('DB_USERNAME');
        \$pass = getenv('DB_PASSWORD');
        \$db = getenv('DB_DATABASE');

        try {
            \$pdo = new PDO(\"mysql:host=\$host;port=\$port;dbname=\$db\", \$user, \$pass, [
                PDO::ATTR_TIMEOUT => 5,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
            echo 'Connected successfully';
            exit(0);
        } catch (PDOException \$e) {
            echo 'Connection failed: ' . \$e->getMessage();
            exit(1);
        }
    " 2>&1; do
        counter=$((counter + 1))
        if [ $counter -ge $max_tries ]; then
            echo "❌ Could not connect to database after $max_tries attempts"
            echo "🔍 Debug: Trying to resolve hostname..."
            getent hosts "$DB_HOST" || echo "Could not resolve hostname $DB_HOST"
            echo "🔍 Debug: Trying to ping..."
            ping -c 1 "$DB_HOST" 2>&1 || echo "Could not ping $DB_HOST"
            exit 1
        fi
        echo "Waiting for database... (attempt $counter/$max_tries)"
        sleep 3
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
