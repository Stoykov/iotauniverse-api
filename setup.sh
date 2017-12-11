#!/bin/bash -xe

if [[ $EUID -ne 0 ]]; then
   echo "This script must be run as root"
   exit 1
fi

PKG_OK=$(dpkg-query -W --showformat='${Status}\n' docker-ce |grep "install ok installed")
if [ "" == "$PKG_OK" ]; then
    curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -
    add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable"
    apt-cache policy docker-ce
    apt-get install -y docker-ce
    systemctl status docker
fi

# Build the API image
docker build -t iotauniverse --label api ./

# We'll need an .env file in there
docker exec -it IotaUniverse touch /var/www/.env

# Run the image
docker run --name="IotaUniverse" --detach=true --label api -p 0.0.0.0:80:80 iotauniverse