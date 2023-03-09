# les étapes à suivre
#1- accés au projet et installer les packages:
    a- cd back-end
    b- composer install

#2-configuration base de donne:

    Les paramètres de la connexion à la base de donne sont stockées dans la variable DATABASE_URL qui existe dans la fichier .env.
    Exemple:
    DATABASE_URL=‘mysql://db_user:db_password@127.0.0.1:3306/db_name’
    db_user: root
    db_password: par défaut vide 
    db_name: nom de votre base par exemple 'crud_symfony'

    DATABASE_URL='mysql://root:@127.0.0.1:3306/club_employe_database'

#3- création base de données :

    php bin/console doctrine:database:create

#4- Migrations: Création des tables / schémas de la base de données

    a- php bin/console make:migration 
    b- php bin/console doctrine:migrations:migrate 

#6- Exécution du fixture pour les etats des invitations

    php bin/console doctrine:fixtures:load

#6- Exécution du projet

    symfony server:start
