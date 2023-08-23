Yore - A Framework for YoreWeb

https://yoreweb.com

Be sure that the following lines are in your web host config

```
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteCond %{REQUEST_FILENAME} !-d
            RewriteRule ^(.*)$ /index.php/$1 [NC,L]
```

For example, for Apache your sites-available ssl config might look like this: 


```
<IfModule mod_ssl.c>
<VirtualHost *:443>
        ServerName blackrush.us
        <Directory /var/www/yore/web>
            Options Indexes FollowSymLinks MultiViews
            AllowOverride All
            Require all granted

            RewriteEngine on
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteCond %{REQUEST_FILENAME} !-d
            RewriteRule ^(.*)$ /index.php/$1 [NC,L]

        </Directory>

        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/yore/web
        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined

SSLCertificateFile /etc/letsencrypt/live/erikleeolson.com-0001/fullchain.pem
SSLCertificateKeyFile /etc/letsencrypt/live/erikleeolson.com-0001/privkey.pem
Include /etc/letsencrypt/options-ssl-apache.conf
</VirtualHost>
</IfModule>


```
For some reason, this only works when the Rewrite stuff
is put inside the <Directory> tag.

Alno....