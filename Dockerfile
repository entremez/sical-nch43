FROM php:8.2-apache

ENV DEBIAN_FRONTEND=noninteractive

# Paquetes para Laravel + compilación PECL + ODBC MS + GD
RUN apt-get update && apt-get install -y \
    zip unzip git curl libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev \
    gnupg2 apt-transport-https ca-certificates unixodbc-dev $PHPIZE_DEPS \
 \
 # Extensiones PHP comunes
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install pdo pdo_mysql zip mbstring exif pcntl bcmath gd \
 \
 # Repositorio Microsoft y SQLSRV (igual que antes)
 && curl -fsSL https://packages.microsoft.com/keys/microsoft.asc | gpg --dearmor -o /usr/share/keyrings/microsoft-prod.gpg \
 && echo "deb [arch=amd64 signed-by=/usr/share/keyrings/microsoft-prod.gpg] https://packages.microsoft.com/debian/12/prod bookworm main" \
    > /etc/apt/sources.list.d/microsoft-prod.list \
 && apt-get update \
 && ACCEPT_EULA=Y apt-get install -y msodbcsql18 \
 && pecl install sqlsrv pdo_sqlsrv \
 && docker-php-ext-enable sqlsrv pdo_sqlsrv \
 \
 # Limpieza
 && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite
COPY .docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf
WORKDIR /var/www/html

