Yii 2 gebaseerd barkassa systeem.
============================
[![Laravel Forge Site Deployment Status](https://img.shields.io/endpoint?url=https%3A%2F%2Fforge.laravel.com%2Fsite-badges%2F1ca7df9b-20f6-480a-b20b-c054a854b947%3Fdate%3D1%26commit%3D1&style=plastic)](https://forge.laravel.com/servers/432841/sites/1279996)

Deze aplicatie is gemaakt voor het beheren van de bar voor kleine verenigingen.

Installatie:

Onder buntu kun je alle benodigde pakketten met apt installeren:
`sudo apt install composer php-cli php-gd php-intl php-mysql php-xml mariadb-server`

Database aanmaken:
`mysql -u root -p -e 'CREATE DATABASE barkassa'`

clone met git en maak de config/db.php:
```
<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=barkassa',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
];
```

 En de config/email.php:

```
<?php

return [
    'class' => 'Swift_SmtpTransport',
    'host' => 'localhost', // e.g. smtp.mandrillapp.com or smtp.gmail.com
    'username' => 'username',
    'password' => 'password',
    'port' => '587', // Port 25 is a very common port too
    'encryption' => 'tls', // It is often used, check your provider or mail
];
```

Vanaf de root
- `composer global require "fxp/composer-asset-plugin"`
- `composer install`
- `php yii migrate/up --migrationPath=@yii/rbac/migrations`
- `php yii migrate/up --migrationPath=@vendor/dektrium/yii2-user/migrations`
- `php yii migrate/up`
- `php yii serve`

Ga met je browser naar http://localhost:8080

Log in met de username `beheerder` en wachtwoord `beheerder` en wijzig het wachtwoord.
Deze gebruiker kan gebruikers toevoegen en rechten zetten op andere gebruikers.
