FROM php:7.2-apache

# OS setup
RUN export DEBIAN_FRONTEND=noninteractive \
    && apt-get update \
    && apt-get install -y \
        gnupg \
        git \
        zlib1g-dev \
        libmemcached-dev \
        libfreetype6 \
        libfontconfig1 \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libicu-dev \
        poppler-utils \
        unzip \
        jq \
    # Cleanup image
    && apt-get -yqq autoremove \
    && apt-get -yqq clean \
    && rm -rf /var/lib/apt/lists/* /var/cache/* /tmp/* /var/tmp/*

# Apache config
RUN a2enmod rewrite headers

# Install needed php extensions, configure and & enable them
RUN pecl install -o -f xdebug redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis \
    && docker-php-ext-install pdo_mysql zip gd mysqli gettext exif bcmath intl calendar \
    && docker-php-ext-configure intl \
    && docker-php-ext-configure gd --with-gd --with-freetype-dir --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-configure opcache --enable-opcache \
    && docker-php-ext-install opcache \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

COPY opcache.ini $PHP_INI_DIR/conf.d/
COPY apache-default.conf /etc/apache2/sites-available/000-default.conf
COPY php.ini $PHP_INI_DIR
RUN chown www-data:www-data $PHP_INI_DIR/php.ini

# Disable for now, mounted in docker-compose && see how to reduce the image size!
#COPY . /var/www

# Override base entrypoint file, to do some extra things on startup
COPY docker-php-entrypoint /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-php-entrypoint

WORKDIR /var/www
