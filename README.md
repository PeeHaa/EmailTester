EmailTester
=

A service to test regex patterns against valid and invalid emailaddresses. Check out the [demo][demo].

Requirements
-

- PHP 5.4+
- PostgreSQL

Installation
-

Currently PostgreSQL is tightly coupled to the project, because I am lazy...

Clone the repository into a directory:

    git clone git@github.com:PeeHaa/EmailTester.git

Rewrite all requests through the `/public/index.php` file. Apache vhost example:

    <VirtualHost *:80>
      ServerName www.emailtester.com
      DocumentRoot "/path/to/EmailTester/public"

      <IfModule mod_dir.c>
        DirectoryIndex index.php
      </IfModule>

      <Directory "/path/to/EmailTester/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
      </Directory>

      RewriteEngine on

      RewriteCond "/path/to/EmailTester/public/%{REQUEST_URI}" !-f
      RewriteRule ^(.*)$ /index.php/$1 [L]

    </VirtualHost>

Create the database by importing the `/EmailTester/init.sql` file.

Run `get_addresses.php` from the CLI to get a nice list of valid and invalid emailaddresses:

    php -f get_addresses.php

Copy `/EmailTester/init.example.php` to `/EmailTester/init.production.php` and setup the db settings and error reporting.

Change the environment file in `/EmailTester/init.deployment.php`

[demo]:https://emailtester.pieterhordijk.com