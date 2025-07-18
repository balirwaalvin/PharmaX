FROM php:8.1-apache

# Install necessary PHP extensions
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl \
    && docker-php-ext-install mysqli \
    && docker-php-ext-enable mysqli

# Enable Apache mod_rewrite (if needed for pretty URLs)
RUN a2enmod rewrite

# Copy all project files into the container
COPY . /var/www/html/

# Fix permissions so Apache can serve everything
RUN chown -R www-data:www-data /var/www/html

# Set working directory
WORKDIR /var/www/html
