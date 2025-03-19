FROM php:8.2-fpm

# Directorio de trabajo
WORKDIR /var/www/html

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-install \
    pdo_mysql \
    zip \
    intl \
    opcache \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copiar archivos de configuración de dependencias primero
COPY composer.json composer.lock ./

# Configurar permisos para Composer
RUN mkdir -p var/cache var/log && \
    chown -R www-data:www-data var

# Instalar dependencias
RUN composer install --prefer-dist --no-scripts --no-progress --no-interaction

# Copiar el código de la aplicación
COPY . .

# Definir variables de entorno de Symfony
ENV APP_ENV=dev
ENV APP_SECRET=24e17c47fffffff20e2d224d5204d81a
ENV DATABASE_URL="mysql://user:password@database:3306/app_db?serverVersion=8.0"

# Configurar permisos
RUN chown -R www-data:www-data var

# Iniciar servidor web de Symfony
EXPOSE 8000
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
