#!/bin/sh
set -e

echo "============================================="
echo "Starting application setup..."
echo "============================================="

# Print environment info for debugging
echo "PHP Version: $(php -v | head -n 1)"
echo "Node Version: $(node -v 2>/dev/null || echo 'Not installed')"
echo "Working directory: $(pwd)"

# Create storage directories if they don't exist
echo "Creating storage directories..."
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

# Set proper permissions
echo "Setting permissions..."
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Create storage symlink
echo "Creating storage symlink..."
php artisan storage:link --force 2>/dev/null || true

# Test database connection
echo "Testing database connection..."
php artisan db:show 2>&1 || echo "Warning: Database connection test failed"

# Run migrations if database is available
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Running database migrations..."
    php artisan migrate --force 2>&1 || echo "Migration skipped or failed"
fi

# Clear old cache first (important for production)
echo "Clearing old cache..."
php artisan config:clear 2>&1 || true
php artisan route:clear 2>&1 || true
php artisan view:clear 2>&1 || true

# Cache configuration (now environment variables are available)
echo "Caching Laravel configuration..."
php artisan config:cache 2>&1 || echo "Warning: config:cache failed"
php artisan route:cache 2>&1 || echo "Warning: route:cache failed"
php artisan view:cache 2>&1 || echo "Warning: view:cache failed"

# Verify critical files exist
echo "Verifying critical files..."
if [ ! -f /var/www/html/public/index.php ]; then
    echo "ERROR: public/index.php not found!"
    exit 1
fi

if [ ! -d /var/www/html/vendor ]; then
    echo "ERROR: vendor directory not found!"
    exit 1
fi

echo "============================================="
echo "Application setup complete. Starting services..."
echo "============================================="

# Execute the main command (supervisord)
exec "$@"
