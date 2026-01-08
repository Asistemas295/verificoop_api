FROM php:8.2-apache

RUN apt-get update && apt-get install -y `
    git unzip libzip-dev libpng-dev libonig-dev libxml2-dev `
    && docker-php-ext-install pdo_mysql zip `
    && a2enmod rewrite headers `
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY apache-vhost.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress

COPY . .

RUN chown -R www-data:www-data /var/www/html `
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

ENTRYPOINT ["/usr/local/bin/start.sh"]
