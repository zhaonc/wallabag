FROM php:8.0.11-buster
# FROM php:7.4.15-buster
# see https://hub.docker.com/_/php/

RUN apt-get update && \
    apt-get install -y curl \
    acl \
    zsh \
    sudo \
    git \
    unzip \
    net-tools \
    wget \
    vim \
    zlib1g-dev \
    libicu-dev \
    g++ \
    libjpeg-dev \
    libpng-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    openssl \
    libfaketime \
    libtidy-dev

ENV FAKETIME_NO_CACHE=1
ENV LD_PRELOAD=/usr/lib/x86_64-linux-gnu/faketime/libfaketime.so.1

RUN ln -s /usr/local/bin/php /usr/local/bin/php8.0

ARG APCU_VERSION=5.1.20
# see https://pecl.php.net/package/APCu

ARG XDEBUG_VERSION=3.0.4
# see https://xdebug.org/download

RUN pecl install apcu-${APCU_VERSION} xdebug-${XDEBUG_VERSION} \
    && pecl clear-cache \
    && docker-php-ext-enable apcu opcache xdebug

RUN docker-php-ext-configure pdo_mysql \
    && docker-php-ext-install -j$(nproc) pdo_mysql \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) intl \
    && docker-php-ext-configure zip \
    && docker-php-ext-install -j$(nproc) zip \
    && docker-php-ext-configure exif \
    && docker-php-ext-install -j$(nproc) exif \
    && docker-php-ext-configure gd \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pcntl \
    && docker-php-ext-install -j$(nproc) tidy

COPY .docker/config/xdebug.ini /xdebug.ini
RUN cat /xdebug.ini >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && rm /xdebug.ini

# Blackfire
#RUN version=$(php -r "echo PHP_MAJOR_VERSION.PHP_MINOR_VERSION;") \
#    && curl -A "Docker" -o /tmp/blackfire-probe.tar.gz -D - -L -s https://blackfire.io/api/v1/releases/probe/php/linux/amd64/$version \
#    && mkdir -p /tmp/blackfire \
#    && tar zxpf /tmp/blackfire-probe.tar.gz -C /tmp/blackfire \
#    && mv /tmp/blackfire/blackfire-*.so $(php -r "echo ini_get ('extension_dir');")/blackfire.so \
#    && printf "extension=blackfire.so\nblackfire.agent_socket=tcp://blackfire:8707\n" > $PHP_INI_DIR/conf.d/blackfire.ini \
#    && rm -rf /tmp/blackfire /tmp/blackfire-probe.tar.gz

RUN wget -O composer.phar https://getcomposer.org/composer-2.phar \
    && mv composer.phar /usr/local/bin/composer \
    && chmod +x /usr/local/bin/composer \
    && /usr/local/bin/composer self-update

RUN mkdir /home/api && chown -R 1000:1000 /home/api && chmod -R 775 /home/api
RUN mkdir /home/api/.symfony && chown -R 1000:1000 /home/api/.symfony && chmod -R 775 /home/api/.symfony
RUN echo 'api:x:1000:1000:api:/home/api:/bin/bash' >> /etc/passwd

RUN wget https://get.symfony.com/cli/installer -O - | bash \
    && mv /root/.symfony/bin/symfony /usr/local/bin/symfony \
    && echo "/root/.symfony/bin/symfony moved to /usr/local/bin/symfony"

ENV COMPOSER_MEMORY_LIMIT=-1

RUN echo 'memory_limit = 1024M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini
RUN echo 'upload_max_filesize = 8M' > /usr/local/etc/php/conf.d/sol-upload.ini

RUN echo 'alias c="/app/bin/console"' >> /home/api/.bashrc
RUN echo 'alias p="/app/bin/phpunit"' >> /home/api/.bashrc

# Commented out as it seems to crash on local env
# RUN echo 'apc.enable_cli=1' >> /usr/local/etc/php/conf.d/docker-php-ext-apcu.ini

CMD [".docker/scripts/run.sh"]
