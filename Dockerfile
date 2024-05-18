# Dockerfile

# Use the official PHP image with Apache
FROM php:8.0-apache

# Enable mod_rewrite for Apache
RUN a2enmod rewrite

# Install dependencies
RUN apt-get update && apt-get install -y libsqlite3-dev zip unzip cron

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_sqlite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory
WORKDIR /var/www/html

# Copy the application files
COPY . .

# Copy the custom Apache configuration
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Enable the custom Apache configuration
RUN a2dissite 000-default.conf && a2ensite 000-default.conf

# Install PHP dependencies
RUN composer install

# Set up cron job
COPY crontab.txt /etc/cron.d/email-cron
RUN chmod 0644 /etc/cron.d/email-cron
RUN crontab /etc/cron.d/email-cron
RUN touch /var/log/cron.log

# Set proper permissions for the Apache server
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose port 80
EXPOSE 80

# Start Apache and cron
CMD cron && apache2-foreground