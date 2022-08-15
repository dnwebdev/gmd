#!/bin/sh

cd /opt/lampstack-7.1.12-0/apps/memoria/htdocs && git pull origin master
cd /opt/lampstack-7.1.12-0/apps/memoria/htdocs/ && /opt/lampstack-7.1.12-0/php/bin/php /root/composer.phar update

