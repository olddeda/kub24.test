#!/bin/bash

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}[INIT]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[INIT]${NC} $1"
}

print_error() {
    echo -e "${RED}[INIT]${NC} $1"
}

# Wait for database to be ready
print_status "Waiting for database to be ready..."
until pg_isready -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USERNAME" -d "$DB_NAME" > /dev/null 2>&1; do
    print_warning "Database is not ready yet, waiting..."
    sleep 2
done
print_status "Database is ready!"

# Wait for Redis to be ready
print_status "Waiting for Redis to be ready..."
until redis-cli -h "$REDIS_HOST" -p "$REDIS_PORT" ping > /dev/null 2>&1; do
    print_warning "Redis is not ready yet, waiting..."
    sleep 2
done
print_status "Redis is ready!"

# Change to application directory
cd /var/www

# Ensure all required directories exist with proper permissions
print_status "Creating required directories..."
mkdir -p runtime/{cache,logs,mail,debug} web/assets
chown -R www-data:www-data runtime web/assets
chmod -R 777 runtime web/assets

# Install dependencies
print_status "Installing Composer dependencies..."
if composer install --optimize-autoloader; then
    print_status "Composer dependencies installed successfully"
else
    print_error "Failed to install Composer dependencies"
    exit 1
fi

# Run migrations
print_status "Running database migrations..."
if php yii migrate --interactive=0; then
    print_status "Migrations completed successfully"
else
    print_error "Failed to run migrations"
    exit 1
fi

# Initialize RBAC
print_status "Initializing RBAC system..."
if php yii rbac/init; then
    print_status "RBAC initialized successfully"
else
    print_error "Failed to initialize RBAC"
    exit 1
fi

# Seed database
print_status "Seeding database with test data..."
if php yii seed/all; then
    print_status "Database seeded successfully"
else
    print_error "Failed to seed database"
    exit 1
fi

# Create admin user
print_status "Creating admin user..."
if php yii user/create-admin admin admin@kub24.local admin123; then
    print_status "Admin user created successfully"
    echo -e "${GREEN}[INFO]${NC} ========================================="
    echo -e "${GREEN}[INFO]${NC} ADMIN USER CREDENTIALS:"
    echo -e "${GREEN}[INFO]${NC} Username: admin"
    echo -e "${GREEN}[INFO]${NC} Email:    admin@kub24.local"
    echo -e "${GREEN}[INFO]${NC} Password: admin123"
    echo -e "${GREEN}[INFO]${NC} ========================================="
else
    print_warning "Admin user creation failed (may already exist)"
    echo -e "${YELLOW}[INFO]${NC} ========================================="
    echo -e "${YELLOW}[INFO]${NC} DEFAULT ADMIN CREDENTIALS:"
    echo -e "${YELLOW}[INFO]${NC} Username: admin"
    echo -e "${YELLOW}[INFO]${NC} Email:    admin@kub24.local"
    echo -e "${YELLOW}[INFO]${NC} Password: admin123"
    echo -e "${YELLOW}[INFO]${NC} ========================================="
fi

print_status "Application initialization completed successfully!"

echo -e "${GREEN}[INFO]${NC} ========================================="
echo -e "${GREEN}[INFO]${NC} APPLICATION READY!"
echo -e "${GREEN}[INFO]${NC} ========================================="
echo -e "${GREEN}[INFO]${NC} Web Application: http://localhost:8080"
echo -e "${GREEN}[INFO]${NC} Adminer (DB):    http://localhost:8081"
echo -e "${GREEN}[INFO]${NC} ========================================="
