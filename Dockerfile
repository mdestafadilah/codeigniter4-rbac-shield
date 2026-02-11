FROM php:8.3-fpm-alpine

LABEL maintainer="mdestafadilah <github.com/mdestafadilah>"

RUN apk update && apk add --no-cache icu-dev libxml2-dev \
    && docker-php-ext-install ctype dom intl \
    && apk del icu-dev libxml2-dev \
    && docker-php-ext-enable ctype dom intl

RUN curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer && \
    COMPOSER_ALLOW_SUPERUSER=1 && \
    PATH="./vendor/bin:$PATH"


RUN apk add --no-cache \
    nginx supervisor \
    ffmpeg postgresql-dev freetype freetype-dev libjpeg-turbo libjpeg-turbo-dev libwebp-dev libxpm-dev libpng libpng-dev curl zip unzip zip zlib-dev libzip-dev gcc musl-dev linux-headers gmp-dev pcre-dev ${PHPIZE_DEPS} && \
    docker-php-ext-configure gd --with-freetype --with-webp --with-jpeg && \
    docker-php-ext-configure zip && \
    NPROC=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) && \
    docker-php-ext-install -j$(nproc) pdo_pgsql zip gmp gd bcmath pgsql

COPY docker_conf/php/php.ini /usr/local/etc/php/conf.d/docker-php-custom.ini
COPY docker_conf/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker_conf/nginx/default.conf /etc/nginx/http.d/default.conf
COPY docker_conf/supervisor/supervisord.ini /etc/supervisor.d/supervisord.ini

RUN mkdir -p /etc/supervisor.d/
RUN touch /run/supervisord.sock
RUN mkdir -p /run/nginx/
RUN touch /run/nginx/nginx.pid

RUN ln -sf /dev/stdout /var/log/nginx/access.log
RUN ln -sf /dev/stderr /var/log/nginx/error.log

WORKDIR /var/www

COPY . /var/www

RUN chown -R www-data:www-data /var/www/public /var/www/writable && \
    chmod -R 0777 /var/www/writable

EXPOSE 80

CMD ["supervisord", "-c", "/etc/supervisor.d/supervisord.ini"]
