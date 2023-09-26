FROM php:8.2-alpine AS base
# Install PHP extensions
RUN curl -sSLf -o /usr/local/bin/install-php-extensions https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions \
    && chmod +x /usr/local/bin/install-php-extensions \
    && install-php-extensions gd vips bz2 intl bcmath opcache calendar pdo_mysql pcntl zip redis swoole \
    && rm /usr/local/bin/install-php-extensions

FROM php:8.2-alpine AS production
WORKDIR /var/www/html
# Log to stderr
ENV LOG_CHANNEL=stderr

# Set timezone to UTC
ENV TZ=UTC
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Install PHP extensions
RUN curl -sSLf -o /usr/local/bin/install-php-extensions https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions \
    && chmod +x /usr/local/bin/install-php-extensions \
    && install-php-extensions gd vips bz2 intl bcmath opcache calendar pdo_mysql pcntl zip redis swoole \
    && rm /usr/local/bin/install-php-extensions

# Install PHP libs
# COPY --from=base /usr/local/lib/php/extensions/no-debug-non-zts-20220829 /usr/local/lib/php/extensions/no-debug-non-zts-20220829/
# RUN docker-php-ext-enable gd vips bz2 intl bcmath opcache calendar pdo_mysql pcntl zip redis swoole

# Serve with Octane
CMD [ "/usr/local/bin/php", "-d", "variables_order=EGPCS", "/var/www/html/artisan", "octane:start", "--server=swoole",  "--host=0.0.0.0", "--port=80"]

FROM production AS debug
# Development environment
ENV LOG_CHANNEL=stderr
ENV APP_ENV=local
ENV APP_DEBUG=true
# Composer
RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer
# Install Xdebug
RUN curl -sSLf -o /usr/local/bin/install-php-extensions https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions \
    && chmod +x /usr/local/bin/install-php-extensions \
    && install-php-extensions xdebug \
    && rm /usr/local/bin/install-php-extensions
# PHP config
COPY .dev/php-dev.ini /usr/local/etc/php/conf.d/99-tourbox.ini
# Serve with artisan serve
CMD [ "/usr/local/bin/php", "-d", "variables_order=EGPCS", "/var/www/html/artisan", "serve",  "--host=0.0.0.0", "--port=80"]