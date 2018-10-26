# DOCKER TASKS
start: ## Start the pre-built environment.
	docker-compose up -d --build

stop: ## Stops/pause the environment.
	docker-compose stop

install: ## Builds the environment for the first and starts it
	docker-compose up -d --build
	docker-compose exec php-fpm composer install
	docker-compose exec php-fpm cp .env.example .env
	docker-compose exec php-fpm php artisan key:generate
	docker-compose exec php-fpm php artisan migrate:fresh
	docker-compose exec php-fpm php artisan db:seed --no-interaction
	docker-compose exec db mysql -uroot -proot -e 'CREATE DATABASE IF NOT EXISTS youtubeapi_test;'
	docker-compose exec db mysql -uroot -proot -e "GRANT ALL PRIVILEGES ON youtubeapi_test.* TO 'youtubeapi'@'%';"
	docker-compose exec php-fpm php artisan migrate:fresh --database=mysql_test

destroy: ## Destroy and clean the environment.
	docker-compose down --rmi all --volumes --remove-orphans

down: ## Remove containers
	docker-compose down

# HELP
# This will output the help for each task
# thanks to https://marmelab.com/blog/2016/02/29/auto-documented-makefile.html
.PHONY: help

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'
