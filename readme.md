# framework by Graphikart

https://www.grafikart.fr/tutoriels/structure-projet-918



# Commandes pour le framework


### Installation de Composer

- non global:
php composer.phar init

- recharger Composer apres modif/ajout ds Composer
php composer.phar dump-autoload


### Server

php -S localhost:8000 -d display_errors=1 -t public/


## COMPOSANTS UTISES

### PhpUnit pour les tests

php composer.phar require --dev phpunit/phpunit // 7.5


### psr7 request/response sur packagiste

- PSR-7 message implementation that also provides common utility methods
php composer.phar require guzzlehttp/psr7

### psr7- lecture response

php composer.phar require http-interop/response-sender


### code sniffer pour bon code

- php composer.phar require squizlabs/php_codesniffer
- pour tester
./vendor/bin/phpcs src/Framework/App.php

- avec xml 
./vendor/bin/phpcs

- correction direct
./vendor/bin/phpcbf

### code php unit

- avec xml commande, exemples : 

./vendor/bin/phpunit tests/Framework/RendererTest.php --colors
./vendor/bin/phpunit tests/Framework/Renderer/PHPRendererTest.php --colors

### les deux en meme temps phpcs et php unit

 ./vendor/bin/phpcs; ./vendor/bin/phpunit

 
 
 ### TWIGG
 
php composer.phar require twig/twig:2.7

twig/twig:^2.0

### GIT - pour retourner a precedente version
git status
git reset --hard HEAD


### Router lecture et match route
php composer.phar require zendframework/zend-expressive-fastroute

### php DI (injection de dependance) PSR-11

- http://php-di.org/doc/migration/6.0.html

php composer.phar require php-di/php-di


# Etapes

- Structure du projet
Dans ce premier chapitre nous allons poser la structure de notre projet et parler de deux objet qui seront essentiel tout au long de cette mise en pratique : l'objet Request et l'objet Response. L'objet Request nous permettra de représenter, sous forme d'objet, la requête faite auprès du server et pourra être utiliser dans de nombreuses classes au sein de notre application. L'objet Response, comme son nom l'indique, permettra de représenter la réponse que l'on renverra à l'utilisateur à la fin de l'éxécution de notre script.

- Router
On souhaite pour notre code contrôler un maximum de choses depuis notre code PHP. Plutôt que de créer un fichier PHP par page et d'utiliser la réécriture d'URL pour sélectionner la page à charger nous allons mettre en place un router. Le but de cette classe là sera de détecter le format de l'URL et d'appeller la bonne action en fonction.

Attention, afin d'éviter les problèmes utilisez 
la version 1.2.0 zend-expressive-fastroute
composer require zendframework/zend-expressive-fastroute:1.2.0

- Renderer
Afin de générer le code HTML pour nos pages nous allons créer une classe qui va nous permettre de gérer nos "vues". Le principe est ici d'éviter d'avoir à inclure manuellement les fichiers.

- Twig
Afin d'améliorer encore notre système de "vue" nous allons utiliser le moteur de template Twig. Ce moteur de template va permettre une meilleur séparation de la logique et sera aussi plus facil à modifier par une personne non familière avec PHP.

- Injections de dependances
Comme nous l'avons vu lors de la mise en place de Twig, certaines classes ont des dépendances qu'il faut satisfaire et on se retrouve à passer nos instances de classe en classe. Afin de nous simplifier la tâche nous allons utiliser un système de container.
