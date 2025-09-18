# コンテナの元になるイメージ(docker hubのphp公式イメージ)
FROM php:8.2.17-apache
#作業ディレクトリの指定
WORKDIR /var/www/html
# ライブラリのインストール
RUN apt-get update \
    && apt-get install -y \
                vim \
                git \
                zip \
                unzip \
                libonig-dev \
                libfreetype6-dev \ 
                libjpeg62-turbo-dev \
                libpng-dev \
                libjpeg-dev \
                libzip-dev \
                libwebp-dev \
                libmagickwand-dev \
    && pecl install xdebug \
                    imagick \
    && docker-php-ext-enable xdebug \
                            imagick \
    && docker-php-ext-install \ 
                pdo_mysql \ 
                bcmath \
                gd \
                zip \
                gettext \
                calendar \
    && docker-php-ext-configure gd \
                --with-freetype=/usr/include/ \
                --with-jpeg=/usr/include/ \
                --with-webp=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd exif
RUN ["/bin/bash", "-c", "bash < <(curl -sL https://raw.githubusercontent.com/axllent/mailpit/develop/install.sh)"]
RUN a2enmod rewrite
RUN a2enmod actions
# ローカルの設定ファイルをコンテナ内にコピー
# COPY [コピーする(ローカル)] [コピー先(コンテナ内)]
COPY php.ini /usr/local/etc/php/php.ini
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
COPY mailpit.ini /usr/local/etc/php/conf.d/mailpit.ini
COPY policy.xml /etc/ImageMagick-6/policy.xml
COPY permission.conf /etc/apache2/sites-enabled/permission.conf