# Tumia PHP 8.2 kama ulivyotaka
FROM php:8.2-apache

# Install extensions zinazohitajika na Laravel
RUN apt-get update && apt-get install -y libpng-dev libzip-dev zip unzip
RUN docker-php-ext-install pdo_mysql gd zip

# Nakili msimbo wa mradi wako
COPY . /var/www/html

# Weka ruhusa sahihi
RUN chown -R www-data:www-data /var/www/html

# Weka Document Root ya Apache kwenye public ya Laravel
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

EXPOSE 80
