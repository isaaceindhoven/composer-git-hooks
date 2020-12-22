FROM docksal/cli:2.13-php7.3

LABEL maintainer="ISAAC <info@isaac.nl>"

RUN apt-get update \
    && ACCEPT_EULA=Y DEBIAN_FRONTEND=noninteractive apt-get upgrade -y \
    && rm -rf /var/lib/apt/lists/*