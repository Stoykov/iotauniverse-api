# Iota Full Nodes List API

## What is this?

This is a simple API that automatically updates the status of added nodes and serves them as a JSON string to clients that request the information.

## How to install

Clone the repository on your server `git clone https://github.com/Stoykov/iotauniverse-api.git` and build a docker image from the Dockerfile in the root directory. After you have the image up and running, you should be able to access the api on port 80

Alternatively, you can set up your own web server and php interpreter. In that case you'll need to install composer ( https://getcomposer.org ) and run `composer install` in the root of the project. Additionally you'll need to create a storage folder and a nodes.json file inside of it. 

This should be the structure of the nodes.json file and for now nodes are added manually:

```json
[
    {
        "name": "TestNode",
        "ip": "127.0.0.1",
        "api_port": 14265,
        "address": "localhost",
        "country": {
            "name": "Germany",
            "iso": "de"
        }
    },
    {
        "name": "TestNode 2",
        "ip": "127.0.0.2",
        "api_port": 14265,
        "address": "localhost2",
        "country": {
            "name": "France",
            "iso": "fr"
        }
    }
]
```