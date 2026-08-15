# Etapa PHP
FROM php:8.3-cli-alpine AS php-base

RUN apk add --no-cache \
    bash \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Etapa Node
FROM node:20-alpine AS node-base

WORKDIR /app
