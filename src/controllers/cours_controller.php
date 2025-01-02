<?php
session_start();
require_once '../models/modele.class.php';
require_once("../config/db.php"); // Ajout de la configuration de la BD

try {
    $modele = new Modele($serveur, $bdd, $user, $mdp);
} catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'create') {
        $data = [
            'titre' => $_POST['titre'],
            'description' => $_POST['description'],
            'categorie' => $_POST['categorie'],
            'image' => isset($_FILES['image']) && $_FILES['image']['error'] === 0 
                ? $_FILES['image']['name'] 
                : ''
        ];

        // Gérer le téléchargement de fichier
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $target_dir = __DIR__ . '/../uploads/';
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $target_file = $target_dir . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
        }

        if ($modele->createCours($data)) {
            header("Location: /Formation-Express/index.php?page=dashboard&section=cours&status=success&action=create");
        } else {
            header("Location: /Formation-Express/index.php?page=dashboard&section=cours&status=error&action=create");
        }
        exit();
    } elseif ($action === 'update') {
        $id = intval($_POST['id']);
        $data = [
            'titre' => $_POST['titre'],
            'description' => $_POST['description'],
            'categorie' => $_POST['categorie'],
            'image' => isset($_FILES['image']) && $_FILES['image']['error'] === 0 
                ? $_FILES['image']['name'] 
                : $_POST['current_image']
        ];

        // Gérer le téléchargement de fichier
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $target_dir = __DIR__ . '/../uploads/';
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $target_file = $target_dir . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
        }

        if ($modele->updateCours($id, $data)) {
            header("Location: /Formation-Express/index.php?page=dashboard&section=cours&status=success&action=update");
        } else {
            header("Location: /Formation-Express/index.php?page=dashboard&section=cours&status=error&action=update");
        }
        exit();
    } elseif ($action === 'delete') {
        $id = intval($_POST['id']);
        if ($modele->deleteCours($id)) {
            header("Location: /Formation-Express/index.php?page=dashboard&section=cours&status=success&action=delete");
        } else {
            header("Location: /Formation-Express/index.php?page=dashboard&section=cours&status=error&action=delete");
        }
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action']) && $_GET['action'] === 'get' && isset($_GET['id'])) {
        try {
            // Suppression de tout output avant l'envoi du JSON
            ob_clean(); // Nettoie le buffer de sortie
            
            $id = intval($_GET['id']);
            $cours = $modele->getCoursById($id);
            
            if ($cours) {
                header('Content-Type: application/json');
                header('Access-Control-Allow-Origin: *');
                echo json_encode($cours, JSON_THROW_ON_ERROR);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Cours non trouvé'], JSON_THROW_ON_ERROR);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()], JSON_THROW_ON_ERROR);
        }
        exit();
    }
}

?>
