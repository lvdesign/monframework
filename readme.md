# framework by Graphikart

https://www.grafikart.fr/tutoriels/structure-projet-918



## command

php composer.phar init

php composer.phar require --dev phpunit/phpunit // 7.5

- recharger Composer apres modif/ajout ds Composer
php composer.phar dump-autoload


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

- avec xml 
./vendor/bin/phpcs

- correction direct
./vendor/bin/phpcbf

### code php unit
php composer.phar require --dev phpunit/phpunit // 7.5

- avec xml commande :

### les deux en meme temps phpcs et php unit
 ./vendor/bin/phpcs; ./vendor/bin/phpunit

### GIT - pour retourner a precedente version
git status
git reset --hard HEAD


### Router lecture et match route
php composer.phar require zendframework/zend-expressive-fastroute