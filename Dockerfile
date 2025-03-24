FROM dunglas/frankenphp

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    vim \
    bash \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN install-php-extensions \
    bcmath \
    ctype \
    curl \
    dom \
    fileinfo \
    json \
    mbstring \
    openssl \
    pcntl \
    tokenizer \
    xml \
    zip


WORKDIR /app

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY ./backend /app

EXPOSE 80

CMD ["frankenphp", "php-server", "-r", "/app"]