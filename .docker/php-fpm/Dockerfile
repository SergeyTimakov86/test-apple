FROM php:8.4-fpm-alpine

ENV APP_HOME=/var/www/html
ENV USERNAME=www-data
ARG HOST_UID=1000
ARG HOST_GID=1000

RUN apk add --no-cache curl git icu-dev zlib-dev libzip-dev
RUN apk add --update linux-headers

RUN docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd \
    && docker-php-ext-configure intl \
    && docker-php-ext-install \
      intl \
      opcache \
      pdo_mysql \
      zip \
    && rm -rf /tmp/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer self-update

RUN mkdir -p /home/$USERNAME && chown $USERNAME:$USERNAME /home/$USERNAME \
    && apk --no-cache add shadow \
    && usermod -o -u $HOST_UID $USERNAME -d /home/$USERNAME \
    && groupmod -o -g $HOST_GID $USERNAME \
    && chown -R ${USERNAME}:${USERNAME} $APP_HOME

WORKDIR $APP_HOME

USER ${USERNAME}

COPY --chown=${USERNAME}:${USERNAME} ./ $APP_HOME/

#RUN composer install --no-interaction --no-autoloader --no-scripts
#RUN composer dump-autoload --optimize


USER root