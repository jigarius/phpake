FROM php:8-alpine
WORKDIR /app

RUN php --version

COPY . /app

RUN /app/.docker/main/install-composer.sh
RUN composer install
ENV PATH="/app/bin:${PATH}:/app/vendor/bin"

CMD ["tail", "-f", "/dev/null"]