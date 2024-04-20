FROM php:8.0.30-apache
RUN apt-get update && apt-get install -y \
    unixodbc-dev \
    apt-transport-https \
    curl

RUN curl https://packages.microsoft.com/keys/microsoft.asc | tee /etc/apt/trusted.gpg.d/microsoft.asc
RUN curl https://packages.microsoft.com/config/debian/11/prod.list | tee /etc/apt/sources.list.d/mssql-release.list

RUN apt-get update && ACCEPT_EULA=Y apt-get install -y msodbcsql18

RUN echo 'export PATH="$PATH:/opt/mssql-tools18/bin"' >> ~/.bashrc
RUN echo 'export PATH="$PATH:/opt/mssql-tools18/bin"' >> /etc/environment
RUN pecl install pdo_sqlsrv sqlsrv
RUN docker-php-ext-enable pdo_sqlsrv sqlsrv
RUN a2enmod rewrite

WORKDIR /var/www/html
EXPOSE 80 
CMD ["apache2-foreground"]