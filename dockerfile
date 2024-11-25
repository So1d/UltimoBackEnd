# Esse aqui serve pra configurar o driver mysql no php
FROM php:8.1-apache

# Instala dependências e habilita extensões necessárias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd mysqli \
    && docker-php-ext-enable mysqli

# Copia os arquivos do seu projeto para dentro do container
COPY . /var/www/html/

# Dá as permissões necessárias
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expõe a porta padrão do Apache
EXPOSE 80
