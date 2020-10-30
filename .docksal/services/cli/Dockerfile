FROM docksal/cli:2.11-php7.4

LABEL maintainer="ISAAC <info@isaac.nl>"

RUN apt-get update \
    && ACCEPT_EULA=Y DEBIAN_FRONTEND=noninteractive apt-get upgrade -y \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:1.10.10 /usr/bin/composer /usr/local/bin/