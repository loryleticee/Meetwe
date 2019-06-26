USERID=$(shell id -u)
GROUPID=$(shell id -g)

CONSOLE=php bin/console
FIG=docker-compose
HAS_DOCKER:=$(shell command -v $(FIG) 2> /dev/null)

ifdef HAS_DOCKER
	ifdef APP_ENV
		EXECROOT=$(FIG) exec -e APP_ENV=$(APP_ENV) app
		EXEC=$(FIG) exec -e APP_ENV=$(APP_ENV) -u $(USERID):$(GROUPID) app
	else
		EXECROOT=$(FIG) exec app
		EXEC=$(FIG) exec -u $(USERID):$(GROUPID) app
	endif
else
	EXECROOT=
	EXEC=
endif

.DEFAULT_GOAL := help

.PHONY: help ## Generate list of targets with descriptions
help:
		@grep '##' Makefile \
		| grep -v 'grep\|sed' \
		| sed 's/^\.PHONY: \(.*\) ##[\s|\S]*\(.*\)/\1:\t\2/' \
		| sed 's/\(^##\)//' \
		| sed 's/\(##\)/\t/' \
		| expand -t14

##
## Project setup & day to day shortcuts
##---------------------------------------------------------------------------

.PHONY: start ## Start the project (Install in first place)
start:
start: docker-compose.override.yml
	$(FIG) pull || true
	$(FIG) build
	$(FIG) up -d
	composer install
	$(EXEC) $(CONSOLE) doctrine:database:create --if-not-exists
	$(EXEC) $(CONSOLE) doctrine:schema:update --force
	$(EXEC) $(CONSOLE) make:migration
	$(EXEC) $(CONSOLE) hautelook:fixtures:load -q

.PHONY: start-mounia
start-mounia:
	composer install
	$(CONSOLE) doctrine:database:create --if-not-exists
	$(CONSOLE) doctrine:schema:update --force
	$(CONSOLE) make:migration
	$(CONSOLE) hautelook:fixtures:load -q
	$(CONSOLE) server:run

.PHONY: stop ## stop the project
stop:
stop:
	$(FIG) down

.PHONY: cc ## Clear the cache in dev env
cc: perm
	$(EXECROOT) rm -rf var/cache/*
	$(EXEC) $(CONSOLE) cache:clear --no-warmup
	$(EXEC) $(CONSOLE) cache:warmup

.PHONY: exec ## Run bash in the app container
exec:
	$(EXEC) /bin/bash

.PHONY: tests ## Make code Tests
tests:
	$(EXEC) vendor/bin/phpcs src/
	$(EXEC) vendor/bin/phpstan analyse --level=6 src

.PHONY: fix ## Fix error code test
fix:
	$(EXEC) vendor/bin/phpcbf src/


##
## Dependencies Files
##---------------------------------------------------------------------------

docker-compose.override.yml: docker-compose.override.yml.dist
	$(RUN) cp docker-compose.override.yml.dist docker-compose.override.yml
