FROM php:8.0-alpine

WORKDIR /app
EXPOSE 9000

COPY . /app

ENV PATH="/app/bin:${PATH}:/app/vendor/bin"
ENV PHP_INI_PATH="$PHP_INI_DIR/conf.d/php.ini"

RUN apk --update add make git g++ autoconf

RUN cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_PATH" \
    && pear config-set php_ini "$PHP_INI_PATH" \
    && pecl install xdebug

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

CMD ["tail", "-f", "/dev/null"]
