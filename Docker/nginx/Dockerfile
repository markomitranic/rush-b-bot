FROM nginx:latest AS base

RUN rm /etc/nginx/conf.d/*

FROM base AS dev
COPY ssl/certs /etc/nginx/ssl
COPY conf-dev /etc/nginx/conf.d
