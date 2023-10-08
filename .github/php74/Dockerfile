FROM ubuntu:22.04
ARG DEBIAN_FRONTEND=noninteractive

COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

RUN apt update \
    && apt install -y software-properties-common \
    && add-apt-repository ppa:ondrej/php \
    && apt update \
    && apt install -y \
        php7.4 \
        php7.4-dom \
        php7.4-mbstring \
        php7.4-xdebug \
        zip

ENV COMPOSER_HOME="/var/www/.composer"
ENV PATH="$PATH:$COMPOSER_HOME/vendor/bin"
ENV XDEBUG_MODE=coverage
