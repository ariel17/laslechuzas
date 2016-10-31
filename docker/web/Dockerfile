FROM nginx:latest
MAINTAINER Ariel Gerardo Ríos <ariel.gerardo.rios@gmail.com>

RUN ["apt-get", "update"]
RUN ["apt-get", "install", "-y", "telnet", "wget"]

RUN ["mkdir", "-p", "/srv/laslechuzas/www"]

WORKDIR /srv/laslechuzas/www

COPY ["site.conf", "/etc/nginx/conf.d/default.conf"]
