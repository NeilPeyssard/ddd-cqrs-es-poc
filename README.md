# DDD + CQRS + Event Sourcing project

## Overview

This project is a POC for testing a different way to work on Symfony project. It includes 
domain driven development, CQRS and event sourcing.

The goal here, is to reproduce an Oauth Flow, with an authorization code grant type, to access 
two protected endpoints (create and read a project).

Keep in mind it's only a POC, and there is room for multiple improvements.

## Installation

In order to install the project, you can use the Makefile with the following command:

```shell
make install
```

## Testing

There is a test suite available with behat. There is also a task for this in the Makefile:

```shell
make test
```


