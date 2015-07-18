#!/bin/bash

PROJECT="laslechuzas"
ROOT="/opt/$PROJECT"

debconf-set-selections <<< 'mysql-server mysql-server/root_password password myroot'
debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password myroot'

sudo apt-get update
sudo apt-get install -y php5-cli php5-gd php5-fpm php5-mysql php5-curl libssh2-php nginx mysql-server mysql-server-5.5 unzip

cd $ROOT

mysql -u root -pmyroot < provision/mysql/development.sql
mysql -u root -pmyroot $PROJECT < provision/mysql/development-data.sql

cp $ROOT/provision/mysql/my.cnf /etc/mysql/.

rm /etc/php5/fpm/pool.d/www.conf
ln -s $ROOT/provision/fpm/pool.d/www.conf /etc/php5/fpm/pool.d/.

rm /etc/php5/fpm/php.ini
ln -s $ROOT/provision/fpm/php.ini /etc/php5/fpm/.

rm /etc/nginx/nginx.conf
ln -s $ROOT/provision/nginx/nginx.conf /etc/nginx/.

rm /etc/nginx/sites-enabled/*
ln -s $ROOT/provision/nginx/laslechuzas.conf /etc/nginx/sites-enabled/.

chmod +x configure.sh
./configure.sh vagrant

service nginx configtest
service nginx restart
service php5-fpm restart
service mysql restart
