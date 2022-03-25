# wallabag v3

Here is a POC for wallabag v3. 

## Summary

- [Installation](#installation)
- [Tests](#tests)

## Installation

### Prerequisites

-   [docker (linux)](https://docs.docker.com/install/linux/docker-ce/ubuntu/) or [docker-destkop (mac/windows)](https://www.docker.com/products/docker-desktop)
-   [docker-compose](https://docs.docker.com/compose/install/)
-   [mutagen-compose](https://mutagen.io/documentation/introduction/installation) `brew install mutagen-io/mutagen/mutagen-compose`

    > **NOTE**: Please ensure you can run docker without sudo ([tutorial](https://docs.docker.com/install/linux/linux-postinstall/)).

### Usage

All interactions with the local environment is done through the filesystem and the `./wallabag` util.

-   `./wallabag start` will create and run all containers
-   `./wallabag stop` will stop and destroy all containers
-   `./wallabag restart` will stop then start all containers
-   `./wallabag clean` will remove all generated artifacts in the project (they should already be ignored by git). Such artifacts include the database persistence directory
-   `./wallabag shell [api | ui]` will bind a shell to the corresponding container
-   `./wallabag run [api | ui ]` ...commandWithArgs` will execute the **commandWithArgs** inside a new container
-   `./wallabag exec [api | ui | db ] ...commandWithArgs` will execute the **commandWithArgs** inside the related container
-   `./wallabag logs [api | ui | db ]` will show the related container logs in watch mode

The differents services are accessible from localhost:

-   **api** on port `4000` with http protocol
-   **db** on port `6033` with mysql protocol
-   **PhpMyAdmin** on port `8081` with http protocol

> **NOTE**: Please feel free to edit the docker-compose.yml and .env to fit your needs

## Tests

To run tests:

```
./wallabag shell api
bin/phpunit
```