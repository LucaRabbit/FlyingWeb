Flying Web – Application de gestion de vols et de réservations
----------------------
Présentation du projet
----------------------

Flying Web est une application web destinée à la compagnie aérienne fictive Flying Blue.

Elle permet: 
- à l’équipe interne de gérer les avions, les aéroports, les vols et les réservations,
- aux clients de rechercher des vols, réserver, consulter leurs billets, consulter et gérer leurs réservations.

Le système génère automatiquement des cartes d’embarquement PDF avec QR Code.
L’application est développée en PHP selon une architecture MVC.

----------------------
Architecture du projet
----------------------
flying_web/
- app/
	- Controller/
		- admin/ (contrôleurs du back-office)
		- front/ (contrôleurs du front-office)
	- Core/ (mini-framework MVC : Router, Controller, Model, View)
	- Models/ (modèles métier : Avion, Aeroport, Vol, Reservation, Passager)
	- Views/ (vues front, back, partials)
- config/ (configuration de la base de données et .env)
- public/ (routeur, assets, .htaccess, fichiers temporaires)
- vendor/ (dépendances Composer)

---------------------------
Fonctionnalités principales
---------------------------
Back-office (administration):
- Authentification sécurisée
- Gestion des avions (CRUD)
- Gestion des aéroports (CRUD)
- Planification des vols
- Vérification compatibilité avion/piste
- Vérification disponibilité avion
- Gestion du cycle de vie d’un vol : décollage, atterrissage, annulation
- Consultation des passagers d’un vol
- Verrouillage des réservations après décollage

Front-office (clients):
- Recherche de vols (avec tolérance ± X jours)
- Proposition automatique de vols retour
- Réservation d’un vol
- Nombre de passagers
- Informations passagers
- Email du réservant
- Envoi automatique d’un email contenant :
	- un lien unique sécurisé (token) sous forme QRCode
	- la carte d’embarquement PDF
- Gestion de la réservation via lien token :
	- consultation
	- modification (si vol non décollé)
	- annulation

---------------
Base de données
---------------
Tables principales :
- AVION
- AEROPORT
- VOL
- RESERVATION
- PASSAGER
- RESERVATION_PASSAGER
- ADMIN
- LOGS

Chaque table correspond à un modèle dans app/models/.

----------------------
Technologies utilisées
----------------------
- PHP 8+
- MySQL
- Composer
- HTML / CSS / JavaScript
- TCPDF (PDF et QRCode)
- Apache (.htaccess)

------------
Installation locale
------------
- Installer les dépendances
composer install

- Importer la base de données flyingweb.sql

- Configurer Apache pour pointer vers public/index.php

- Paramêtrer /config/.env pour pouvoir tester l'envoi d'email

-------
Routing
-------
Le routeur (app/core/Router.php) gère les routes du type :
- /admin/avion/create
- /front/recherche
- /reservation/gestion?token=xxxx

-------------------------
Génération PDF et QR Code
-------------------------
Lors d’une réservation :
- un token unique est généré
- un QR Code contenant l’URL de gestion est créé
- une carte d’embarquement PDF est générée
- un email est envoyé au client

--------
Sécurité
--------
- Mots de passe hashés
- Tokens aléatoires sécurisés
- Vérification des droits d’accès Back-office
- Filtrage des entrées utilisateur

Auteur : Luca Ramampy
Projet réalisé dans le cadre du module INFDIET9 – Projet Web Dynamique.

