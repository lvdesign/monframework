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

- avec xml commande, exemples 

./vendor/bin/phpunit tests/Framework/RendererTest.php --colors
./vendor/bin/phpunit tests/Framework/Renderer/PHPRendererTest.php --colors

 ./vendor/bin/phpunit Tests/Blog/Actions/BlogActionsTest.php --colors

 ./vendor/bin/phpunit tests/Framework/RouterTest.php --colors

- Twig extension
./vendor/bin/phpunit tests/Framework/Twig/TextExtensionTest.php --colors
./vendor/bin/phpunit tests/Framework/Twig/TimeExtensionTest.php --colors

./vendor/bin/phpunit tests/Blog/Table/PostTableTest.php --colors

- flash messages
./vendor/bin/phpunit tests/Framework/Session/FlashServiceTest.php --colors

./vendor/bin/phpunit tests/Framework/ValidatorTest.php --colors

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


### Installation de Phinx (pour migration BD)
- https://phinx.org/

php composer.phar require robmorgan/phinx --dev

- migration
vendor/bin/phinx migrate -e development

- create Table
vendor/bin/phinx create CreatePostsTable

- migrate Table
vendor/bin/phinx migrate

- SEEDS [command](https://book.cakephp.org/3.0/en/phinx/commands.html)

vendor/bin/phinx seed:create PostSeeder

vendor/bin/phinx seed:run          (// -e development

- faker (https://packagist.org/packages/fzaninotto/faker)

php composer.phar require --dev fzaninotto/faker



## pagination

- https://github.com/whiteoctober/Pagerfanta

php composer.phar require pagerfanta/pagerfanta

## bootsrap 4
php composer.phar require twbs/bootstrap:4.0.0

## timeago js
- https://timeago.org/

cdnjs.cloudflare.com
timeago().render(document.querySelectorAll('.need_to_be_rendered'));


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

- Les migrations
Pour gérer notre système de blog, il va nous falloir sauvegarder les articles dans une base de données. On utilisera pour cela une base de données MySQL qu'il va nous falloir préparer en amont en créant les différentes tables.

- Récupération des articles
Maintenant que nos tables sont prêtes, nous allons mettre en place les classes qui nous permettrons d'intéragir avec ces-dernières. On séparera ici les choses en 2 classes :

La class PostTable sera chargée de faire les requêtes et de récupérer les enregistrements.
Tandis que la class Post permettra de représenter un enregistrement.

- Pagination
Sur la page d'accueil, mais aussi dans la partie administration, on sera amené à paginer nos articles. On se basera sur la librairie PagerFanta afin de créer un système de pagination compatible avec ce qui a déjà été mis en place et on utilisera les extensions twig pour se créer une méthode simple pour les vues.

- Tester la base de données
Tout au long de cette formation on s'efforce de tester le code que l'on écrit. En revanche comment faire pour tester les requêtes SQL que l'on écrit ?

- Administration du blog
Dans ce chapitre nous allons mettre en place la partie administration du blog avec la gestion de la création, l'édition et la suppression d'articles.

- Messages flash
Lorsqu'un article est modifié, ou supprimé l'utilisateur est redirigé vers le listing d'articles. En revanche, il faut confirmer l'action auprès de l'utilisateur en lui affichant un message.