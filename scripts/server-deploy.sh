#!/bin/bash

# WordPress Integration Deployment Script for Existing Rails Server
# Usage: ./scripts/server-deploy.sh

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

print_status() { echo -e "${BLUE}[INFO]${NC} $1"; }
print_success() { echo -e "${GREEN}[SUCCESS]${NC} $1"; }
print_warning() { echo -e "${YELLOW}[WARNING]${NC} $1"; }
print_error() { echo -e "${RED}[ERROR]${NC} $1"; }

print_status "Deploying WordPress integration to existing Rails server..."

# Check if we're in the right directory
if [ ! -f "docker-compose.server.yml" ]; then
    print_error "docker-compose.server.yml not found! Run this from the WordPress project root."
    exit 1
fi

# Load environment variables
if [ ! -f .env ]; then
    print_error ".env file not found! Please create it from .env.example"
    exit 1
fi

source .env

# Validate WordPress environment variables
if [ -z "$WP_DB_PASSWORD" ] || [ "$WP_DB_PASSWORD" = "your_secure_wp_password_here" ]; then
    print_error "Please set a secure WP_DB_PASSWORD in .env file"
    exit 1
fi

if [ -z "$WP_DB_ROOT_PASSWORD" ] || [ "$WP_DB_ROOT_PASSWORD" = "your_secure_root_password_here" ]; then
    print_error "Please set a secure WP_DB_ROOT_PASSWORD in .env file"
    exit 1
fi

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    print_error "Docker is not running!"
    exit 1
fi

# Check if crm_abz_net network exists (from Rails setup)
if ! docker network ls | grep -q "crm_abz_net"; then
    print_error "crm_abz_net network not found! Make sure your Rails CRM is running first."
    exit 1
fi

# Create necessary directories
print_status "Creating WordPress directories..."
mkdir -p volumes/wordpress
mkdir -p volumes/wordpress-db
mkdir -p backups/wordpress
mkdir -p logs/wordpress

# Set permissions
print_status "Setting proper permissions..."
chmod +x scripts/*.sh
sudo chown -R www-data:www-data volumes/wordpress/ 2>/dev/null || true

# Backup existing nginx config (if this is an update)
if docker ps | grep -q "crm_abz_nginx"; then
    print_status "Backing up current nginx configuration..."
    docker cp crm_abz_nginx:/etc/nginx/nginx.conf ./backups/nginx.conf.backup.$(date +%Y%m%d-%H%M%S) || true
fi

# Deploy WordPress containers
print_status "Starting WordPress containers..."
docker-compose -f docker-compose.server.yml up -d

# Wait for WordPress to be ready
print_status "Waiting for WordPress to start..."
sleep 45

# Check WordPress health
print_status "Checking WordPress health..."
if ! docker-compose -f docker-compose.server.yml ps | grep -q "Up"; then
    print_error "WordPress containers failed to start properly!"
    docker-compose -f docker-compose.server.yml logs
    exit 1
fi

# Update nginx configuration
print_status "Updating nginx configuration..."

# Copy new nginx config
docker cp ./nginx/nginx-updated.conf crm_abz_nginx:/etc/nginx/nginx.conf

# Copy WordPress site config
docker cp ./nginx/sites/wordpress-blog.conf crm_abz_nginx:/etc/nginx/sites-available/

# Test nginx configuration
if docker exec crm_abz_nginx nginx -t; then
    print_status "Nginx configuration is valid. Reloading..."
    docker exec crm_abz_nginx nginx -s reload
    print_success "Nginx configuration updated successfully!"
else
    print_error "Nginx configuration test failed! Restoring backup..."
    docker cp ./backups/nginx.conf.backup.* crm_abz_nginx:/etc/nginx/nginx.conf 2>/dev/null || true
    docker exec crm_abz_nginx nginx -s reload
    exit 1
fi

# Final health checks
print_status "Running final health checks..."
sleep 10

# Check if WordPress is accessible through nginx
if curl -f -s http://localhost/health > /dev/null 2>&1; then
    print_success "WordPress integration deployed successfully!"
else
    print_warning "WordPress containers are running but health check failed. This might be normal on first setup."
fi

print_success "Deployment completed!"
echo ""
print_status "Next steps:"
echo "  1. Point zhiyara.com DNS to your server IP: 65.109.219.107"
echo "  2. Set up SSL certificates for zhiyara.com"
echo "  3. Complete WordPress installation at: http://zhiyara.com"
echo "  4. Configure WordPress settings and themes"
echo ""
print_status "Your setup:"
echo "  WordPress Main Site: http://zhiyara.com (new)"
echo "  WordPress Admin: http://zhiyara.com/wp-admin"
echo "  Rails CRM: http://65.109.219.107:4000 (existing, direct access)"
echo ""
print_warning "Remember to:"
echo "  - Update zhiyara.com DNS settings"
echo "  - Configure SSL certificates"
echo "  - Set up WordPress backups"
