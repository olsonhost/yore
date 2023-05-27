Yore - A Framework for YoreWeb

https://yoreweb.com

vhost

Be sure that the following lines are in your web host config

```        RewriteEngine on
        RewriteCond %{REQUEST_URI} !^/index.php$
        RewriteRule ^(.+)$ /index.php?url=$1 [NC,L]
```

For example, for Apache your sites-available ssl config might look like this: 


```
<VirtualHost *:443>
ServerName yoreweb.com
<Directory /var/www/yore/web>
AllowOverride All
</Directory>

        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/yore/web

        ErrorLog ${APACHE_LOG_DIR}/dev-error.log
        CustomLog ${APACHE_LOG_DIR}/dev-access.log combined

        RewriteEngine on
        RewriteCond %{REQUEST_URI} !^/index.php$
        RewriteRule ^(.+)$ /index.php?url=$1 [NC,L]

SSLCertificateFile /etc/letsencrypt/live/yoreweb.com/fullchain.pem
SSLCertificateKeyFile /etc/letsencrypt/live/yoreweb.com/privkey.pem
Include /etc/letsencrypt/options-ssl-apache.conf
</VirtualHost>

```
