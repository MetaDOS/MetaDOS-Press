FROM php:7.2-apache as press_metados
COPY ./ /var/www/html/
EXPOSE 80