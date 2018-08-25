#!/usr/bin/env bash

set -e

operation()
{
    printf '%*s\n' "${COLUMNS:-$(tput cols)}" | tr ' ' W
    echo '🤖 [Operation]' $1
}

operation "Re-Create the .env file."
rm -rf ./.env
cp ./.env.dist ./.env

operation "Clean up and regenerate SSL certificates."
rm -rf ./php/ssl/
mkdir ./php/ssl/

cd ./nginx/ssl/
file="./certs/certificate.local.crt"
if [ ! -e "$file" ]
then
    rm -rf ./certs/*
    git checkout ./certs/
    ./generate.sh
fi
cd -

cp -R ./nginx/ssl/certs/ ./php/ssl/certs/

operation "Build Docker container images"
docker-compose build

operation "Ready to take on the world! Try to run: docker-compose up"
