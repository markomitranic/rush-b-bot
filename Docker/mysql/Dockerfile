FROM mariadb:latest AS base

FROM base AS dev

COPY my-dev.cnf /etc/my.cnf

FROM base AS prod

COPY my-prod.cnf /etc/my.cnf
