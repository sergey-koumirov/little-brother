1. ===== nginx config =====

server {
    listen 8088 default_server;
    listen [::]:8088 default_server ipv6only=on;

    root /home/gopher/eve/little-brother/web;
    set $yii_bootstrap "index.php";

    server_name localhost;

    location / {
        index  index.html $yii_bootstrap;
        try_files $uri $uri/ /$yii_bootstrap?$args;
    }

    error_page 404 /404.html;
    error_page 500 502 503 504 /50x.html;
    location = /50x.html {
        root /usr/share/nginx/html;
    }

    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/var/run/php5-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}



2. nginx restart ubuntu
sudo service nginx restart

3. migrate DB
cd root
./yii migrate
./yii migrate/down

4. composer
php -r "readfile('https://getcomposer.org/installer');" > composer-setup.php
php -r "if (hash('SHA384', file_get_contents('composer-setup.php')) === '7228c001f88bee97506740ef0888240bd8a760b046ee16db8f4095c0d8d525f2367663f22a46b48d072c816e7fe19959') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"

php composer.phar install


5. redis

redis-server
QUEUE=* ./yii resque start

6. eve api lib (yapeal)
sudo apt-get install php5-curl
sudo apt-get install php5-xsl
sudo apt-get install php5-tidy

cd ./vendor/yapeal/yapeal
php bin/yc D:I -u root -p root
