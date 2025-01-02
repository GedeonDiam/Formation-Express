<?php
session_start();
require_once("../config/db.php");
require_once("controllers.class.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'etudiant') {
    header('Location: ../../index.php');
    exit();
}

$unController = new Controller($serveur, $bdd, $user, $mdp);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_etudiant = $_POST['id_etudiant'];
    $id_cours = $_POST['id_cours'];
    $action = $_POST['action'];

    $success = false;
    if ($action === 'inscription') {
        $success = $unController->inscrireCours($id_etudiant, $id_cours);
    } elseif ($action === 'desinscription') {
        $success = $unController->desinscrireCours($id_etudiant, $id_cours);
    }

    if ($success) {
        header("Location: ../../index.php?page=detail_cours&id=" . $id_cours . "&status=success");
    } else {
        header("Location: ../../index.php?page=detail_cours&id=" . $id_cours . "&status=error");
    }
    exit();
}
