FROM docksal/cli:2.11-php7.3

LABEL maintainer="ISAAC <info@isaac.nl>"

RUN apt-get update \
    && ACCEPT_EULA=Y DEBIAN_FRONTEND=noninteractive apt-get upgrade -y \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:1.10.10 /usr/bin/composer /usr/local/bin/

USER docker
RUN composer global remove hirak/prestissimo \
    && composer global require isaac/composer-velocita \
    && composer global velocita:enable https://composer-cache.hq.isaac.nl/
USER root