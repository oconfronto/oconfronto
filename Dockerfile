# Stage 1: Composer
FROM composer:1.10 as composer
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader

# Stage 2: Application
FROM bitnami/php-fpm:7.4.33
WORKDIR /app

RUN echo "clear_env = no" >> /opt/bitnami/php/etc/php-fpm.conf

# Copy only the vendor directory from the composer stage
COPY --from=composer /app/vendor /vendor

# Copy the application code
COPY src/ /app

# Copy the .env file
COPY .env /app
