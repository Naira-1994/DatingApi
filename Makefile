#Запустить проект
init: down rebuild create-env composer-install key-generate  migrate-db

up:
	docker-compose -f docker-compose.yml up -d
down:
	docker-compose -f docker-compose.yml down
rebuild:
	docker-compose -f docker-compose.yml up -d --build
create-env:
	docker exec -t dating-api-php cp .env.example .env
migrate-db:
	docker exec -t dating-api-php php artisan migrate
composer-install:
	docker exec -t dating-api-php composer install
key-generate:
	docker exec -t dating-api-php php artisan key:generate
