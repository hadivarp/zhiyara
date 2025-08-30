-- MySQL initialization script for WordPress
-- This script runs when the database container starts for the first time

-- Create additional databases if needed
-- CREATE DATABASE IF NOT EXISTS `zhiyara_wp_staging`;
-- CREATE DATABASE IF NOT EXISTS `zhiyara_wp_backup`;

-- Set proper MySQL settings for WordPress
SET GLOBAL sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_DATE,NO_ZERO_IN_DATE,ERROR_FOR_DIVISION_BY_ZERO';

-- Optimize MySQL for WordPress
SET GLOBAL innodb_buffer_pool_size = 128M;
SET GLOBAL max_connections = 100;
SET GLOBAL query_cache_size = 16M;
SET GLOBAL query_cache_type = 1;

-- Create indexes for better performance (will be applied after WordPress installation)
-- These are WordPress-specific optimizations that can be applied later
-- ALTER TABLE wp_posts ADD INDEX idx_post_name (post_name);
-- ALTER TABLE wp_posts ADD INDEX idx_post_parent (post_parent);
-- ALTER TABLE wp_postmeta ADD INDEX idx_meta_key_value (meta_key, meta_value(10));

FLUSH PRIVILEGES;
