FROM php:7.3-apache

RUN apt-get update && apt-get upgrade -y && apt-get install -y vim zlib1g-dev libpng-dev libicu-dev g++ libxml2-dev libldb-dev libldap2-dev libzip-dev

RUN docker-php-ext-install pdo_mysql mysqli zip gd intl xml ldap

COPY . /var/www/html/
COPY deploy/apache2.conf /etc/apache2/sites-enabled/000-default.conf
COPY deploy/entrypoint.sh ./entrypoint.sh

RUN chown -R www-data:www-data /var/www/
RUN mkdir /var/log/application
RUN chmod -R 777 /var/log/application
RUN chown -R www-data:www-data /var/log/application
RUN chmod +x ./entrypoint.sh

RUN a2enmod rewrite

EXPOSE 80

ENTRYPOINT ["./entrypoint.sh"]
