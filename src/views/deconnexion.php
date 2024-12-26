<?php
// Inclusion des fichiers requis
require_once(__DIR__ . '/../config/db.php'); // Monte d'un niveau pour accéder à src/config/db.php
require_once(__DIR__ . '/../controllers/controllers.class.php'); // Monte d'un niveau pour accéder à src/controllers/controllers.class.php

// Initialisation du contrôleur
$controller = new Controller($serveur, $bdd, $user, $mdp);

// Appel de la méthode de déconnexion
$controller->deconnexion();
?>
