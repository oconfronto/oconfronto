# Stage 1: Composer
FROM composer:1.10 as composer
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader

# Stage 2: Application
FROM orsolin/docker-php-5.3-apache
WORKDIR /var/www/html

# Copy only the vendor directory from the composer stage
COPY --from=composer /app/vendor /var/www/vendor

# Copy the application code
COPY src/ /var/www/html/
