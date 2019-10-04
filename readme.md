# framework by Graphikart

https://www.grafikart.fr/tutoriels/structure-projet-918



## command

php composer.phar init

php composer.phar require --dev phpunit/phpunit // 7.5


### Server
php -S localhost:8000 -d display_errors=1 -t public/


###  psr7 request/response sur packagiste
PSR-7 message implementation that also provides common utility methods
php composer.phar require guzzlehttp/psr7

### psr7- lecture response
php composer.phar require http-interop/response-sender


### code sniffer pour bon code
php composer.phar require squizlabs/php_codesniffer
- pour tester
./vendor/bin/phpcs src/Framework/App.php