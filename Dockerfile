# ============================================
# Dockerfile - The Blueprint for Your App
# ============================================
# This tells Docker HOW to build your PHP environment
# Each line is a step, like a recipe

# --- STEP 1: Choose Base Image ---
# "Start with PHP 8.3 that has CLI (command line)"
# Think of this as installing PHP on a fresh computer
FROM php:8.3-cli

# --- STEP 2: Install System Dependencies ---
# PHP needs these tools to work with MySQL, ZIP files, etc.
# This is like installing drivers on Windows
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# --- STEP 3: Install Node.js ---
# We need Node for Vite (frontend assets)
# This downloads Node 20 LTS
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm yarn

# --- STEP 4: Install Composer ---
# Composer is PHP's package manager (like npm for PHP)
# We download it and put it in /usr/local/bin so it's always available
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# --- STEP 5: Set Working Directory ---
# All future commands run inside /var/www
# This is like cd /var/www, but permanent
WORKDIR /var/www

# --- STEP 6: Copy Application Files ---
# Copy composer files first (for caching)
COPY composer.json composer.lock ./

# Install PHP dependencies
# --no-dev = skip test packages (smaller image)
# --optimize-autoloader = faster loading
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy package files
COPY package.json yarn.lock ./

RUN yarn install --frozen-lockfile

# Copy everything else (your actual code)
COPY . .

# --- STEP 7: Setup Permissions ---
# Laravel needs to write to storage/ and bootstrap/cache/
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

# --- STEP 8: Expose Port ---
# Tell Docker this container listens on port 8000
EXPOSE 8000

# --- STEP 9: Entry Script ---
# Copy our startup script
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# --- STEP 10: Start Command ---
# When container starts, run this
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
