FROM webdevops/php-apache:latest

RUN apt-get update -y
RUN apt-get install mysql-client -y

WORKDIR /app
ADD . .
RUN composer install
RUN mkdir -p /app/storage
RUN chmod -R 777 /app/storage/logs
RUN chmod -R 777  bootstrap/cache
RUN a2enmod rewrite
RUN chmod -R 777 storage/framework/sessions
RUN chmod -R 777 storage/framework/views
RUN php artisan cache:clear
RUN composer dump-autoload
RUN php artisan cache:clear

