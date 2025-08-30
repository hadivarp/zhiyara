<?php
/**
 * Additional WordPress configuration
 * This file is included by wp-config.php
 */

// Redis Cache Configuration
define('WP_REDIS_HOST', 'redis');
define('WP_REDIS_PORT', 6379);
define('WP_REDIS_PASSWORD', $_ENV['REDIS_PASSWORD'] ?? '');
define('WP_REDIS_DATABASE', 0);

// Security keys (generate new ones for production)
define('AUTH_KEY',         'put your unique phrase here');
define('SECURE_AUTH_KEY',  'put your unique phrase here');
define('LOGGED_IN_KEY',    'put your unique phrase here');
define('NONCE_KEY',        'put your unique phrase here');
define('AUTH_SALT',        'put your unique phrase here');
define('SECURE_AUTH_SALT', 'put your unique phrase here');
define('LOGGED_IN_SALT',   'put your unique phrase here');
define('NONCE_SALT',       'put your unique phrase here');

// Performance optimizations
define('WP_CACHE', true);
define('COMPRESS_CSS', true);
define('COMPRESS_SCRIPTS', true);
define('CONCATENATE_SCRIPTS', true);
define('ENFORCE_GZIP', true);

// Security enhancements
define('DISALLOW_FILE_EDIT', true);
define('DISALLOW_FILE_MODS', false); // Allow plugin/theme updates
define('FORCE_SSL_ADMIN', false); // Set to true in production with SSL

// Automatic updates
define('WP_AUTO_UPDATE_CORE', 'minor');
define('AUTOMATIC_UPDATER_DISABLED', false);

// Memory and timeout limits
ini_set('memory_limit', '256M');
ini_set('max_execution_time', 300);

// Multisite support (uncomment if needed)
// define('WP_ALLOW_MULTISITE', true);

// Custom content directory (if needed for Rails integration)
// define('WP_CONTENT_DIR', '/var/www/html/wp-content');
// define('WP_CONTENT_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/wp-content');
?>
