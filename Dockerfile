# Utiliser l'image PHP 8.2-FPM comme base
FROM php:8.2-fpm

# Installer les dépendances nécessaires et les extensions PDO et MySQL
RUN apt-get update && apt-get install -y \
    curl \
    git \
    unzip \
    libmariadb-dev-compat \
    && docker-php-ext-install pdo pdo_mysql \
    && docker-php-ext-enable pdo_mysql \
    && rm -rf /var/lib/apt/lists/*


# Vérifier si l'extension pdo_mysql est installée
RUN php -m | grep pdo_mysql

# Installer Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash && \
    mv ~/.symfony*/bin/symfony /usr/local/bin/symfony

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Vérifier la version de PHP et de Composer
RUN php -v && composer --version
