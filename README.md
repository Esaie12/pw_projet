# AdopteUnDev

## Développeurs ( Etudiant )
- BOGNON Martial Freddy
- COULIBALY Karidja
-  KOCORA Roxane
-  OMIYALE Esaie A.
  
Retrouver ici le projet *AdoupteUnDev* , Il s'agit d'une application web permettant aux développeurs et aux entreprises de créer des profils ou des fiches de postes et de les faire correspondre, à la manière d'un site de rencontre. L'objectif est d'optimiser la mise en relation entre développeurs et recruteurs via des fonctionnalités conviviales et innovantes.


## Fonctionnalités

- Connexion et inscription ( entant que Développeur ou Société )
- Coté Dev:
    * Voir les offres et des autres développeurs
    * Ajouter des offres en favories
    * Voir des offres compatible à son profil ( système de matching )
    * Postuler à des offres
    * Echanger via un système de messagerie avec des sociétés ou des developpeur
    * Noter un développeur
    * Accéder à son dashboard
 
- Coté société
    * Publier et gérer des offres
    * Gerer les candidatures et les recrutements lié à chacune de ses offres
    * Système de matching : Voir des développers qui matchent avec les offres publiés
    * Ajouter en favoris un dev
    * Echanger via un système de messagerie avec des sociétés ou des developpeur
    *  Accéder à son dashboard

## Installation et configuration

### 1. *Prérequis*
- Docker
- Composer
- Symfony CLI

### 2. *Étapes d'installation*

- Clonez ce dépôt sur votre machine locale :  git clone [git@github.com:Esaie12/pw_projet.git](https://github.com/Esaie12/pw_projet)
- Lancer docker bash : docker exec -it symfony_app bash
- Installer les dépendances : composer install
- Créer les tables dans la basse de donnnée : php bin/console doctrine:migrations:migrate
- Lancer des fixtures pour préalablement remplis sa bdd :  php bin/console doctrine:fixtures:load
