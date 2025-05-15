# Usar uma imagem PHP oficial com FPM (FastCGI Process Manager)
# Escolha a versão do PHP compatível com o seu projeto Laravel
FROM php:8.2-fpm

# Argumentos que podem ser passados durante o build
ARG user=laravel
ARG uid=1000

# Variáveis de ambiente
ENV COMPOSER_ALLOW_SUPERUSER=1

# Diretório de trabalho
WORKDIR /var/www/html

# Instalar dependências do sistema
# libpng-dev, libjpeg-dev, libfreetype-dev para gd
# libzip-dev para zip
# libonig-dev para mbstring
# libxml2-dev para soap e xml
# libpq-dev para pdo_pgsql (se usar PostgreSQL)
# default-mysql-client ou mariadb-client para mysql (se usar MySQL/MariaDB)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype-dev \
    libzip-dev \
    zip \
    unzip \
    libxml2-dev \
    libonig-dev \
    # Adicione aqui outras dependências que seu projeto possa precisar
    # Ex: default-mysql-client ou libpq-dev se usar banco de dados
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensões PHP
# mbstring, exif, pcntl, bcmath, gd são comuns em Laravel
# pdo_mysql ou pdo_pgsql se usar banco de dados
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo pdo_mysql zip exif pcntl bcmath mbstring soap sockets
    # Se precisar de opcache (recomendado para produção):
    # RUN docker-php-ext-enable opcache

# Instalar Composer globalmente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Criar usuário do sistema para rodar a aplicação (opcional, mas boa prática)
RUN groupadd -g $uid $user \
    && useradd -u $uid -ms /bin/bash -g $user $user

# Copiar arquivos da aplicação
# O .dockerignore deve ser usado para excluir node_modules, vendor, .git, etc.
COPY . .

# Mudar o proprietário dos arquivos para o usuário da aplicação
# Isso é importante se você for rodar o composer install como o usuário não-root
# RUN chown -R $user:$user /var/www/html

# Instalar dependências do Composer
# --no-interaction: Não fazer perguntas interativas
# --no-plugins: Desabilitar plugins (pode ser necessário em alguns CIs)
# --no-scripts: Não executar scripts definidos no composer.json (configure depois se necessário)
# --prefer-dist: Baixar arquivos zipados em vez de clonar repositórios (mais rápido)
RUN composer install --no-interaction --optimize-autoloader --no-dev
# Se você precisar de dependências de desenvolvimento (ex: para testes no container), remova --no-dev

# Ajustar permissões para Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copiar o .env.example para .env (ou montar um .env em tempo de execução)
# RUN php -r "file_exists(\".env\") || copy(\".env.example\", \".env\");"
# RUN php artisan key:generate # Geralmente feito ao iniciar o container ou via entrypoint

# Expor a porta 9000 e iniciar php-fpm
EXPOSE 9000
CMD ["php-fpm"]

# Se você quiser rodar 'php artisan serve' (para desenvolvimento simples, não recomendado para produção):
# EXPOSE 8000
# CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

