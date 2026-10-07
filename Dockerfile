FROM php:8.2-fpm

# Installer les dépendances système et extensions nécessaires
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev

# Nettoyer le cache apt
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www

# Copier le code source
COPY . /var/www

# Installer les dépendances Laravel sans les packages de dev
RUN composer install --no-dev --optimize-autoloader

# Donner les bonnes permissions aux dossiers de stockage et de cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Exposer le port de l'application
EXPOSE 10000

# Lancer les optimisations Laravel puis le serveur au démarrage
CMD php artisan config:clear && \
    php artisan cache:clear && \
    php artisan serve --host=0.0.0.0 --port=10000