FROM php:apache
RUN DEBIAN_FRONTEND=noninteractive

WORKDIR /var/www/html
COPY web .

RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo_pgsql
# RUN apt-get install -y \
#     libmagickwand-dev --no-install-recommends \
#     && rm -rf /var/lib/apt/lists/* \
#     && pecl install imagick \
#     && docker-php-ext-enable imagick

ENV PORT=8000
EXPOSE ${PORT}

RUN sed -i 's/Listen 80/Listen ${PORT}/' /etc/apache2/ports.conf

RUN chmod -R 775 /var/www/html/images
RUN chown -R 775 /tmp
RUN chown -R www-data:www-data /var/www/html/images
