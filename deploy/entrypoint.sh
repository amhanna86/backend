#!/bin/bash

sleep 15

export APP_ENV=prod

FILE=/var/www/html/public/uploads
if [ ! -f "$FILE" ]; then
    mkdir $FILE
fi

chmod -R 777 /var/www/html/public/uploads/
# cp /var/www/html/config/secrets/.env /var/www/html/

touch /var/www/html/.env.local
echo "DATABASE_URL=mysql://\"$DB_USER\":\"$DB_PASSWORD\"@\"$DB_HOST\":3306/\"$DB_NAME\"" >> /var/www/html/.env.local

cd /var/www/html/


# APP_ENV=prod APP_DEBUG=0 php composer.phar install --no-dev --optimize-autoloader
APP_ENV=prod APP_DEBUG=0 php -d memory_limit=-1 bin/console d:d:c
APP_ENV=prod APP_DEBUG=0 php bin/console --no-interaction d:m:m #> /dev/null 2>&1

if [ ! -f /var/www/html/public/uploads/.installed ]; then
    touch /var/www/html/public/uploads/.installed
fi

chmod -R 777 /var/www/html/var
exec apache2-foreground
