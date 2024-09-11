FROM php:8.2-apache

ARG TIMEZONE

RUN a2enmod rewrite


RUN apt-get update && \
    apt-get install \
    libzip-dev \
    wget \
    git \
    unzip \
    -y  nodejs npm

# Install PHP Extensions
RUN docker-php-ext-install zip pdo_mysql

# Set timezone
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone \
    && printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini \
    && "date"

WORKDIR /var/www/html
COPY scripts/src /var/www/html
RUN cp .env.example .env

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install
RUN php artisan key:generate
RUN chown -R www-data:www-data /var/www/html/
RUN chown  www-data:www-data .env

RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf && \
    sed -i 's/:80>/:8080>/' /etc/apache2/sites-available/000-default.conf

RUN usermod -u 1000 www-data

EXPOSE 8080

# Start Apache in foreground
CMD ["apache2-foreground"]
