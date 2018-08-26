#!/bin/bash
set -e

cd /usr/share/nginx/rush-b/
COMPOSER_ALLOW_SUPERUSER=1 composer install
