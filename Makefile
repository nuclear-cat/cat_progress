start: docker-up
stop: docker-down
test: cat-progress-test
init: \
	docker-down-clear \
	docker-pull \
	docker-build \
	cat-progress-composer-install \
	docker-up \
	cat-progress-wait-postgres \
	cat-progress-drop \
    cat-progress-migrate \
    cat-progress-load-fixtures \
    cat-progress-test

load-fixtures: cat-progress-load-fixtures
migrate: cat-progress-migrate
test: cat-progress-test
deploy:
	docker-compose -f docker-compose-prod.yaml down -v --remove-orphans
	docker-compose -f docker-compose-prod.yaml pull
	docker-compose -f docker-compose-prod.yaml build
	docker-compose -f docker-compose-prod.yaml run --rm cat-progress-php-cli composer install --no-dev --optimize-autoloader
	docker-compose -f docker-compose-prod.yaml up -d
    until docker-compose -f docker-compose-prod.yaml run --rm cat-progress-php-cli bin/console app:check-db-connection ; do sleep 1 ; done
	docker-compose -f docker-compose-prod.yaml --rm cat-progress-php-fpm php bin/console doctrine:migrations:migrate --no-interaction

deploy-vscale:
	composer install --no-dev --optimize-autoloader
	yarn build

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build

cat-progress-composer-install:
	docker-compose run --rm cat-progress-php-cli composer install

cat-progress-load-fixtures:
	docker-compose run --rm cat-progress-php-fpm php bin/console doctrine:fixtures:load --no-interaction --no-debug

cat-progress-wait-postgres:
	until docker-compose run --rm cat-progress-php-cli bin/console app:check-db-connection ; do sleep 1 ; done

cat-progress-drop:
	docker-compose run --rm cat-progress-php-fpm php bin/console doctrine:schema:drop --force

cat-progress-migrate:
	docker-compose run --rm cat-progress-php-fpm php bin/console doctrine:migrations:migrate --no-interaction

cat-progress-test:
	docker-compose run --rm cat-progress-php-cli bin/console doctrine:database:drop --if-exists --force --env=test
	docker-compose run --rm cat-progress-php-cli bin/console doctrine:database:create --env=test
	docker-compose run --rm cat-progress-php-fpm php bin/console doctrine:migrations:migrate --no-interaction --env=test
	docker-compose run --rm cat-progress-php-cli bin/console doctrine:fixtures:load --env=test --no-interaction
	docker-compose run --rm cat-progress-php-cli bin/phpunit

php-cli-version:
	docker-compose run --rm cat-progress-php-cli php -v

generate-jwt-keys:
	openssl genrsa -out var/jwt/private.pem -aes256 4096
	openssl rsa -pubout -in var/jwt/private.pem -out var/jwt/public.pem

generate-jwt-encryption-key:
	php -r 'echo base64_encode(random_bytes(32)), PHP_EOL;'
