install:
	composer install
#	php composer.phar install

test:
	./vendor/bin/phpunit tests

webserver:
	php -S localhost:8000