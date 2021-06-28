<?php

return [
    'class' => 'Swift_SmtpTransport',
    'host' => $_ENV['EMAIL_HOST'], // e.g. smtp.mandrillapp.com or smtp.gmail.com
    'username' => $_ENV['EMAIL_USERNAME'],
    'password' => $_ENV['EMAIL_PASSWORD'],
    'port' => $_ENV['EMAIL_PORT'], // Port 25 is a very common port too
    'encryption' => 'tls', // It is often used, check your provider or mail
];
