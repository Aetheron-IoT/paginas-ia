FROM php:8.2-apache

# Instalar dependencias necesarias para curl
RUN apt-get update && apt-get install -y \
    libcurl4-openssl-dev \
    pkg-config \
    && docker-php-ext-install curl \
    && apt-get clean

# Activar mod_rewrite (opcional pero recomendable)
RUN a2enmod rewrite

# Copiar proyecto al servidor Apache
COPY . /var/www/html/

# Permisos correctos
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80