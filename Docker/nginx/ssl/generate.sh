#!/usr/bin/env bash

openssl genrsa -out ./certs/rootCA.key 4096

openssl req \
    -x509 \
    -new \
    -nodes \
    -key ./certs/rootCA.key \
    -sha256 \
    -days 3560 \
    -out ./certs/rootCA.crt \
    -config rootCA.cnf

openssl genrsa -out ./certs/certificate.local.key 2048

openssl req \
    -new \
    -key ./certs/certificate.local.key \
    -out ./certs/certificate.local.csr \
    -reqexts SAN \
    -extensions SAN \
    -config certificate.local.cnf

openssl x509 \
    -req \
    -in ./certs/certificate.local.csr \
    -CA ./certs/rootCA.crt \
    -CAkey ./certs/rootCA.key \
    -CAcreateserial \
    -extensions v3_req \
    -extfile certificate.local.cnf \
    -out ./certs/certificate.local.crt \
    -days 3650 \
    -sha256