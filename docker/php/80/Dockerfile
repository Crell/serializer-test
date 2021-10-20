FROM php:8.0.11-cli
WORKDIR /usr/src/myapp
CMD [ "vendor/bin/phpunit" ]

RUN pecl install xdebug
RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer
