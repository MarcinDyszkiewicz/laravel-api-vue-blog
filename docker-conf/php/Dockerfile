FROM php:7.3-fpm

RUN apt-get update && apt-get install -y zlib1g-dev -y zip libzip-dev libicu-dev g++ libcurl4-openssl-dev libpng-dev libjpeg-dev libmcrypt-dev less vim libpq-dev libxml2-dev && apt-get clean -y
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql
RUN docker-php-ext-install pdo pdo_pgsql pgsql intl curl gd zip mbstring tokenizer xml sockets bcmath soap pcntl

#mcrypt

RUN pecl install xdebug

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer config --global github-oauth.github.com a4eb70aa61c96075cd5fb5a8e8076c7513f7557d
