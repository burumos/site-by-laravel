From centos:7

RUN  yum update -y && yum install -y epel-release which git unzip
RUN rpm -Uvh http://rpms.famillecollet.com/enterprise/remi-release-7.rpm && \
    yum install -y --enablerepo=remi-php73 php php-pdo php-mysqlnd \
    php-mbstring php-bcmath php-json php-pecl-crypto php-xml php-pecl-zip

# RUN echo 'extension=pdo_mysql.so' >> /etc/php.d/20-pdo.ini


# install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php && \
    mv composer.phar /bin/composer && \
    php -r "unlink('composer-setup.php');"

# install laravel
RUN composer global require laravel/installer
RUN echo 'export PATH="$HOME/.composer/vendor/bin/:$PATH"' >> ~/.bashrc

# install nodejs + npm
RUN curl -sL https://rpm.nodesource.com/setup_13.x | bash - && \
    yum install -y nodejs


# mail
RUN curl -sSLO https://github.com/mailhog/mhsendmail/releases/download/v0.2.0/mhsendmail_linux_amd64 \
    && chmod +x mhsendmail_linux_amd64 \
    && mv mhsendmail_linux_amd64 /usr/local/bin/mhsendmail \
    && echo 'sendmail_path = "/usr/local/bin/mhsendmail --smtp-addr=mailhog:1025"' >> /etc/php.ini

WORKDIR /root
RUN ln -s /var/www/html/app/site /root/

COPY site /var/www/html/app/site

EXPOSE 80
CMD ["httpd", "-DFOREGROUND"]
