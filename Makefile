PHP_CONTAINER_NAME=api-project.app

up:
	docker-compose up

down:
	docker-compose down

upd:
	docker-compose up -d

bash b:
	docker exec -it "${PHP_CONTAINER_NAME}" bash

composer-install:
	docker exec -it "${PHP_CONTAINER_NAME}" sh -c 'composer install'

composer-dump-autoload:
	docker exec -it "${PHP_CONTAINER_NAME}" sh -c 'composer dump-autoload'

phpstan:
	docker exec -it "${PHP_CONTAINER_NAME}" sh -c 'vendor/bin/phpstan analyse -c phpstan.neon'

cs:
	docker exec -it "${PHP_CONTAINER_NAME}" sh -c 'vendor/bin/ecs'

csf:
	docker exec -it "${PHP_CONTAINER_NAME}" sh -c 'vendor/bin/ecs --fix'

delete-cache dc:
	docker exec -it "${PHP_CONTAINER_NAME}" sh -c 'rm -rf temp/cache/*'

db-migrate:
	docker exec -it "${PHP_CONTAINER_NAME}" sh -c 'vendor/bin/phinx migrate'

run-tests:
	docker exec -it "${PHP_CONTAINER_NAME}" sh -c 'vendor/bin/tester -d extension=tokenizer tests/'
