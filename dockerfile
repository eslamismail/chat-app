FROM webdevops/php-apache:latest

WORKDIR /var/www
ADD . /var/www
RUN composer install
EXPOSE 6001

CMD ["php", "artisan", "websockets:serve"]
