# Description des actions

- ajout de apache-pack pour compatibilité local (composer req symfony/apache-pack)
- ajout des annotations (composer req annotations)
- ajout du bundle uuid (composer require symfony/uid)

- retrait de doctrine migration (composer remove doctrine/doctrine-migrations-bundle)
- retrait de doctrine orm (composer remove doctrine/orm)

## Exercice 1
- Les paramètres sous ## CUSTOM DATABASE MANAGEMENT dans le fichier .env doivent être remplis. Un fichier .env.local peut également être créé.
- Des commandes sont à exécuter en se plaçant à la racine du projet (le répertoire contenant le présent readme)
    - $ php bin/console app:database:create (app:database:delete existe aussi)
    - $ php bin/console app:schema:create
    - $ php bin/console app:fixtures:load

## Exercice 2
- Création de la page liste d'écritures d'un dossier, selon son uuid. Une donnée fantoche est utilisée pour le moment.
- Un peu d'avance a été pris sur l'exercice 8 (page d'accueil = liste des dossiers). Il sera complété en temps venu.