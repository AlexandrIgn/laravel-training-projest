install:
	composer install
lint:
	composer run-script phpcs -- --standard=PSR12 routes app/Providers app/Services app/Entity app/Http app/Region.php  tests
run:
	php artisan serve
test:
	composer run-script phpunit tests