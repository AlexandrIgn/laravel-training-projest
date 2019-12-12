install:
	composer install
lint:
	composer run-script phpcs -- --standard=PSR12 public routes bootstrap app/Http  tests
run:
	php artisan serve
logs:
	tail -f storage/logs/.log
test:
	composer run-script phpunit tests