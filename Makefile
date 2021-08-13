-include .env
-include .env.local

-include .colors

app_dir	:= tchevalleraud_symfony-docker-project

user	:= $(shell id -u)
group	:= $(shell id -g)

ifeq ($(APP_ENV), prod)
	dc := USER_ID=$(user) GROUP_ID=$(group) docker-compose -f docker-compose.prod.yaml -p $(app_dir)_$(APP_ENV)
else ifeq ($(APP_ENV), dev)
	dc := USER_ID=$(user) GROUP_ID=$(group) docker-compose -f docker-compose.dev.yaml -p $(app_dir)_$(APP_ENV)
endif

dr	:= $(dc) run --rm
de	:= $(dc) exec

node	:= $(dr) node
php		:= $(dr) --no-deps apache
sy		:= $(php) php bin/console

help:
	@echo "################################"
	@echo "# HELP / ENV. " $(APP_ENV)
	@echo "################################"
	@$(call cyan,"cache-clear")
	@$(call cyan,"docker-build") : allows the construction of the project images
	@$(call cyan,"doctrine-database-create")
	@$(call cyan,"doctrine-database-drop")
	@$(call cyan,"doctrine-fixtures-load")
	@$(call cyan,"public/assets")
	@$(call cyan,"public/assets-dev")
	@$(call cyan,"server-restart")
	@$(call cyan,"server-start") : allows you to start the server
	@$(call cyan,"server-stop") : allows to stop the server
	@$(call cyan,"test-screenshot")
	@$(call cyan,"test-unit-all")
	@$(call cyan,"vendor/autoload.php")

cache-clear:
	$(sy) cache:clear

docker-build:
	$(dc) build

doctrine-database-create:
	$(sy) doctrine:database:create -c mysql --if-not-exists
	$(sy) doctrine:database:create -c local
	$(sy) doctrine:schema:update --force --em mysql

doctrine-database-drop:
	$(sy) doctrine:database:drop -c mysql --force --if-exists
	$(sy) doctrine:database:drop -c local --force

doctrine-fixtures-load:
	$(sy) doctrine:fixtures:load --no-interaction

public/assets:
	$(node) yarn
	$(node) yarn run build

public/assets-dev:
	$(node) yarn
	$(node) yarn run dev

server-restart:
	$(dc) restart

server-start:
	$(dc) up -d

server-stop:
	$(dc) down

test-screenshot:
	$(php) php bin/phpunit --testsuite Screenshot

test-unit-all:
	$(php) php bin/phpunit --exclude-group screenshot --testdox

vendor/autoload.php: composer.lock
	$(php) composer update