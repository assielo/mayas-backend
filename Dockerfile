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

# 1. Copier d'abord les fichiers de dépendances
COPY composer.json composer.lock /var/www/

# 2. Créer le dossier database et le fichier sqlite vide
RUN mkdir -p /var/www/database && touch /var/www/database/database.sqlite

# Configurer les variables d'environnement pour SQLite pendant le build
ENV DB_CONNECTION=sqlite
ENV DB_DATABASE=/var/www/database/database.sqlite

# 3. Installer les dépendances Composer EN PREMIER pour créer le dossier vendor/
RUN composer install --no-dev --optimize-autoloader

# 4. Copier le reste du code source
COPY . /var/www

# 5. Maintenant que vendor/ existe, on peut lancer les migrations en toute sécurité
RUN php artisan migrate --force

# Donner les bonnes permissions aux dossiers de stockage, cache et database
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/database \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache /var/www/database

# Exposer le port de l'application
EXPOSE 10000

# Commande de démarrage : Seeder Admin + Nettoyage cache + Lancement du serveur PHP
CMD php artisan db:seed --class=AdminUserSeeder --force && \
    php artisan config:clear && \
    php artisan cache:clear && \
    php artisan serve --host=0.0.0.0 --port=10000