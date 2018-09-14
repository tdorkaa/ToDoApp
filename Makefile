default: help

build: ## Build docker compose
	@docker-compose build

up: ## Start containers
	docker-compose up -d

destroy: ## Destroys containers
	docker-compose down

stop: ## Stops containers
	docker-compose stop

help: ## This help message
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' -e 's/:.*#/: #/' | column -t -s '##'

ssh: ## SSH into web server container
	docker-compose exec webserver /bin/bash

ssh-test: ## SSH into web server container
	docker-compose exec test /bin/bash

phpunit:
	docker-compose exec test /bin/bash -l -c "cd Tests && ../vendor/bin/phpunit . --colors"

mysql: ## Opens mysql cli
	docker-compose exec mysql mysql -u academy -pacademy

composer-install: ## Runs composer install for sample_project
	docker-compose exec webserver /bin/bash -l -c "composer install && php vendor/bin/phinx migrate -e development"

migrate-dbs: ## Migrates databases
	docker-compose exec webserver /bin/bash -l -c "php vendor/bin/phinx migrate -e development && php vendor/bin/phinx migrate -e testing"

seed-dbs: ## Migrates databases
	docker-compose exec webserver /bin/bash -l -c "php vendor/bin/phinx seed:run -e development && php vendor/bin/phinx seed:run -e testing"

install: destroy build up composer-install migrate-dbs seed-dbs
