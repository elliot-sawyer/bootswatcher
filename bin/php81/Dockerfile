FROM php:8.1-apache-buster

# Surpresses debconf complaints of trying to install apt packages interactively
# https://github.com/moby/moby/issues/4032#issuecomment-192327844

ARG DEBIAN_FRONTEND=noninteractive

# Update
RUN apt-get -y update --fix-missing && \
    apt-get upgrade -y && \
    apt-get --no-install-recommends install -y apt-utils && \
    rm -rf /var/lib/apt/lists/*


# Install useful tools and install important libaries
RUN apt-get -y update && \
    apt-get -y --no-install-recommends install nano wget \
    libtidy-dev \
dialog \
libsqlite3-dev \
libsqlite3-0 && \
    apt-get -y --no-install-recommends install default-mysql-client \
zlib1g-dev \
libzip-dev \
libicu-dev && \
    apt-get -y --no-install-recommends install --fix-missing apt-utils \
build-essential \
git \
curl \
libonig-dev && \
    apt-get install -y iputils-ping && \
    apt-get -y --no-install-recommends install --fix-missing libcurl4 \
libcurl4-openssl-dev \
zip \
openssl && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install xdebug
RUN pecl install xdebug-3.1.4 && \
    docker-php-ext-enable xdebug && \
    mkdir /var/log/xdebug

# Install redis
RUN pecl install redis && \
    docker-php-ext-enable redis

# Install imagick
RUN apt-get update && \
    apt-get -y --no-install-recommends install --fix-missing libmagickwand-dev && \
    rm -rf /var/lib/apt/lists/*

# Workarround until imagick is available in pecl with php8 support
# Imagick Commit to install
# https://github.com/Imagick/imagick
ARG IMAGICK_COMMIT="661405abe21d12003207bc8eb0963fafc2c02ee4"

RUN cd /usr/local/src && \
    git clone https://github.com/Imagick/imagick && \
    cd imagick && \
    git checkout ${IMAGICK_COMMIT} && \
    phpize && \
    ./configure && \
    make && \
    make install && \
    cd .. && \
    rm -rf imagick && \
    docker-php-ext-enable imagick

# Other PHP8 Extensions

RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install pdo_sqlite
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install curl
RUN docker-php-ext-install zip
RUN docker-php-ext-install -j$(nproc) intl
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install gettext
RUN docker-php-ext-install calendar
RUN docker-php-ext-install exif
RUN docker-php-ext-install tidy

# Install Freetype
RUN apt-get -y update && \
    apt-get --no-install-recommends install -y libfreetype6-dev \
libjpeg62-turbo-dev \
libpng-dev && \
    rm -rf /var/lib/apt/lists/* && \
    docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd

# Insure an SSL directory exists
RUN mkdir -p /etc/apache2/ssl


# Enable apache modules
RUN a2enmod ssl rewrite headers


# Enable SSL
RUN apt-get update && \
    apt-get install -y \
        zlib1g-dev && \
    mkdir -p /etc/apache2/ssl && \
    openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/apache2/ssl/server.key -out /etc/apache2/ssl/server.crt --batch && \
    printf "<VirtualHost *:443>\n\tDocumentRoot /var/www/html/\n\tServerName localhost\n\tSSLEngine on\n\tSSLCertificateFile /etc/apache2/ssl/server.crt\n\tSSLCertificateKeyFile /etc/apache2/ssl/server.key\n</VirtualHost>\n" > /etc/apache2/sites-available/ssl.conf && \
    a2ensite ssl && \
    service apache2 restart


#create aliases for common commands inside the docker container
RUN echo "#!/bin/bash\nvendor/bin/sake dev/build flush=" > /usr/local/bin/vbsdb
RUN echo "#!/bin/bash\nvendor/bin/sake dev/tasks/\$1 flush=" > /usr/local/bin/vbsdt
RUN echo "#!/bin/bash\ncomposer update" > /usr/local/bin/cu
RUN echo "#!/bin/bash\ncomposer install" > /usr/local/bin/ci
RUN echo "#!/bin/bash\ncomposer require \$1" > /usr/local/bin/cr
RUN echo "#!/bin/bash\nchown -R www-data:www-data public/assets/ public/assets/.htaccess public/assets/.protected" > /usr/local/bin/co
RUN chmod +x /usr/local/bin/vbsdb \
    /usr/local/bin/vbsdt \
    /usr/local/bin/cu \
    /usr/local/bin/ci \
    /usr/local/bin/cr \
    /usr/local/bin/co

RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini
RUN sed -i 's/post_max_size = 8M/post_max_size = 100M/gi' /usr/local/etc/php/php.ini
RUN sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 100M/gi' /usr/local/etc/php/php.ini
RUN sed -i 's/memory_limit = 128M/memory_limit = 2G/gi' /usr/local/etc/php/php.ini

RUN echo "[opcache]" > /usr/local/etc/php/conf.d/opcache.ini
RUN echo "opcache.enable_cli=1" >> /usr/local/etc/php/conf.d/opcache.ini
RUN echo "opcache.jit_buffer_size=100M" >> /usr/local/etc/php/conf.d/opcache.ini
RUN echo "opcache.jit=tracing" >> /usr/local/etc/php/conf.d/opcache.ini

RUN echo "session.save_handler = redis" >> /usr/local/etc/php/conf.d/docker-php-ext-redis.ini
RUN echo "session.save_path = tcp://redis:6379" >> /usr/local/etc/php/conf.d/docker-php-ext-redis.ini

RUN apt-get --no-install-recommends install -y cron
RUN sed -i 's/^exec /service cron start\n\nexec /' /usr/local/bin/apache2-foreground
# Cleanup

RUN rm -rf /usr/src/*

