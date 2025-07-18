FROM php:8.1-apache

# Install necessary PHP extensions and curl for health checks
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

# Enable Apache modules
RUN a2enmod rewrite headers deflate expires

# Configure Apache for port 8080 (DigitalOcean requirement)
RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf
RUN sed -i 's/:80/:8080/' /etc/apache2/sites-available/000-default.conf

# Set ServerName to prevent warnings
RUN echo "ServerName pharmaxapp" >> /etc/apache2/apache2.conf

# Copy custom Apache configuration
COPY apache-config.conf /etc/apache2/conf-available/pharmaxapp.conf
RUN a2enconf pharmaxapp

# Copy all project files into the container
COPY . /var/www/html/

# Create necessary directories and set permissions
RUN mkdir -p /var/www/html/Images/PaymentSlips \
    && mkdir -p /var/www/html/Images/PrescriptionMessage \
    && mkdir -p /var/www/html/Images/PrescriptionOrders \
    && mkdir -p /var/www/html/Images/Profile_Pics \
    && mkdir -p /var/www/html/Images/product-icons \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 777 /var/www/html/Images

# Configure PHP settings
RUN echo "upload_max_filesize = 10M" >> /usr/local/etc/php/php.ini \
    && echo "post_max_size = 10M" >> /usr/local/etc/php/php.ini \
    && echo "max_execution_time = 300" >> /usr/local/etc/php/php.ini \
    && echo "memory_limit = 256M" >> /usr/local/etc/php/php.ini \
    && echo "session.gc_maxlifetime = 3600" >> /usr/local/etc/php/php.ini \
    && echo "session.cookie_lifetime = 3600" >> /usr/local/etc/php/php.ini \
    && echo "display_errors = Off" >> /usr/local/etc/php/php.ini \
    && echo "log_errors = On" >> /usr/local/etc/php/php.ini

# Set working directory
WORKDIR /var/www/html

# Expose port 8080 (required by DigitalOcean)
EXPOSE 8080

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=5s --retries=3 \
    CMD curl -f http://localhost:8080/health || exit 1

# Start Apache
CMD ["apache2-foreground"]
