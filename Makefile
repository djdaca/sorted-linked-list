.PHONY: up down test stan cs fix shell

DC := COMPOSE_PROJECT_NAME=sorted-linked-list docker-compose -f docker/docker-compose.yml

up:
	$(DC) up -d --build

install:
	$(DC) exec app composer install

start: up install

bash:
	$(DC) exec app bash

test:
	$(DC) exec app composer test

test-coverage:
	$(DC) exec app composer test:coverage

ci:
	$(DC) exec app composer ci

down:
	$(DC) down -v