FROM php:8.2-apache

# Instala extensiones necesarias para Laravel
RUN apt-get update && apt-get install -y \
    zip unzip git curl libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring exif pcntl bcmath

# Habilita el módulo rewrite de Apache
RUN a2enmod rewrite

# Copia el archivo de configuración del virtualhost
COPY .docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf

# Define el directorio raíz para Apache
WORKDIR /var/www/html

# Copia el código Laravel al contenedor (lo hará docker-compose con un volumen)
