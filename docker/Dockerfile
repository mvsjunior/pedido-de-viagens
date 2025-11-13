# Etapa base
FROM php:8.3-fpm

# Instalar dependências e extensões PHP necessárias
RUN apt-get update && apt-get install -y \
    git unzip zip curl libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Definir diretório de trabalho
WORKDIR /var/www

# Copiar apenas os arquivos de dependências primeiro para melhor cache
COPY composer.json composer.lock ./

RUN composer install --no-interaction --no-scripts --no-autoloader

# Agora copiar o restante do projeto
COPY . .

# Gerar autoloader otimizado
RUN composer dump-autoload --optimize

# Ajustar permissões
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]
