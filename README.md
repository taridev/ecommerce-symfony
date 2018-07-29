# ecommerce-symfony

## Requirement :
- PHP 7.1 ou supérieur
- [Composer](https://getcomposer.org/download/)

## Initialisation :
- git clone
- composer install
- remplir app/config/parameter.yml avec les infos de votre base de données
- php app/console doctrine:schema:create
- php app/console doctrine:fixtures:load
- php app/console server:start
- le site est disponible à l'adresse [http://localhost:8000/](http://localhost:8000/).
- un compte est créé, __login__ : client, __mdp__ : password