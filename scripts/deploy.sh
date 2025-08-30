#!/bin/bash

# WordPress Deployment Script
# Usage: ./scripts/deploy.sh [staging|production]

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

ENVIRONMENT=${1:-staging}
TIMESTAMP=$(date +%Y%m%d-%H%M%S)

print_status "Starting deployment to $ENVIRONMENT environment..."

# Load environment variables
if [ -f .env ]; then
    source .env
else
    print_error ".env file not found!"
    exit 1
fi

# Pre-deployment checks
print_status "Running pre-deployment checks..."

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    print_error "Docker is not running!"
    exit 1
fi

# Test Docker Compose configuration
if ! docker-compose config > /dev/null 2>&1; then
    print_error "Docker Compose configuration is invalid!"
    exit 1
fi

# Backup database before deployment
print_status "Creating database backup..."
mkdir -p backups
if docker-compose ps database | grep -q "Up"; then
    docker-compose exec -T database mysqldump \
        -u root -p"$DB_ROOT_PASSWORD" \
        "$WP_DB_NAME" > "backups/backup-${TIMESTAMP}.sql"
    print_success "Database backup created: backups/backup-${TIMESTAMP}.sql"
else
    print_warning "Database container not running, skipping backup"
fi

# Pull latest changes
print_status "Pulling latest code changes..."
git pull origin $(git branch --show-current)

# Build and deploy
print_status "Building and deploying containers..."
docker-compose down
docker-compose up -d --build

# Wait for services to be ready
print_status "Waiting for services to start..."
sleep 30

# Health checks
print_status "Running health checks..."
if docker-compose ps | grep -q "unhealthy\|Exit"; then
    print_error "Some containers are not healthy!"
    docker-compose ps
    exit 1
fi

# WordPress updates (if container is running)
if docker-compose ps wordpress | grep -q "Up"; then
    print_status "Updating WordPress core..."
    docker-compose exec -T wordpress wp core update --allow-root || true
    
    print_status "Updating WordPress plugins..."
    docker-compose exec -T wordpress wp plugin update --all --allow-root || true
    
    print_status "Flushing WordPress cache..."
    docker-compose exec -T wordpress wp cache flush --allow-root || true
fi

# Final health check
print_status "Final health verification..."
sleep 10

if curl -f -s http://localhost:${NGINX_PORT:-8080}/health > /dev/null; then
    print_success "Deployment completed successfully!"
    print_status "WordPress is accessible at: http://localhost:${NGINX_PORT:-8080}"
else
    print_error "Health check failed!"
    exit 1
fi

# Cleanup old backups (keep last 5)
print_status "Cleaning up old backups..."
ls -t backups/backup-*.sql | tail -n +6 | xargs -r rm --

print_success "Deployment to $ENVIRONMENT completed!"
