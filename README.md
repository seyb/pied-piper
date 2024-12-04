# Pied piper API

## Exercice

```
Pied piper est une société avec 500 salariés.
Afin d'agrémenter leur déjeuner, elle met à disposition des emplacements pour les food trucks. Quelques règles ont été mises en place pour assurer un roulement.
Du lundi au jeudi, huit emplacements sont mis à disposition.
Sept le vendredi.Chaque foodtruck ne peut réserver qu'un emplacement par semaine.Dans le but d'intégrer ces réservations au SIRH, Pied piper a besoin d'une API avec les endpoints suivants :
- Ajout d'une réservation avec une date, un nom
- Suppression d'une réservation
- Liste des réservations par jour.

Étonnamment, c'est le nom du foodtruck qui sert de clé d'unicité, malgré les réserves émises.
M. Hendricks (CTO) semble s'en contenter.--
```

## Hypothèse

Dans le cadre du MVP, nous considérons que le système n'est capable de gérer qu'une semaine calendaire.
En effet l'emploi du temps des foodtruck est peu changeant, il leur est donc plus simple d'avoir un emploi du temps fixe.
Si toutefois le foodtruck doit changer d'emploi du temps pour la semaine suivante, il est de sa responsabilité de 
supprimer sa réservation pour en refaire une.
Si la demande s'en fait sentir, le système pourra évoluer vers une solution plus ergonomique pour les foodtrucks.


## Tâches

### Initialiser l'environnement

- [x] Installer Symfony
- [x] Installer PHPUNIT
- [x] Configurer la CI
- [x] Configurer un Logger
- [ ] API authentication
- [ ] generate the API documentation

### Cas d'acceptation

#### Ajouter une réservation 

- [x] L'ajout nécessite une date et un nom de foodtruck
- L'ajout n'est pas possible quand :
  - [x] le foodtruck a déja une réservation sur cette semaine
  - [x] le quota de réservation pour le jour est dépassé
  - [ ] la date soumise est un samedi ou un dimanche => To check at the API level

#### Supprimer une réservation

- [ ] Supprime la réservation d'un foodtruck donné.
- [ ] Ne fait rien si le foodtruck n'a pas de réservation

#### Lister les réservations

Plusieurs solutions peuvent être envisagées pour lister en fonction des besoins des employés:
- [ ] Planning de la semaine
- [ ] Planning par jour
- [ ] Planning par foodtruck

Etant donné le nombre d'employés (500) et le faible taux de modifications du planning il serait utile de prévoir du
cache pour résister au pic de connexion à l'heure du déjeuner.
- [ ] Cache get requests