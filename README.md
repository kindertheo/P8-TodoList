# ToDoList #
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/30955ec18a0940f180eb079d593b193e)](https://www.codacy.com/gh/kindertheo/P8-TodoList/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=kindertheo/P8-TodoList&amp;utm_campaign=Badge_Grade)
[![Maintainability](https://api.codeclimate.com/v1/badges/f94474dd93c5cd7925bc/maintainability)](https://codeclimate.com/github/kindertheo/P8-TodoList/maintainability)

## Description du besoin ##

### Corrections d'anomalies ###

### Une tâche doit être attachée à un utilisateur ###

Actuellement, lorsqu’une tâche est créée, elle n’est pas rattachée à un utilisateur. Il vous est demandé d’apporter les 
corrections nécessaires afin qu’automatiquement, à la sauvegarde de la tâche, l’utilisateur authentifié soit rattaché à 
la tâche nouvellement créée.

Lors de la modification de la tâche, l’auteur ne peut pas être modifié.

Pour les tâches déjà créées, il faut qu’elles soient rattachées à un utilisateur “anonyme”.
Choisir un rôle pour un utilisateur

Lors de la création d’un utilisateur, il doit être possible de choisir un rôle pour celui-ci. Les rôles listés sont les 
suivants :

  - rôle utilisateur (ROLE_USER) ;
  - rôle administrateur (ROLE_ADMIN).

Lors de la modification d’un utilisateur, il est également possible de changer le rôle d’un utilisateur.

### Implémentation de nouvelles fonctionnalités ###

#### Autorisation ####

Seuls les utilisateurs ayant le rôle administrateur (ROLE_ADMIN) doivent pouvoir accéder aux pages de gestion des 
utilisateurs.

Les tâches ne peuvent être supprimées que par les utilisateurs ayant créé les tâches en question.

Les tâches rattachées à l’utilisateur “anonyme” peuvent être supprimées uniquement par les utilisateurs ayant le 
rôle administrateur (ROLE_ADMIN).

#### Implémentation de tests automatisés ####

Il vous est demandé d’implémenter les tests automatisés (tests unitaires et fonctionnels) nécessaires pour assurer que 
le fonctionnement de l’application est bien en adéquation avec les demandes.

Ces tests doivent être implémentés avec PHPUnit ; vous pouvez aussi utiliser Behat pour la partie fonctionnelle.

Vous prévoirez des données de tests afin de pouvoir prouver le fonctionnement dans les cas explicités dans ce document.

## Installation ##

1. Clonez le repo :
```
    git clone https://github.com/kindertheo/P8-TodoList
```

2. Modifier le .env avec vos informations.
 
3. Installez les dependances :
```
    composer install
```
4. Mettre en place la BDD :
```
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate
    php bin/console doctrine:fixtures:load
```
ou 
```
    composer prepare
```
****