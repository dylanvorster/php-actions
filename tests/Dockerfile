FROM php:5.6-apache

MAINTAINER Dylan Vorster <dylan@eezipay.com>

RUN docker-php-ext-install mbstring
RUN docker-php-ext-install bcmath

RUN apt-get update --fix-missing
RUN apt-get upgrade -y
RUN apt-get install -y git

RUN pecl install igbinary
RUN echo 'extension=igbinary.so' >> /usr/local/etc/php/php.ini

RUN git clone https://github.com/phpredis/phpredis.git phpredis

RUN cd phpredis && phpize
RUN cd phpredis && ./configure --enable-redis-igbinary
RUN cd phpredis && make && make install

RUN echo 'extension=redis.so' >> /usr/local/etc/php/php.ini

RUN echo 'session.save_handler = redis' >> /usr/local/etc/php/php.ini
RUN echo 'session.save_path = "${REDIS_PORT}"' >> /usr/local/etc/php/php.ini

RUN a2enmod rewrite
RUN cd /etc/apache2/sites-available && rm 000-default.conf \
&& echo "<VirtualHost *:80>\n \
	ServerAdmin webmaster@localhost\n \
	DocumentRoot /var/www/html\n \
	ErrorLog ${APACHE_LOG_DIR}/error.log\n \
	CustomLog ${APACHE_LOG_DIR}/access.log combined\n \
	AllowOverride All\n \
</VirtualHost>" > 000-default.conf \ && cat 000-default.conf

RUN apt-get install -y -f phpunit