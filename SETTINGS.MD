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
