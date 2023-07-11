FROM php:8.3.0alpha3-apache

# Install PDO MySQL driver (optional)
RUN docker-php-ext-install pdo_mysql

# Enable mod rewrite (optional)
RUN a2enmod rewrite