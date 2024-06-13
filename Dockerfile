FROM php:8.3-cli

ARG USER_ID
ARG GROUP_ID

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmemcached-dev \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    librdkafka-dev \
    libpq-dev \
    openssh-server \
    zip \
    unzip \
    supervisor \
    nano \
    cron \
    && pecl install swoole \
    && docker-php-ext-enable swoole \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-install pcntl
RUN docker-php-ext-install mysqli pdo_mysql

# Create a user/group with UID 1000 GID 1000
RUN addgroup --gid ${GROUP_ID} laravel \
    && adduser --uid ${USER_ID} --ingroup laravel --shell /bin/sh --disabled-password laravel \
    && echo 'laravel:password1234' | chpasswd

# Add laravel user to sudoers with NOPASSWD option
RUN mkdir -p /etc/sudoers.d \
    && echo 'laravel ALL=(ALL) NOPASSWD:ALL' > /etc/sudoers.d/laravel \
    && chmod 0440 /etc/sudoers.d/laravel

WORKDIR /var/www

EXPOSE 9501
USER laravel

CMD ["supervisord", "-c", "/etc/supervisor.conf"]

