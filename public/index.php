<?php
session_start();

// Autoload Composer
require __DIR__ . '/../vendor/autoload.php';

// Chargement de .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../config');
$dotenv->load();

// Imports
use App\Core\Router;
use App\Controllers\admin\AdminController;
use App\Controllers\admin\AvionController;
use App\Controllers\admin\AeroportController;
use App\Controllers\admin\VolController;
use App\Controllers\admin\ReservationController;

use App\Controllers\front\FrontController;
use App\Controllers\front\FrontVolController;
use App\Controllers\front\FrontReservationController;

// Router
$router = new Router();

// Page d'accueil
$router->get('/', [\App\Controllers\HomeController::class, 'index']);

// ADMIN
// Dashboard
$router->get('/admin/dashboard', [\App\Controllers\admin\AdminController::class, 'dashboard']);
$router->get('/admin', [\App\Controllers\admin\AdminController::class, 'dashboard']);

// Authentification ADMIN
$router->get('/admin/login', [\App\Controllers\admin\BackAuthController::class, 'loginForm']);
$router->post('/admin/login', [\App\Controllers\admin\BackAuthController::class, 'login']);
$router->get('/admin/logout', [\App\Controllers\admin\BackAuthController::class, 'logout']);

// Logs
$router->get('/admin/logs', [\App\Controllers\admin\LogController::class, 'index']);

// Avion
$router->get('/admin/avion', [AvionController::class, 'index']);
$router->get('/admin/avion/show/{id}', [AvionController::class, 'show']);
$router->get('/admin/avion/create', [AvionController::class, 'create']);
$router->post('/admin/avion/create', [AvionController::class, 'store']);
$router->get('/admin/avion/edit/{id}', [AvionController::class, 'edit']);
$router->post('/admin/avion/update/{id}', [AvionController::class, 'update']);
$router->post('/admin/avion/delete/{id}', [AvionController::class, 'delete']);

// Aéroport
$router->get('/admin/aeroport', [AeroportController::class, 'index']);
$router->get('/admin/aeroport/show/{id}', [AeroportController::class, 'show']);
$router->get('/admin/aeroport/create', [AeroportController::class, 'create']);
$router->post('/admin/aeroport/create', [AeroportController::class, 'store']);
$router->get('/admin/aeroport/edit/{id}', [AeroportController::class, 'edit']);
$router->post('/admin/aeroport/update/{id}', [AeroportController::class, 'update']);
$router->post('/admin/aeroport/delete/{id}', [AeroportController::class, 'delete']);

// Vol
$router->get('/admin/vol', [VolController::class, 'index']);
$router->get('/admin/vol/show/{id}', [VolController::class, 'show']);
$router->get('/admin/vol/create', [VolController::class, 'create']);
$router->post('/admin/vol/create', [VolController::class, 'store']);
$router->get('/admin/vol/edit/{id}', [VolController::class, 'edit']);
$router->post('/admin/vol/update/{id}', [VolController::class, 'update']);
$router->get('/admin/vol/delete/{id}', [VolController::class, 'delete']);
$router->get('/admin/vol/passagers/{idVol}', [VolController::class, 'passagers']);

$router->get('/admin/vol/decoller/{idVol}', [VolController::class, 'decoller']);
$router->get('/admin/vol/atterrir/{idVol}', [VolController::class, 'atterrir']);

// Réservation admin
$router->get('/admin/reservation', [ReservationController::class, 'index']);
$router->get('/admin/reservation/show/{id}', [ReservationController::class, 'show']);
$router->get('/admin/reservation/create', [ReservationController::class, 'create']);
$router->post('/admin/reservation/create', [ReservationController::class, 'store']);
$router->get('/admin/reservation/edit/{id}', [ReservationController::class, 'edit']);
$router->post('/admin/reservation/update/{id}', [ReservationController::class, 'update']);
$router->get('/admin/reservation/cancel/{id}', [ReservationController::class, 'cancel']);

// Passagers admin
$router->get('/admin/reservation/add-passager/{id}', [ReservationController::class, 'addPassagerForm']);
$router->post('/admin/reservation/add-passager/{id}', [ReservationController::class, 'addPassager']);
$router->get('/admin/reservation/remove-passager/{id}/{idPassager}', [ReservationController::class, 'removePassager']);

//FRONT
// Dashboard front
$router->get('/front', [FrontController::class, 'dashboard']);

// Recherche vol
$router->get('/vol/recherche', [FrontVolController::class, 'index']);
$router->post('/vol/recherche', [FrontVolController::class, 'index']);

// Choix vol
$router->get('/vol/retour/{idVolAller}', [FrontVolController::class, 'retour']);
$router->get('/vol/terminer/aller/{idVolAller}', [FrontVolController::class, 'terminerSansRetour']);
$router->get('/vol/terminer/aller-retour/{idVolAller}/{idVolRetour}', [FrontVolController::class, 'terminerAvecRetour']);

// Réservation création
$router->get('/reservation/infos', [FrontReservationController::class, 'infosForm']);
$router->post('/reservation/save-infos', [FrontReservationController::class, 'saveInfosReservant']);

$router->get('/reservation/passagers', [FrontReservationController::class, 'passagersForm']);
$router->post('/reservation/save-passagers', [FrontReservationController::class, 'savePassagers']);

$router->get('/reservation/recap', [FrontReservationController::class, 'recap']);
$router->post('/reservation/confirm', [FrontReservationController::class, 'confirm']);

$router->get('/reservation/recap-final/{idReservation}', [FrontReservationController::class, 'recapFinal']);

// Accès réservation
$router->get('/reservation/acces', [FrontReservationController::class, 'accesForm']);
$router->get('/reservation/show', [FrontReservationController::class, 'voirReservation']);

// Edit réservation
$router->get('/reservation/add-passager', [FrontReservationController::class, 'addPassagerForm']);
$router->post('/reservation/add-passager', [FrontReservationController::class, 'addPassager']);
$router->get('/reservation/remove-passager', [FrontReservationController::class, 'removePassager']);
$router->get('/reservation/cancel', [FrontReservationController::class, 'cancel']);

// Dispatch
$router->dispatch();