FROM php:7.3-zts

RUN apt-get update && apt-get install -y --no-install-recommends \
  vim \
  libzip-dev \
  unzip \
  procps \
  inotify-tools \
  mc \
  zlibc \
  zlib1g \
  zlib1g-dev \
  libfreetype6-dev \
  libssl-dev \
  libjpeg62-turbo-dev \
  libmemcached-dev \
  libmagickwand-dev \
  libpq-dev \
  libmcrypt-dev \
  libpng-dev \
  libicu-dev

# Install PHP Extensions
RUN docker-php-ext-install zip \
  && docker-php-ext-install opcache \
  && docker-php-ext-enable opcache \
  && docker-php-ext-install -j$(nproc) iconv sockets pdo_mysql mysqli pcntl intl bcmath \
  && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
  && docker-php-ext-configure mbstring \
  && docker-php-ext-install mbstring \
  && docker-php-ext-install pdo pdo_pgsql \
  && docker-php-ext-install -j$(nproc) gd \
  && docker-php-ext-install -j$(nproc) sysvsem

# isntall imagick
RUN pecl install imagick \
    && docker-php-ext-enable imagick

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
  && php -r "if (hash_file('SHA384', 'composer-setup.php') === rtrim(file_get_contents('https://composer.github.io/installer.sig'))) { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
  && php composer-setup.php \
  && php -r "unlink('composer-setup.php');" \
  && mv composer.phar /usr/local/bin/composer

# Download RoadRunner
ENV RR_VERSION 1.5.0
RUN mkdir /tmp/rr \
  && cd /tmp/rr \
  && echo "{\"require\":{\"spiral/roadrunner\":\"${RR_VERSION}\"}}" >> composer.json \
  && composer install \
  && vendor/bin/rr get-binary -l /usr/local/bin \
  && rm -rf /tmp/rr

WORKDIR /var/www

ENTRYPOINT ["/usr/local/bin/rr", "serve", "-d", "-c", "/etc/roadrunner/.rr.yaml"]
