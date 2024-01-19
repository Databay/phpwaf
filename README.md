# Production installation
___
Navigate to directory where you want WAF to be installed (example: /var/www/web-application-firewall) and run the following command there.
```bash
git clone git@gitlab.databay.de:mzych/web-application-firewall.git .
```

To allow proper functionality you need to install composer dependencies. <b>(Currently only composer autoload)</b>
```bash
composer install --no-dev
```

# Implementation
There are two supported ways of implementing the WAF into your application. You can either include the WAF directly in the [Nginx configuration](#Nginx) <b>(recommended)</b> or you can include it in your [index.php](#PHP-indexphp) file of your web application.

### Nginx
Add the following to your nginx.conf under server:
```
fastcgi_param PHP_VALUE "auto_prepend_file=/var/www/web-application-firewall/public/index.php";
```

### PHP index.php
Add the following to your index.php file:
```php
include_once /var/www/web-application-firewall/waf.php;
```

# Development environment
___
To start the application please run the following command:
```bash
docker-compose up -d
```
The application will be available, by default, at https://127.0.0.1:9443.

# Container access
___
To access the container, please run the following command in the root directory of the project:
```bash
docker-compose exec app bash
```