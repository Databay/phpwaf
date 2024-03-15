# Production installation
___
Navigate to directory where you want WAF to be installed (example: /var/www/web-application-firewall) and run the following command there.
```bash
git clone https://github.com/Databay/phpwaf 
```

To allow proper functionality you need to install composer dependencies. <b>(Currently only composer autoload)</b>
```bash
composer install --no-dev
```

# Configuration
___

## Comments:
Commenting out a line will result in the use of the next higher priority value.
If there is none available, the default will be used.
Add comments by placing a '#' before the comment.

## Default configuration (priority 4):
For basic use, there is no need to change the default configuration.
The default configuration is located in the `default.env` file.
Avoid, at all cost, direct modifications to the `default.env` file.
For custom configuration, refer to the next sections.

## Custom global configuration (priority 3):
To override the default configuration, copy the `default.env` file to `config/global.env` and modify the values to your preferences.
Values are stored as key-value pairs separated by '='.
All values are treated as strings and cannot contain '='.

## Custom hostname configuration (priority 2):
To specify a configuration for a specific hostname, copy the `default.env` file to `config/<hostname>.env` where 'hostname' is your application's hostname.
For instance, if your application is at https://example.com, the file should be named `example.com.env`.
The rules from [Custom global configuration](#custom-global-configuration-priority-3) apply.

## Fully custom configuration (priority 1):
(Only usable if you are implementing the WAF via [PHP index.php](#PHP-indexphp))

Create a fully custom configuration by copying the `default.env` file to `config/<myCustomName>.env`, where 'myCustomName' is your custom configuration's name.
For the configuration to take effect, define a constant named `WAF_ENV_FILE`, containing a string with the custom configuration file's name.
It is very important for the constant to be defined before the WAF is included!
Rules from [Custom global configuration](#custom-global-configuration-priority-3) apply.

# Implementation
There are two supported ways of implementing the WAF into your application. You can either include the WAF directly in the [Nginx configuration](#Nginx) <b>(recommended)</b> or you can include it in your [index.php](#PHP-indexphp) file of your web application.

### Nginx
Add the following to your nginx.conf under server:
```
fastcgi_param PHP_VALUE "auto_prepend_file=/var/www/phpwaf/src/waf.php";
```

### PHP index.php
Add the following to your index.php file:
```php
include_once /var/www/phpwaf/src/waf.php;
```
