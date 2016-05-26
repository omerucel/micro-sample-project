FROM alpine:latest
MAINTAINER Ömer ÜCEL <omerucel@gmail.com>

RUN apk update && apk add \
    bash \
    nginx \
    php-dom \
    php-fpm \
    php-json \
    php-dom \
    php-xml \
    php-phar \
    php-openssl \
    php-pdo_mysql \
    php-mcrypt \
    php-opcache \
    php-ctype \
    php-curl && \
    rm -rf /var/cache/apk/*
RUN echo "PS1='[\u@\h:\w] $ '" > /root/.bashrc
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer
WORKDIR /data/project
CMD php-fpm -R && nginx
