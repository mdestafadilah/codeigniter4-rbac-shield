FROM dunglas/frankenphp:8.3-alpine

LABEL maintainer="mdestafadilah <github.com/mdestafadilah>"

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
    icu-dev \
    libxml2-dev \
    postgresql-dev \
    freetype \
    freetype-dev \
    libjpeg-turbo \
    libjpeg-turbo-dev \
    libwebp-dev \
    libxpm-dev \
    libpng \
    libpng-dev \
    curl \
    zip \
    unzip \
    zlib-dev \
    libzip-dev \
    gcc \
    musl-dev \
    linux-headers \
    gmp-dev \
    pcre-dev \
    icu-libs \
    ${PHPIZE_DEPS} \
    && docker-php-ext-configure gd --with-freetype --with-webp --with-jpeg \
    && docker-php-ext-configure zip \
    && docker-php-ext-install -j$(nproc) \
    pdo_pgsql \
    pgsql \
    zip \
    gmp \
    gd \
    bcmath \
    ctype \
    dom \
    intl \
    && apk del icu-dev libxml2-dev freetype-dev libjpeg-turbo-dev libwebp-dev libxpm-dev libpng-dev zlib-dev libzip-dev gcc musl-dev linux-headers gmp-dev pcre-dev ${PHPIZE_DEPS}

# Install FrankenPHP Caddy features
RUN install-php-extensions \
    @apcu \
    @opcache

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    COMPOSER_ALLOW_SUPERUSER=1

# Copy Caddyfile for FrankenPHP worker mode
COPY Caddyfile /etc/caddy/Caddyfile

# Copy PHP custom configuration
COPY docker_conf/php/php.ini /usr/local/etc/php/conf.d/docker-php-custom.ini

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . /var/www

# Set permissions
RUN chown -R www-data:www-data /var/www/public /var/www/writable && \
    chmod -R 0777 /var/www/writable

# Expose port (FrankenPHP default)
EXPOSE 80 443 8080

# Run FrankenPHP with Caddyfile (worker mode enabled)
CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]