.DEFAULT_GOAL := help

ifndef CI_JOB_ID
	# Colors
	GREEN  := $(shell tput -Txterm setaf 2)
	YELLOW := $(shell tput -Txterm setaf 3)
	WHITE  := $(shell tput -Txterm setaf 7)
	RESET  := $(shell tput -Txterm sgr0)
	TARGET_MAX_CHAR_NUM=30
endif

# Programs
DOCKER_COMPOSE = docker compose
PHP = ${DOCKER_COMPOSE} exec php-fpm php
COMPOSER = ${DOCKER_COMPOSE} exec php-fpm composer
CONSOLE = ${PHP} bin/console

help:
	@echo "${GREEN}SensioLabs Jobs application${RESET}"
	@awk '/^[a-zA-Z\-_0-9]+:/ { \
		helpMessage = match(lastLine, /^## (.*)/); \
		if (helpMessage) { \
			helpCommand = substr($$1, 0, index($$1, ":") - 1); helpMessage = substr(lastLine, RSTART + 3, RLENGTH); \
			printf "  ${YELLOW}%-$(TARGET_MAX_CHAR_NUM)s${RESET} ${GREEN}%s${RESET}\n", helpCommand, helpMessage; \
		} \
		isTopic = match(lastLine, /^###/); \
	    if (isTopic) { printf "\n%s\n", $$1; } \
	} { lastLine = $$0 }' $(MAKEFILE_LIST)

#################################
Project:

## Start the project
start: up

## Stop the project
stop: down

## Refresh the database
clear: db-drop db-create db-migrate db-fixtures setup-cli cache

## Clean the project
clean: start db-drop stop

## Install the project
install: start composer clear

## Create ES index and populate them
setup-cli:
	@${CONSOLE} app:setup --no-interaction

#################################
Test:

test:
	@${CONSOLE} doctrine:database:drop --force --if-exists --env=test
	@${CONSOLE} doctrine:database:create --if-not-exists --env=test
	@${CONSOLE} doctrine:migrations:sync-metadata-storage --env=test
	@${CONSOLE} doctrine:migrations:migrate --no-interaction --env=test
	@${CONSOLE} doctrine:fixtures:load --no-interaction --env=test
	@${CONSOLE} app:setup --no-interaction --env=test
	@sleep 2
	@${PHP} vendor/bin/behat
#################################
Cache:

## Remove the cache and warmup a new one
cache: cache-clear cache-warmup

## Remove the cache
cache-clear:
	@${CONSOLE} cache:clear

## Warmup the cache
cache-warmup:
	@${CONSOLE} cache:warmup

#################################
Composer:

## Install all required PHP vendors
composer:
	@${COMPOSER} install

#################################
Database:

## Remove database
db-drop:
	@${CONSOLE} doctrine:database:drop --force --if-exists

## Create database
db-create:
	@${CONSOLE} doctrine:database:create --if-not-exists
	@${CONSOLE} doctrine:migrations:sync-metadata-storage

## Execute Doctrine migrations
db-migrate:
	@${CONSOLE} doctrine:migrations:migrate --no-interaction

## Load data fixtures
db-fixtures:
	@${CONSOLE} doctrine:fixtures:load --no-interaction

#################################
Docker:

## Start Docker containers
up:
	@${DOCKER_COMPOSE} up -d

## Stop Docker containers
down:
	@${DOCKER_COMPOSE} down --remove-orphans
