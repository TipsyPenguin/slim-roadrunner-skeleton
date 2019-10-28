init: build up

build: ## Start all or c=<name> containers in foreground
	docker-compose -f docker-compose.yml build

up: composer-install ## Start all or c=<name> containers in foreground
	docker-compose -f docker-compose.yml up $(c)

start: ## Start all or c=<name> containers in background
	docker-compose -f docker-compose.yml  up -d $(c)

stop: ## Stop all or c=<name> containers
	docker-compose -f docker-compose.yml stop $(c)

status: ## Show status of containers
	docker-compose -f docker-compose.yml  ps

restart: ## Restart all or c=<name> containers
	docker-compose -f docker-compose.yml  stop $(c)
	docker-compose -f docker-compose.yml up  $(c) -d

composer-install: ## install dependencies
	docker-compose run composer install -d /app/worker
	docker-compose run composer install -d /app/src
