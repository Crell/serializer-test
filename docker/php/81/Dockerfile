FROM php:8.1.0RC3-cli
WORKDIR /usr/src/myapp
CMD [ "vendor/bin/phpunit" ]

RUN pecl install xdebug
RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer
