FROM dunglas/frankenphp:php8.4

RUN apt-get update && apt-get install -y \
    ffmpeg \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libwebp-dev \
    locales \
    zip \
    libzip-dev \
    jpegoptim \
    optipng \
    pngquant \
    gifsicle \
    vim \
    unzip \
    git \
    curl \
    libonig-dev \
    && rm -rf /var/lib/apt/lists/*

# Konfigurasi GD
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    --with-webp

# PHP extensions
RUN docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip

# Redis extension
RUN pecl channel-update pecl.php.net \
    && pecl install redis \
    && docker-php-ext-enable redis

WORKDIR /app

COPY . /app

COPY php.ini /usr/local/etc/php/php.ini

# COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

CMD ["php","artisan","octane:start","--server=frankenphp","--workers=8","--max-requests=1000","--host=0.0.0.0","--port=8000"]