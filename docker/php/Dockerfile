FROM php:7.4-fpm

RUN apt-get update
RUN apt-get install -y bash wget
RUN apt-get install -y libzip-dev

RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN docker-php-ext-install zip

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer

RUN wget https://get.symfony.com/cli/installer -O - | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony

EXPOSE 80

WORKDIR /var/www