# Micro-user

Implement microservice to manage users using DDD, CQRS, Event Sourcing applications using Symfony as framework and running with php7

[![Build Status](https://travis-ci.org/temafey/micro-user.svg?branch=master)](https://travis-ci.org/temafey/micro-user)
[![Coverage Status](https://coveralls.io/repos/github/temafey/micro-user/badge.svg?branch=master)](https://coveralls.io/github/temafey/micro-user?branch=coverage)
[![StyleCI](https://github.styleci.io/repos/116064483/shield?branch=master)](https://github.styleci.io/repos/116064483)

## Implementations

- [x] Environment in Docker
- [x] Command Bus, Query Bus, Event Bus
- [x] Event Store
- [x] Read Model
- [x] Async Event subscribers
- [x] Rest API
- [x] Web UI (A Terrible UX/UI)
- [x] Event Store Rest API

## Use Cases

#### User
- [x] Sign up
- [x] Change Email
- [x] Sign in
- [x] Logout

## Stack

- PHP 7.2
- Percona 5.7
- Elastic & Kibana 6.5
- RabbitMQ 3

## Project Setup

Up environment:

`make start`

Execute tests:

`make phpunit`

Static code analysis:

`make style`

Code style fixer:

`make cs`

Code style checker:

`make cs-check`

Enter in php container:

`make s=php sh`

Disable\Enable Xdebug:

`make xoff`

`make xon`

Build image to deploy

`make artifact`

Make release commit

`make rmt`

Make conventional commit,
read specs https://www.conventionalcommits.org/en/v1.0.0-beta.2

`make commit`

Watch containers logs

`make logs`

See all make commands

`make help`

Full test circle

`make test`
