# Stage 1: Composer
FROM composer:1.10 as composer
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader

# Stage 2: Application
FROM bitnami/php-fpm:5.6.40-prod
WORKDIR /app

# Copy only the vendor directory from the composer stage
COPY --from=composer /app/vendor /vendor

# Copy the application code
COPY src/ /app
