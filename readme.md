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
- Un peu d'avance a été prise sur l'exercice 8 (page d'accueil = liste des dossiers). Il sera complété en temps venu.

## Exercice 3
- Bien que non prévu pour l'exercice, une liberté à été prise et il est ainsi possible de créer des dossiers avant de créer une écriture dans celui-ci.

## Exercice 4 
- Interversion des données en page d'accueil, elle affiche maintenant la liste globale des écritures comptables.
- Un lien d'accès vers le dossier associé à l'écriture a été ajouté pour pouvoir réaccéder à celui-ci et ainsi y ajouter une nouvelle écriture. L'affichage sera modifié plus tard pour que l'intitulé du dossier apparaisse plutôt que son uuid.
- La liste des écritures affairantes à ce dossier a été ajoutée par la même occasion.

## Exerice 5
- Une fenêtre modale a été créée plutôt qu'une page pour la création et l'édition d'une écriture. Le formulaire est géré de manière asynchrone

## Exerice 7
- Utilisation de la librairie datatable pour ordonner les colonnes. JQuery requis. CDN utilisé pour récupérer ces dépendances.
- Fix sur le mode d'utilisation de CDN et retrait de librairie inutile

## Exerice 8
- Bien que non demandé, ajout des valeurs de crédit et de débit en plus du solde du dossier sur la page de liste de ceux-ci.