#!/usr/bin/make
# Makefile readme (ru): <http://linux.yaroslavl.ru/docs/prog/gnu_make_3-79_russian_manual.html>
# Makefile readme (en): <https://www.gnu.org/software/make/manual/html_node/index.html#SEC_Contents>

SHELL = /bin/sh

php_container_name := php
docker_bin := $(shell command -v docker 2> /dev/null)
docker_compose_bin := $(shell command -v docker-compose 2> /dev/null)
docker_compose_yml := Docker/docker-compose.yml
user_id := $(shell id -u)

.PHONY : help pull build push login test clean \
         app-pull app app-push\
         sources-pull sources sources-push\
         nginx-pull nginx nginx-push\
         up down restart shell install
.DEFAULT_GOAL := build

# --- [ Development tasks ] -------------------------------------------------------------------------------------------

build: ## Build container and install composer libs
	$(docker_compose_bin) --file "$(docker_compose_yml)" build
	$(docker_compose_bin) --file "$(docker_compose_yml)" run --rm -u $(user_id) "$(php_container_name)" composer install

shell: ## Runs shell in container
	$(docker_compose_bin) --file "$(docker_compose_yml)" run --rm -u $(user_id) "$(php_container_name)" /bin/bash

buildEntities: ## Build entities from yaml file with description
	$(docker_compose_bin) --file "$(docker_compose_yml)" run --rm -u $(user_id) "$(php_container_name)" php -f Resources/build/generate_entities.php
	$(docker_compose_bin) --file "$(docker_compose_yml)" run --rm -u $(user_id) "$(php_container_name)" vendor/bin/php-cs-fixer fix -q

test: ## Execute library tests
	$(docker_compose_bin) --file "$(docker_compose_yml)" run --rm -u $(user_id) "$(php_container_name)" vendor/bin/phpunit --configuration phpunit.xml.dist --coverage-html=Tests/coverage

fixer: ## Run fixes for code style
	$(docker_compose_bin) --file "$(docker_compose_yml)" run --rm -u $(user_id) "$(php_container_name)" vendor/bin/php-cs-fixer fix -v

linter: ## Run code checks
	$(docker_compose_bin) --file "$(docker_compose_yml)" run --rm -u $(user_id) "$(php_container_name)" vendor/bin/php-cs-fixer fix --config=.php_cs.dist -v --dry-run --stop-on-violation
	$(docker_compose_bin) --file "$(docker_compose_yml)" run --rm -u $(user_id) "$(php_container_name)" vendor/bin/phpcpd ./ --exclude vendor --exclude Tests --exclude Entity -v
	$(docker_compose_bin) --file "$(docker_compose_yml)" run --rm -u $(user_id) "$(php_container_name)" vendor/bin/psalm -c psalm.xml --show-info=false
	$(docker_compose_bin) --file "$(docker_compose_yml)" run --rm -u $(user_id) "$(php_container_name)" vendor/bin/phpunit --configuration phpunit.xml.dist
