FROM php:5-fpm
MAINTAINER Ariel Gerardo Ríos <ariel.gerardo.rios@gmail.com>

RUN ["apt-get", "update"]
RUN ["apt-get", "install", "-y", "telnet", "wget", "libfreetype6-dev", "libjpeg62-turbo-dev", "libmcrypt-dev", "libpng12-dev", "ssmtp"]

RUN ["docker-php-ext-install", "iconv", "mcrypt", "mysqli"]
RUN ["docker-php-ext-configure", "gd", "--with-freetype-dir=/usr/include/", "--with-jpeg-dir=/usr/include/"]
RUN ["docker-php-ext-install", "gd"]
RUN ["sed", "-ri", "s/^www-data:x:33:33:/www-data:x:1000:50:/", "/etc/passwd"]
