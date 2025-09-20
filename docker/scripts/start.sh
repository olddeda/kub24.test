#!/bin/bash

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

print_status() {
    echo -e "${GREEN}[START]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[START]${NC} $1"
}

# Create .env file and check if application needs initialization
if [ ! -f /var/www/.env ]; then
    print_status "Creating .env file from .env.docker..."
    cp /var/www/.env.docker /var/www/.env
    print_status "No .env file found, initializing application..."
    /var/www/docker/scripts/init.sh
else
    print_status "Application already configured (.env exists), skipping initialization"
fi

# Start supervisor
print_status "Starting Supervisor..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
