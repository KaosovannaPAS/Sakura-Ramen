FROM php:8.2-apache

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    libssl-dev \
    pkg-config

# Installer les extensions PHP
RUN docker-php-ext-install pdo_mysql gd

# Installer l'extension MongoDB
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Activer le mod_rewrite d'Apache
RUN a2enmod rewrite

# Configurer le DocumentRoot sur /api (Entry point)
ENV APACHE_DOCUMENT_ROOT /var/www/html/api
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html

EXPOSE 80
