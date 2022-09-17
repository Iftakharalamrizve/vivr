FROM php:7.4.14-fpm

RUN apt update && apt install -y zlib1g-dev libpng-dev && rm -rf /var/lib/apt/lists/*


RUN apt-get update && apt-get install -y \
      git \
      libicu-dev \
      zlib1g-dev \
      libpq-dev \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-install -j$(nproc) intl \
    && docker-php-ext-install -j$(nproc) pgsql \
    && docker-php-ext-install -j$(nproc) pdo_pgsql \
    && docker-php-ext-install -j$(nproc) mysqli \
    && docker-php-ext-install -j$(nproc) gd

WORKDIR /var/www/html


EXPOSE 9000
CMD ["php-fpm"]