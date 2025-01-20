#!/bin/bash

# Install composer if not installed
if ! [ -x "$(command -v composer)" ]; then
    echo 'Error: composer is not installed.' >&2
    echo 'Installing composer...'
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer
    php -r "unlink('composer-setup.php');"
fi

# Install dependencies
composer install

# Run tests
./vendor/bin/phpunit
