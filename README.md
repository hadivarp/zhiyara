# Zhiyara WordPress Project

A production-ready WordPress setup with Docker, designed for seamless Rails integration and automated deployment.

## 🚀 Quick Start

### Local Development
```bash
# Clone and setup
git clone <your-repo>
cd zhiyara
cp .env.example .env
# Edit .env with your credentials
./scripts/setup.sh local
```

### Production Deployment
```bash
./scripts/setup.sh production
```

## 📁 Project Structure

```
zhiyara/
├── docker-compose.yml              # Production configuration
├── docker-compose-local.yml        # Local development
├── docker-compose.rails.yml        # Rails integration
├── .env.example                     # Environment template
├── nginx/
│   ├── nginx.conf                  # Main Nginx config
│   └── sites/
│       ├── wordpress.conf          # WordPress-only config
│       └── combined.conf           # Rails + WordPress config
├── config/
│   ├── php.ini                     # PHP customizations
│   └── wp-config-extra.php         # WordPress extras
├── scripts/
│   ├── setup.sh                    # Environment setup
│   ├── deploy.sh                   # Deployment script
│   └── mysql-init.sql              # Database initialization
└── .github/workflows/
    └── deploy.yml                  # CI/CD pipeline
```

## 🐳 Services

- **WordPress**: PHP 8.1 FPM with custom configuration
- **MySQL**: 8.0 with optimizations
- **Nginx**: Alpine with security headers and caching
- **Redis**: 7 Alpine for caching
- **phpMyAdmin**: Database management (local only)

## 🔧 Configuration

### Environment Variables (.env)
```bash
WP_DB_NAME=zhiyara_wp
WP_DB_USER=wp_user
WP_DB_PASSWORD=your_secure_password
DB_ROOT_PASSWORD=your_root_password
REDIS_PASSWORD=your_redis_password
NGINX_PORT=8080
DOMAIN=yourdomain.com
```

### Rails Integration

To integrate with Rails:

1. **Setup Rails + WordPress**:
```bash
docker-compose -f docker-compose.yml -f docker-compose.rails.yml up -d
```

2. **URL Structure**:
- Main app: `https://yourdomain.com/`
- WordPress blog: `https://yourdomain.com/blog/`
- Rails API: `https://yourdomain.com/api/`
- WordPress admin: `https://yourdomain.com/wp-admin/`

## 🚀 Deployment

### Automated Deployment (GitHub Actions)
- Push to `main` → deploys to staging
- Push to `production` → deploys to production
- Includes automatic backups and health checks

### Manual Deployment
```bash
./scripts/deploy.sh production
```

### Server Setup Requirements
```bash
# Install Docker & Docker Compose
curl -fsSL https://get.docker.com -o get-docker.sh
sh get-docker.sh
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# Clone project
git clone <your-repo> /var/www/zhiyara-wordpress
cd /var/www/zhiyara-wordpress
cp .env.example .env
# Configure .env for production
```

## 🔒 Security Features

- Rate limiting for login pages
- Security headers (XSS, CSRF protection)
- File upload restrictions
- Hidden sensitive files
- Redis password protection
- Database access restrictions

## 📊 Monitoring & Health Checks

All services include health checks:
- WordPress: PHP-FPM status
- MySQL: Connection test
- Nginx: HTTP response
- Redis: Ping test

Health endpoint: `http://your-domain/health`

## 🔄 Backup & Recovery

### Automatic Backups
- Database backups before each deployment
- Keeps last 5 backups automatically

### Manual Backup
```bash
# Database backup
docker-compose exec database mysqldump -u root -p$DB_ROOT_PASSWORD $WP_DB_NAME > backup.sql

# WordPress files backup
docker cp wordpress_app:/var/www/html ./wordpress-backup
```

## 🛠 Development

### Local URLs
- WordPress: http://localhost:8080
- phpMyAdmin: http://localhost:8081
- Database: localhost:3307

### Useful Commands
```bash
# View logs
docker-compose logs -f wordpress

# WordPress CLI
docker-compose exec wordpress wp --info --allow-root

# Database access
docker-compose exec database mysql -u root -p

# Redis CLI
docker-compose exec redis redis-cli
```

## 🔧 Customization

### Adding Plugins/Themes
Place in `volumes/wordpress/wp-content/` for local development or use WordPress admin.

### PHP Configuration
Edit `config/php.ini` and rebuild containers.

### Nginx Configuration
Edit `nginx/sites/wordpress.conf` for WordPress-only setup or `nginx/sites/combined.conf` for Rails integration.

## 🚨 Troubleshooting

### Common Issues

1. **Permission Issues**:
```bash
sudo chown -R www-data:www-data volumes/wordpress/
```

2. **Database Connection**:
```bash
docker-compose logs database
docker-compose restart wordpress
```

3. **Memory Issues**:
```bash
# Increase in config/php.ini
memory_limit = 512M
```

## 📈 Performance Optimization

- OPcache enabled
- Redis object caching
- Nginx static file caching
- Gzip compression
- Image optimization ready

## 🤝 Contributing

1. Fork the repository
2. Create feature branch
3. Make changes
4. Test locally
5. Submit pull request

## 📝 License

[Your License Here]
