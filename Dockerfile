FROM php:8.2-apache

# Install system dependencies required for PHP extensions
RUN apt-get update && apt-get install -y \
    libssl-dev \
    pkg-config \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Install and enable required PHP extensions
# 1. MySQL (pdo_mysql)
RUN docker-php-ext-install pdo pdo_mysql

# 2. Redis & MongoDB via PECL
RUN pecl install redis mongodb \
    && docker-php-ext-enable redis mongodb

# Enable Apache mod_rewrite for nice URLs if needed
RUN a2enmod rewrite

# Copy all project files into the Apache web root
COPY . /var/www/html/

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html/ \
    && chmod -R 755 /var/www/html/

# Expose port 80
EXPOSE 80
