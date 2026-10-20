FROM php:8.3-apache

# - pdo_mysql: the MySQL driver used by km_conn.php
# - php.ini-production: errors are logged (docker compose logs), never displayed
# - expose_php off / ServerTokens Prod: don't advertise versions in headers
# - AllowOverride All: let Apache read the project's .htaccess files
RUN docker-php-ext-install pdo_mysql \
    && cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && echo "expose_php = Off" > "$PHP_INI_DIR/conf.d/security.ini" \
    && printf 'ServerTokens Prod\nServerSignature Off\n<Directory /var/www/html>\n    AllowOverride All\n</Directory>\n' \
        > /etc/apache2/conf-available/web-system.conf \
    && a2enconf web-system

COPY --chown=www-data:www-data . /var/www/html/
