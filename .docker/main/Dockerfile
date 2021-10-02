FROM php:8-alpine
WORKDIR /app

RUN php --version

COPY . /app

RUN /app/.docker/main/install-composer.sh
RUN composer install

CMD ["tail", "-f", "/dev/null"]