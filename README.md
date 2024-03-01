# Agence de super héros - Back-end
Projet scolaire visant à créer un site type "Wikipedia" permettant à l'utilisateur de créer ses superhéros, les modifer et supprimer, ainsi de de consulter les autres superhéros.

> Ce dépôt ne contient que le back-end du site, il vous faudra cloner aussi le dépôt suivant : [Front-end](https://github.com/NockIA/agence_superhero_front_groupe2)

### Lancement serveur

- `cd ./laravelux`

- `composer install`

- glisser le .env dans le dossier laravelux

- `php db:wipe` (Pour être sur que la base de donnée est bien vide)

- `php artisan migrate`

- `php artisan db:seed`

- Changez dans le fichier .env `DB_DATABASE=./database/database.db` --> `DB_DATABASE=../database/database.db`

- `php artisan serve`

> Le serveur est lancé
### Technologies

 - Php
 - Laravel
 - SQLite
### Membres
 - [Lucas B](https://github.com/Ylucasb) (Back-end)   
 - [Kevin C](https://github.com/NockIA) (Front-end)  
 -  Dorian M (Database)

