#!/bin/bash

# WordPress Docker Setup Script
# Usage: ./scripts/setup.sh [local|production]

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Determine environment
ENVIRONMENT=${1:-local}

print_status "Setting up WordPress environment: $ENVIRONMENT"

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    print_error "Docker is not running. Please start Docker and try again."
    exit 1
fi

# Create necessary directories
print_status "Creating necessary directories..."
mkdir -p volumes/wordpress
mkdir -p volumes/database  
mkdir -p volumes/redis
mkdir -p backups
mkdir -p logs
mkdir -p nginx/logs

# Create .env file if it doesn't exist
if [ ! -f .env ]; then
    print_status "Creating .env file from template..."
    cp .env.example .env
    print_warning "Please edit .env file with your actual credentials!"
    print_warning "Default passwords are not secure for production!"
fi

# Set permissions
print_status "Setting proper permissions..."
chmod +x scripts/*.sh
chmod 755 volumes/
sudo chown -R www-data:www-data volumes/wordpress/ 2>/dev/null || true

if [ "$ENVIRONMENT" = "local" ]; then
    print_status "Setting up LOCAL development environment..."
    
    # Create shared network (for integration with Rails)
    docker network create shared_network 2>/dev/null || print_warning "Network 'shared_network' already exists"
    
    # Start services
    print_status "Starting WordPress containers..."
    docker-compose -f docker-compose.local.yml up -d
    
    # Wait for services to be ready
    print_status "Waiting for services to start..."
    sleep 30
    
    # Check service health
    print_status "Checking service status..."
    docker-compose -f docker-compose.local.yml ps
    
    print_success "Local WordPress setup complete!"
    echo ""
    print_status "Access URLs:"
    echo "  WordPress: http://localhost:8080"
    echo "  phpMyAdmin: http://localhost:8081"
    echo "  Database: localhost:3307"
    echo ""
    print_warning "Complete WordPress installation by visiting: http://localhost:8080"
    
elif [ "$ENVIRONMENT" = "production" ]; then
    print_status "Setting up PRODUCTION environment..."
    
    # Validate required environment variables
    source .env
    
    if [ -z "$WP_DB_PASSWORD" ] || [ "$WP_DB_PASSWORD" = "your_secure_wp_password_here" ]; then
        print_error "Please set a secure WP_DB_PASSWORD in .env file"
        exit 1
    fi
    
    if [ -z "$DB_ROOT_PASSWORD" ] || [ "$DB_ROOT_PASSWORD" = "your_secure_root_password_here" ]; then
        print_error "Please set a secure DB_ROOT_PASSWORD in .env file"
        exit 1
    fi
    
    # Create shared network
    docker network create shared_network 2>/dev/null || print_warning "Network 'shared_network' already exists"
    
    # Start production services
    print_status "Starting production containers..."
    docker-compose up -d
    
    # Wait for services
    print_status "Waiting for services to start..."
    sleep 60
    
    # Check health
    print_status "Checking service health..."
    docker-compose ps
    
    print_success "Production WordPress setup complete!"
    echo ""
    print_status "WordPress is accessible through your Rails nginx proxy at /blog"
    print_warning "Don't forget to:"
    echo "  1. Update your Rails nginx configuration"
    echo "  2. Set up SSL certificates"
    echo "  3. Configure backups"
    echo "  4. Set up monitoring"
    
else
    print_error "Invalid environment. Use 'local' or 'production'"
    exit 1
fi

print_status "Setup script completed!"
