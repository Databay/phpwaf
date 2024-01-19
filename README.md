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

### Nginx configuration
Add the following to your nginx.conf under server:
```
fastcgi_param PHP_VALUE "auto_prepend_file=/var/www/web-application-firewall/public/index.php";
```

### Docker (optional)
To include the WAF in your Docker applications you need to bind the WAF directory to a directory inside the container (example: /waf).

#### Example:
In docker-compose.yml under nginx volumes and php volumes:
- /var/www/web-application-firewall:/var/www/web-application-firewall

# Development environment
___
To start the application please run the following command:
```bash
docker-compose up -d
```
The application will be available, by default, at http://localhost:9000.

# Container access
___
To access the container, please run the following command int the root directory of the project:
```bash
docker-compose exec app bash
```