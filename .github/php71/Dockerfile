FROM ubuntu:22.04
ARG DEBIAN_FRONTEND=noninteractive

COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

RUN apt update \
    && apt install -y software-properties-common \
    && add-apt-repository ppa:ondrej/php \
    && apt update \
    && apt install -y \
        php7.1 \
        php7.1-dom \
        php7.1-mbstring \
        php7.1-xdebug \
        zip

ENV COMPOSER_HOME="/var/www/.composer"
ENV PATH="$PATH:$COMPOSER_HOME/vendor/bin"
