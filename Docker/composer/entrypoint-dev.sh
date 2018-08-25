#!/bin/bash
set -e

cd /usr/share/nginx/rush-b/
#COMPOSER_ALLOW_SUPERUSER=1 composer install

# Tail is used to keep the container running in development environment.
tail -f /dev/null
