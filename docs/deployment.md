# EuroMart Egypt - Production Deployment Guide

## Prerequisites

- Ubuntu 24.04 LTS server
- Domain name pointing to server IP
- SSH access with sudo privileges
- Git installed

## 1. System Preparation

### Update System
```bash
sudo apt update && sudo apt upgrade -y
sudo timedatectl set-timezone Africa/Cairo
```

### Install Required Packages
```bash
sudo apt install -y nginx mysql-server redis-server \
    php8.3-fpm php8.3-cli php8.3-mysql php8.3-redis \
    php8.3-gd php8.3-bcmath php8.3-zip php8.3-mbstring \
    php8.3-curl php8.3-xml php8.3-intl \
    composer git unzip curl supervisor certbot python3-certbot-nginx
```

### Install Node.js
```bash
curl -fsSL https://deb.nodesource.com/setup_22.x | sudo -E bash -
sudo apt install -y nodejs
```

## 2. Database Setup

```bash
sudo mysql_secure_installation
sudo mysql -e "CREATE DATABASE euromart CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
sudo mysql -e "CREATE USER 'euromart'@'localhost' IDENTIFIED BY 'StrongPassword123!';"
sudo mysql -e "GRANT ALL PRIVILEGES ON euromart.* TO 'euromart'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"
```

## 3. Redis Configuration

```bash
sudo systemctl enable redis-server
sudo systemctl start redis-server
sudo redis-cli ping
# Should respond: PONG
```

## 4. Application Deployment

### Clone Repository
```bash
sudo mkdir -p /var/www
sudo chown -R $USER:$USER /var/www
cd /var/www
git clone https://github.com/your-org/euromart-egypt.git
cd euromart-egypt
```

### Install Dependencies
```bash
composer install --no-dev --optimize-autoloader
npm install
npm run build
```

### Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` with production values:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://euromartegypt.com

DB_DATABASE=euromart
DB_USERNAME=euromart
DB_PASSWORD=StrongPassword123!

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
```

### Migrate and Seed
```bash
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
```

### Optimize
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

## 5. Nginx Configuration

Create `/etc/nginx/sites-available/euromart`:

```nginx
server {
    listen 80;
    server_name euromartegypt.com www.euromartegypt.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name euromartegypt.com www.euromartegypt.com;
    root /var/www/euromart-egypt/public;

    ssl_certificate /etc/letsencrypt/live/euromartegypt.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/euromartegypt.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";
    add_header Referrer-Policy "strict-origin-when-cross-origin";
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_read_timeout 300;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    location ~* \.(jpg|jpeg|png|gif|ico|webp|svg|woff|woff2|ttf|eot|css|js)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
        access_log off;
    }

    client_max_body_size 20M;
    gzip on;
    gzip_types text/plain text/css application/json application/javascript text/xml application/xml application/xml+rss text/javascript image/svg+xml;
    gzip_comp_level 6;
    gzip_min_length 256;
}
```

Enable site:
```bash
sudo ln -s /etc/nginx/sites-available/euromart /etc/nginx/sites-enabled/
sudo rm /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl reload nginx
```

## 6. SSL Certificate

```bash
sudo certbot --nginx -d euromartegypt.com -d www.euromartegypt.com
```

## 7. Queue Worker

Create `/etc/supervisor/conf.d/euromart-queue.conf`:

```ini
[program:euromart-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/euromart-egypt/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/euromart-egypt/storage/logs/queue-worker.log
stopwaitsecs=3600
```

Start supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start all
```

## 8. Cron Job

```bash
crontab -e
# Add:
* * * * * cd /var/www/euromart-egypt && php artisan schedule:run >> /dev/null 2>&1
```

## 9. File Permissions

```bash
sudo chown -R www-data:www-data /var/www/euromart-egypt/storage
sudo chown -R www-data:www-data /var/www/euromart-egypt/bootstrap/cache
sudo chmod -R 775 /var/www/euromart-egypt/storage
sudo chmod -R 775 /var/www/euromart-egypt/bootstrap/cache
```

## 10. Firewall

```bash
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
```

## 11. Monitoring

### Check PHP-FPM Status
```bash
sudo systemctl status php8.3-fpm
```

### Check Queue Worker
```bash
sudo supervisorctl status euromart-queue:*
```

### Check Nginx Logs
```bash
sudo tail -f /var/log/nginx/euromart-error.log
sudo tail -f /var/log/nginx/euromart-access.log
```

### Check Laravel Logs
```bash
tail -f /var/www/euromart-egypt/storage/logs/laravel.log
```

## 12. Backup Strategy

### Automated Daily Backups

Create `/usr/local/bin/backup-euromart.sh`:

```bash
#!/bin/bash
BACKUP_DIR="/backups/euromart"
DATE=$(date +%Y%m%d_%H%M%S)
mkdir -p $BACKUP_DIR

# Database backup
mysqldump -u euromart -p StrongPassword123! euromart | gzip > $BACKUP_DIR/db_$DATE.sql.gz

# Storage backup
tar -czf $BACKUP_DIR/storage_$DATE.tar.gz -C /var/www/euromart-egypt storage/app/public

# Clean up backups older than 30 days
find $BACKUP_DIR -name "*.gz" -type f -mtime +30 -delete

# Upload to Cloudflare R2 (optional)
# rclone copy $BACKUP_DIR r2:euromart-backups/
```

Make executable and add cron:
```bash
chmod +x /usr/local/bin/backup-euromart.sh
crontab -e
# Add: 0 2 * * * /usr/local/bin/backup-euromart.sh
```

## 13. Health Check

Create `/var/www/euromart-egypt/public/health.php`:
```php
<?php
$checks = [
    'php_version' => PHP_VERSION >= 8.3,
    'mysql' => @fsockopen('localhost', 3306, $errno, $errstr, 2) ? true : false,
    'redis' => @fsockopen('localhost', 6379, $errno, $errstr, 2) ? true : false,
];
header('Content-Type: application/json');
echo json_encode(['status' => in_array(false, $checks) ? 'degraded' : 'healthy', 'checks' => $checks]);
```

## 14. Performance Tuning

### PHP-FPM (pool settings)
Edit `/etc/php/8.3/fpm/pool.d/www.conf`:
```ini
pm = dynamic
pm.max_children = 50
pm.start_servers = 10
pm.min_spare_servers = 5
pm.max_spare_servers = 20
pm.max_requests = 500
```

### MySQL (tuning)
Edit `/etc/mysql/my.cnf`:
```ini
[mysqld]
innodb_buffer_pool_size = 1G
innodb_log_file_size = 256M
innodb_flush_log_at_trx_commit = 2
query_cache_type = 0
query_cache_size = 0
max_connections = 150
```

### OpCache
Edit `/etc/php/8.3/cli/conf.d/10-opcache.ini`:
```ini
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=10000
opcache.revalidate_freq=60
opcache.fast_shutdown=1
```

## 15. Troubleshooting

| Issue | Solution |
|-------|----------|
| 502 Bad Gateway | Check PHP-FPM is running: `sudo systemctl restart php8.3-fpm` |
| 403 Forbidden | Check file permissions: `sudo chown -R www-data:www-data /var/www/euromart-egypt` |
| White screen | Check Laravel log: `tail -f storage/logs/laravel.log` |
| Slow page load | Clear cache: `php artisan cache:clear` then `php artisan optimize` |
| Email not sending | Check MAIL_ settings in .env and SMTP credentials |
| Queue not processing | Check supervisor: `sudo supervisorctl status` |
| Storage links broken | Re-link: `php artisan storage:link` |

## 16. Rollback

```bash
# Revert to previous deployment
cd /var/www/euromart-egypt
git revert HEAD --no-edit
git push origin main

# Restore database
gunzip < /backups/euromart/db_20240101_000000.sql.gz | mysql -u euromart -p euromart

# Restore storage
tar -xzf /backups/euromart/storage_20240101_000000.tar.gz -C /var/www/euromart-egypt
```

## Support

For deployment support, contact:
- Email: devops@euromartegypt.com
- Emergency: +201234567890
