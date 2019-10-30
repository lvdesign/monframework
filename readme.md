# framework by Graphikart

https://www.grafikart.fr/tutoriels/structure-projet-918



# Commandes pour le framework


### Installation de Composer

- non global:
php composer.phar init

- recharger Composer apres modif/ajout ds Composer
php composer.phar dump-autoload

- mise a jour
php composer.phar update

### Server

ENV=dev php -S localhost:8000 -d display_errors=1 -t public/

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

./vendor/bin/phpunit tests/Framework/Twig/FormExtensionTest.php --colors

./vendor/bin/phpunit tests/Blog/Table/PostTableTest.php --colors

- Flash messages
./vendor/bin/phpunit tests/Framework/Session/FlashServiceTest.php --colors


- Tables
./vendor/bin/phpunit tests/Framework/Database/TableTest.php --colors

./vendor/bin/phpunit tests/Framework/ValidatorTest.php --colors


- Middleware 
./vendor/bin/phpunit tests/Framework/Middleware/MethodMiddlewareTest.php  --colors

./vendor/bin/phpunit tests/Framework/Middleware/CsrfMiddlewareTest.php  --colors

- Query
./vendor/bin/phpunit tests/Framework/Database/QueryTest.php  --colors


- Upload
./vendor/bin/phpunit tests/Framework/UploadTest.php



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

- fichier cree Dans dossier App\ Blog\ db\ migations\

- https://phinx.org/

php composer.phar require robmorgan/phinx --dev

- migration
./vendor/bin/phinx migrate -e development

- create Table
./vendor/bin/phinx create CreatePostsTable

./vendor/bin/phinx create AddCategoryTable

- table liaison Post -> Cat
./vendor/bin/phinx create AddCategoryIdToPost

- migrate Table
./vendor/bin/phinx migrate

- reconstruire Table
./vendor/bin/phinx rollback -t 0


- Creation et ajout ds table
./vendor/bin/phinx create
./vendor/bin/phinx migrate






- SEEDS [command](https://book.cakephp.org/3.0/en/phinx/commands.html)

vendor/bin/phinx seed:create PostSeeder

./vendor/bin/phinx seed:run          (// -e development

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


## flatpickr dateTime 
- https://cdnjs.com/libraries/flatpickr




## Whoops gestions erreur avec ce midleware
php composer.phar require middlewares/whoops

## Recadrage image  intervention/image
- https://github.com/Intervention/image
php composer.phar require intervention/image










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

- Validation des données
Il y a une règle importante à respecter lorsque l'on développe un site internet :
__Never trust user input__
On ne peut pas laisser l'utilisateur remplir le blog n'importe comment. On va devoir valider les informations entrées dans l'administration à l'aide d'une classe dédiée.

- Simplifier les formulaires
Les vues de notre administrations restent relativement lourde à gérer avec tout le code HTML à faire pour générer un formulaire. Nous allons simplifier une partie de ce code en utilisant une classe dédiée que l'on créera sous forme d'extension Twig.

- Les catégories
Lister des articles c'est bien, mais on souhaite pouvoir les classer dans des catégories afin de les organiser plus facilement. Nous allons voir ici comment se reposer sur ce que l'on a déjà fait afin de réduire la quantité de code à écrire.

- Front catégories
Maintenant que la gestion des catégories est en place nous allons mettre à jour le front afin de permettre aux utilisateurs de n'afficher que les articles appartenant à une certaine catégorie.

- Dashboard d'administration
La page d'accueil de l'administration devra afficher des informations provenant de divers sources. Nous allons donc mettre en place un système de "widgets" qui permettra à chaque module de venir injecter un bloc HTML sur le dashboard d'administration.

-Tout middleware !
Nous allons nettoyer une partie du code de notre application en séparant la logique dans des middlewares réutilisables. Et Update PSR15.

-Faille CSRF (erreur Session)
Nous allons dans ce chapitre nous prémunir contre les failles CSRF. CSRF, pour Cross-Site Request Forgery consiste à faire éxécuter une requête HTTP falsifiée à un utilisateur afin de le rediriger vers une action interne au site.

-Et les performances ?
Et les performances dans tout ça ? Est-ce qu'à force de découper en plein de classe on n'a pas rendu notre site "lent" ? Dans ce chapitre nous allons voir comment optimiser notre code, mais aussi la configuration de PHP afin d'améliorer les performances de notre application.

- Créons un Query Builder
Créer les requêtes sous forme de simple chaine de caractère peut suffir pour un petit projet. En revanche, lorsque le projet grandit, concevoir nos requête sous forme d'objet peut nous permettre de les composer et les réutiliser. Nous verrons dans ce chapitre comment la création d'un QueryBuilder peut simplifier le travail.


- Hydrater les entités
Lorsque l'on récupère les données nous allons chercher à les représenter sous forme d'objet pour mieux nous organiser. PDO permet déjà de faire cela avec le mode FETCH_CLASS. En revanche, l'hydratation se fait de manière un peu particulière car PDO va instancier l'objet, le remplir avec les champs issus de la base de données puis appeler le constructeur. Nous souhaitons utiliser une méthode d'hydratation plus classique.