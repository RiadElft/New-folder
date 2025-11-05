# Use PHP 8.1
FROM php:8.1-cli

# Install PostgreSQL and MySQL dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Create startup script
RUN echo '#!/bin/bash\n\
cd /var/www/html\n\
php -S 0.0.0.0:${PORT:-80} -t public' > /start.sh && chmod +x /start.sh

# Expose port
EXPOSE 80

# Start PHP server
CMD ["/start.sh"]
