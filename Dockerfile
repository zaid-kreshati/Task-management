FROM composer:2.3.5 AS builder

WORKDIR /app

COPY . /app

RUN composer update

RUN mv /app/public /app/html

############################

FROM php:8.2

ENV TZ="Etc/GMT-3"

WORKDIR /var/www/

COPY --from=builder /app /var/www/

COPY ./deployment-files/apache2.conf /etc/apache2/apache2.conf

RUN a2enmod rewrite

RUN ln -s /var/www/html /var/www/public
RUN chown www-data:www-data -R /var/www

EXPOSE 80

CMD ["/usr/sbin/apachectl","-DFOREGROUND"]

