# Installation and Deployment Guide

## Quick Start (Local Development)

### Requirements
- PHP 7.0+ with PDO SQLite extension
- SQLite 3
- Apache with mod_rewrite or Nginx
- Modern web browser

### Installation Steps

1. **Clone or Download the Repository**
   ```bash
   git clone https://github.com/Sapvit/mcrm.git
   cd mcrm
   ```

2. **Configure Database Settings**
   - Open `config.php`
   - Update the following constant if needed:
     ```php
     define('DB_PATH', __DIR__ . '/data/mcrm.sqlite');
     ```

3. **Set Up File Permissions**
   ```bash
   chmod 777 uploads/ data/
   ```

4. **Initialize Database**
   - Navigate to `http://localhost/mcrm/setup.php` in your browser
   - You should see: "Database setup completed successfully!"
   - This creates the SQLite database file and all required tables

5. **Access the Application**
   - Go to `http://localhost/mcrm/`
   - Register a new user account
   - Start using the application!

## Apache Configuration

### .htaccess (Optional for Clean URLs)

If you want to add clean URLs later, you can create a `.htaccess` file:

```apache
RewriteEngine On
RewriteBase /mcrm/

# Prevent access to setup.php after initial setup
<Files "setup.php">
    Order Allow,Deny
    Deny from all
</Files>

# Protect sensitive files
<FilesMatch "^(config\.php|\.gitignore)$">
    Order Allow,Deny
    Deny from all
</FilesMatch>
```

## Nginx Configuration

```nginx
server {
    listen 80;
    server_name localhost;
    root /var/www/html/mcrm;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Protect sensitive files
    location ~ /(config\.php|setup\.php|\.git) {
        deny all;
        return 404;
    }

    # Secure uploads directory
    location ~* ^/uploads/.*\.(php|php3|php4|php5|phtml)$ {
        deny all;
        return 404;
    }
}
```

## Production Deployment

### Security Checklist

1. **Set Database Path and Permissions**
   - Use environment variables instead of hardcoded values
   - Place the SQLite file outside the web root if possible
   - Restrict file permissions to the web server user only

2. **Disable setup.php After Initial Setup**
   - Delete or rename `setup.php` after database initialization
   - Or protect it with `.htaccess` as shown above

3. **Secure File Uploads**
   - Move uploads directory outside web root if possible
   - Ensure PHP files cannot be executed from uploads directory
   - Implement file scanning if dealing with sensitive data

4. **Enable HTTPS**
   - Install SSL certificate (Let's Encrypt is free)
   - Force HTTPS redirects
   - Set secure cookie flags in `config.php`:
     ```php
     session_set_cookie_params([
         'secure' => true,
         'httponly' => true,
         'samesite' => 'Strict'
     ]);
     ```

5. **Set Proper File Permissions**
   ```bash
   # Application files
   find . -type f -exec chmod 644 {} \;
   find . -type d -exec chmod 755 {} \;
   
   # Uploads directory
   chmod 755 uploads/
   ```

6. **Configure PHP Settings**
   Update `php.ini`:
   ```ini
   upload_max_filesize = 10M
   post_max_size = 11M
   max_execution_time = 30
   display_errors = Off
   log_errors = On
   ```

7. **Enable Error Logging**
   In `config.php`, add after database connection:
   ```php
   if ($_SERVER['SERVER_NAME'] !== 'localhost') {
       ini_set('display_errors', 0);
       ini_set('log_errors', 1);
       error_log('/path/to/logs/php_errors.log');
   }
   ```

8. **Regular Backups**
   - Backup database regularly
   - Backup uploads directory
   - Store backups securely off-site

### Environment Variables (Recommended for Production)

Create a `.env` file (not committed to git):
```
DB_PATH=/var/lib/mcrm/mcrm.sqlite
```

Update `config.php` to use environment variables:
```php
<?php
// Load environment variables
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            putenv(trim($key) . '=' . trim($value));
        }
    }
}

define('DB_PATH', getenv('DB_PATH') ?: __DIR__ . '/data/mcrm.sqlite');
```

## Maintenance

### Database Maintenance

**Backup Database:**
```bash
cp data/mcrm.sqlite backup_$(date +%Y%m%d).sqlite
```

**Restore Database:**
```bash
cp backup_20240212.sqlite data/mcrm.sqlite
```

### Clean Old Files

Create a cleanup script for old uploads if needed:
```php
// cleanup.php - Run periodically via cron
<?php
require_once 'config.php';

// Delete files older than 365 days that are not in database
$files = glob('uploads/*');
foreach ($files as $file) {
    if (is_file($file) && time() - filemtime($file) > 365 * 24 * 3600) {
        $basename = basename($file);
        $stmt = $pdo->prepare("SELECT id FROM files WHERE filepath = ?");
        $stmt->execute(['uploads/' . $basename]);
        if (!$stmt->fetch()) {
            unlink($file);
            echo "Deleted orphaned file: $file\n";
        }
    }
}
```

## Troubleshooting

### Common Issues

1. **"Database connection failed"**
   - Verify `DB_PATH` in `config.php`
   - Ensure the `data` directory is writable by the web server
   - Check that the SQLite file exists after running `setup.php`

2. **"Failed to upload file"**
   - Check uploads directory permissions: `chmod 777 uploads/`
   - Verify PHP upload settings in `php.ini`
   - Check disk space

3. **"Forbidden" or 403 errors**
   - Check file permissions
   - Verify Apache/Nginx configuration
   - Check .htaccess if using Apache

4. **Session issues**
   - Ensure session directory is writable
   - Check `session.save_path` in `php.ini`

5. **Blank pages**
   - Enable error display temporarily in `config.php`:
     ```php
     ini_set('display_errors', 1);
     error_reporting(E_ALL);
     ```
   - Check PHP error logs

## Support

For issues or questions:
1. Check TESTING.md for comprehensive testing procedures
2. Review STRUCTURE.md for application architecture
3. Check PHP error logs
4. Verify all requirements are met

## License

This application is provided as-is for educational and personal use.
