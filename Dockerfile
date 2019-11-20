FROM php:7.3-cli

# Install packages

RUN apt-get clean \
    && apt-get update \
    && apt-get install -y git sqlite3

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/ \
    && ln -s /usr/local/bin/composer.phar /usr/local/bin/composer

RUN docker-php-ext-install pdo_mysql

WORKDIR /home/app
