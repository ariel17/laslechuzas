DROP DATABASE IF EXISTS laslechuzas;
CREATE DATABASE laslechuzas;

GRANT ALL ON laslechuzas.* TO 'laslechuzas'@'localhost' IDENTIFIED BY 'l4sl3chuz4s';
GRANT ALL ON *.* TO 'root'@'%' IDENTIFIED BY 'myroot';

FLUSH PRIVILEGES;
