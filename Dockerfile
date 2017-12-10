FROM ubuntu:16.04

# Install base packages
RUN apt-get update && apt-get install cron && apt-get install -y nginx php7.0 php7.0-bcmath php7.0-bz2 php7.0-curl php7.0-gd php7.0-intl php7.0-json php7.0-ldap php7.0-mbstring php7.0-mcrypt php7.0-mysql php7.0-opcache php7.0-xml php7.0-sqlite3 php7.0-pspell php7.0-zip supervisor

# Cleanup
RUN apt-get clean
RUN rm -r /var/www/html

# Configure services
COPY provision/supervisord.conf /etc/supervisord.conf
COPY provision/nginx.vhost /etc/nginx/sites-enabled/default
COPY provision/php-pool.conf /etc/php/7.0/fpm/pool.d/www.conf

# Run PHP once, so all runfiles are created
RUN service php7.0-fpm start

# Configure environment
WORKDIR /var/www
ENTRYPOINT ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisord.conf"]

ENV COMPOSER_VERSION 1.4.1

# Install composer
RUN php -r "copy('https://getcomposer.org/installer', '/tmp/composer-setup.php');" && \
    php -r "copy('https://composer.github.io/installer.sig', '/tmp/composer-setup.sig');" && \
    php -r "if (hash('SHA384', file_get_contents('/tmp/composer-setup.php')) !== trim(file_get_contents('/tmp/composer-setup.sig'))) { unlink('/tmp/composer-setup.php'); echo 'Invalid installer' . PHP_EOL; exit(1); }" && \
    php /tmp/composer-setup.php --version=$COMPOSER_VERSION --install-dir=/bin && \
    php -r "unlink('/tmp/composer-setup.php');"

# Copy project files over to docker image
COPY . /var/www

# Run composer
RUN php /bin/composer.phar install

# Make storage folder
RUN mkdir -p /var/www/storage

# Fix permissions
RUN chown -R www-data:www-data /var/www

# Setup cron
RUN echo "* * * * * php /var/www/cron.php" >> /etc/crontab

# Cleanup
RUN apt-get clean