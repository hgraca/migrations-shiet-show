# Makefile
#
# This file contains the commands most used in DEV, plus the ones used in CI and PRD environments.
#

# Execute targets as often as wanted
.PHONY: config

# Mute all `make` specific output. Comment this out to get some debug information.
.SILENT:

# make commands be run with `bash` instead of the default `sh`
SHELL='/bin/bash'

include Makefile.defaults.mk
ifneq ("$(wildcard Makefile.defaults.custom.mk)","")
  include Makefile.defaults.custom.mk
endif

# .DEFAULT: If the command does not exist in this makefile
# default:  If no command was specified
.DEFAULT default:
	if [ -f ./Makefile.custom.mk ]; then \
	    $(MAKE) -f Makefile.custom.mk "$@"; \
	else \
	    if [ "$@" != "default" ]; then echo "Command '$@' not found."; fi; \
	    $(MAKE) help; \
	    if [ "$@" != "default" ]; then exit 2; fi; \
	fi

help:  ## Show this help
	@echo "Usage:"
	@echo "     [ARG=VALUE] [...] make [command]"
	@echo "     make env-status"
	@echo "     NAMESPACE=\"dummy-app-namespace\" RELEASE_NAME=\"another-dummy-app\" make env-status"
	@echo
	@echo "Available commands:"
	@grep '^[^#[:space:]].*:' Makefile | grep -v '^default' | grep -v '^\.' | grep -v '=' | grep -v '^_' | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m  %-40s\033[0m %s\n", $$1, $$2}' | sed 's/://'

########################################################################################################################

###############################
## Database
###############################

.create-db-dev:  ## Recreate the dev database, including dev fixtures
	- GT_APP_ENV=dev bin/console doctrine:database:drop --force
	- GT_APP_ENV=dev bin/console doctrine:database:create
	GT_APP_ENV=dev bin/console doctrine:query:sql "SET GLOBAL sql_mode = 'STRICT_TRANS_TABLES';"
	GT_APP_ENV=dev make migration-migrate

migration-status: ## Display a current status of the migrations
	php bin/console ${OPTIONS} doctrine:migrations:status

migration-list: ## Display a list of all migrations and their current status
	php bin/console ${OPTIONS} doctrine:migrations:list

migration-generate: ## Create e migration file by comparing the DB with the ORM config
	php bin/console ${OPTIONS} doctrine:migrations:diff --allow-empty-diff --formatted

migration-generate-blank: ## Generates an empty migration file
	php bin/console ${OPTIONS} doctrine:migrations:generate

migration-migrate: ## Runs all migrations that have not been run yet
	php bin/console ${OPTIONS} doctrine:migrations:migrate --no-interaction

migration-migrate-prev: ## Undoes the last migration applied
	php bin/console ${OPTIONS} doctrine:migrations:migrate prev --no-interaction

migration-reset: ## Drops all tables except for the migrations table, although it also empties it
	php bin/console ${OPTIONS} doctrine:schema:drop --full-database --force && php bin/console ${OPTIONS} doctrine:migrations:migrate --no-interaction

###############################
## Docker
###############################

docker-create-network: ## Create the docker network to be used by this project docker-compose
	-docker network create ${DOCKER_NETWORK}
	docker network inspect ${DOCKER_NETWORK} | grep Gateway | awk '{print $$2}' | tr -d '"'

docker-up:  ## Start the application and keep it running and showing the logs
	DOCKER_USER_ID=${HOST_USER_ID} DOCKER_NETWORK=${DOCKER_NETWORK} HOST_IP=${HOST_IP} PROJECT=${PROJECT} docker-compose -f docker/docker-compose.yml up --remove-orphans ${CONTAINERS}

docker-up-deamon:  ## Start the application and keep it running and showing the logs
	DOCKER_USER_ID=${HOST_USER_ID} DOCKER_NETWORK=${DOCKER_NETWORK} HOST_IP=${HOST_IP} PROJECT=${PROJECT} docker-compose -f docker/docker-compose.yml up -d --remove-orphans ${CONTAINERS}

docker-up-daemon:  ## Start the application and keep it running in the background
	DOCKER_USER_ID=${HOST_USER_ID} DOCKER_NETWORK=${DOCKER_NETWORK} HOST_IP=${HOST_IP} PROJECT=${PROJECT} docker-compose -f docker/docker-compose.yml up --remove-orphans -d ${CONTAINERS}

docker-down:  ## Stop the application
	DOCKER_USER_ID=${HOST_USER_ID} DOCKER_NETWORK=${DOCKER_NETWORK} HOST_IP=${HOST_IP} PROJECT=${PROJECT} docker-compose -f docker/docker-compose.yml down --remove-orphans

docker-logs:  ## Show the application logs
	DOCKER_USER_ID=${HOST_USER_ID} DOCKER_NETWORK=${DOCKER_NETWORK} HOST_IP=${HOST_IP} PROJECT=${PROJECT} docker-compose -f docker/docker-compose.yml logs -f

docker-shell:  ## Open a shell into the web(php) container
	DOCKER_USER_ID=${HOST_USER_ID} DOCKER_NETWORK=${DOCKER_NETWORK} HOST_IP=${HOST_IP} PROJECT=${PROJECT} docker-compose -f docker/docker-compose.yml exec shietshow-web bash

docker-generate-migration:  ## Create a migration file by comparing the DB with the ORM config, from inside the web(php) container
	DOCKER_USER_ID=${HOST_USER_ID} DOCKER_NETWORK=${DOCKER_NETWORK} HOST_IP=${HOST_IP} PROJECT=${PROJECT} docker-compose -f docker/docker-compose.yml exec shietshow-web sh -c 'make migration-generate'

docker-generate-migration-blank:  ## Create a blank migration file, from inside the web(php) container
	DOCKER_USER_ID=${HOST_USER_ID} DOCKER_NETWORK=${DOCKER_NETWORK} HOST_IP=${HOST_IP} PROJECT=${PROJECT} docker-compose -f docker/docker-compose.yml exec shietshow-web sh -c 'make migration-generate-blank'

docker-run-migrations:  ## Run migrations that have not yet been executed, from inside the web(php) container
	DOCKER_USER_ID=${HOST_USER_ID} DOCKER_NETWORK=${DOCKER_NETWORK} HOST_IP=${HOST_IP} PROJECT=${PROJECT} docker-compose -f docker/docker-compose.yml exec shietshow-web sh -c 'make migration-migrate'

docker-create-db-dev:  ## Recreate the dev database, including dev fixtures. Run this from outside the docker container.
	DOCKER_USER_ID=${HOST_USER_ID} DOCKER_NETWORK=${DOCKER_NETWORK} HOST_IP=${HOST_IP} PROJECT=${PROJECT} docker-compose -f docker/docker-compose.yml exec shietshow-web sh -c 'make .reset-db'

docker-rebuild-images:  ## Rebuild the docker images
	- docker image rm -f docker_shietshow-web
	DOCKER_USER_ID=${HOST_USER_ID} DOCKER_NETWORK=${DOCKER_NETWORK} HOST_IP=${HOST_IP} PROJECT=${PROJECT} docker-compose -f docker/docker-compose.yml build shietshow-web

docker-install-deps:  ## Install project dependencies
	DOCKER_USER_ID=${HOST_USER_ID} DOCKER_NETWORK=${DOCKER_NETWORK} HOST_IP=${HOST_IP} PROJECT=${PROJECT} docker-compose -f docker/docker-compose.yml exec shietshow-web sh -c './composer.phar install'

docker-update-deps:  ## Update project dependencies
	DOCKER_USER_ID=${HOST_USER_ID} DOCKER_NETWORK=${DOCKER_NETWORK} HOST_IP=${HOST_IP} PROJECT=${PROJECT} docker-compose -f docker/docker-compose.yml exec shietshow-web sh -c './composer.phar update -W "doctrine/doctrine-migrations-bundle:3.4.0"'

docker-composer-why-not:  ## composer debug: Why not
	DOCKER_USER_ID=${HOST_USER_ID} DOCKER_NETWORK=${DOCKER_NETWORK} HOST_IP=${HOST_IP} PROJECT=${PROJECT} docker-compose -f docker/docker-compose.yml exec shietshow-web sh -c './composer.phar why-not "doctrine/doctrine-migrations-bundle" "3.4.0"'

docker-composer-diagnose:  ## composer debug: diagnose composer
	DOCKER_USER_ID=${HOST_USER_ID} DOCKER_NETWORK=${DOCKER_NETWORK} HOST_IP=${HOST_IP} PROJECT=${PROJECT} docker-compose -f docker/docker-compose.yml exec shietshow-web sh -c './composer.phar diagnose'

docker-composer-self-update:  ## Update composer executable ot latest version
	DOCKER_USER_ID=${HOST_USER_ID} DOCKER_NETWORK=${DOCKER_NETWORK} HOST_IP=${HOST_IP} PROJECT=${PROJECT} docker-compose -f docker/docker-compose.yml exec shietshow-web sh -c './composer.phar self-update'

docker-run-test:
	make docker-up-deamon
	rm -f build/Migration/Version/*.php
	make docker-install-deps
	make docker-create-db-dev
	make docker-generate-migration
	make docker-run-migrations
	make docker-generate-migration
	make docker-run-migrations
	make docker-generate-migration
	make docker-down

###############################
## Maintenaince
###############################

ENV ?= dev
clear-cache:
	rm -rf var/cache/${ENV}/*
	bin/console cache:clear --env=${ENV}

.reset-db: ## Recreate the dev database
	- GT_APP_ENV=dev bin/console doctrine:database:drop --force
	- rm -rf var/mysql/*
	- rm -rf var/mysql-files/*
	- GT_APP_ENV=dev bin/console doctrine:database:create
