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

    if ($action === 'create' || $action === 'update') {
        $data = [
            'titre' => $_POST['titre'],
            'description' => $_POST['description'],
            'categorie' => $_POST['categorie'],
            'id_enseignant' => $_SESSION['user']['id'] ?? null,
            'image' => null,
            'fichier' => null
        ];

        // Traitement de l'image
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $allowed = ['image/jpeg', 'image/png', 'image/gif'];
            if (in_array($_FILES['image']['type'], $allowed)) {
                $image_name = uniqid() . '_' . $_FILES['image']['name'];
                $target_dir = __DIR__ . '/../uploads/images/';
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $image_name)) {
                    $data['image'] = $image_name;
                }
            }
        } elseif (isset($_POST['current_image'])) {
            $data['image'] = $_POST['current_image'];
        }

        // Traitement du fichier PDF
        if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] === 0) {
            if ($_FILES['fichier']['type'] === 'application/pdf') {
                $fichier_name = uniqid() . '_' . $_FILES['fichier']['name'];
                $target_dir = __DIR__ . '/../uploads/pdf/';
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                if (move_uploaded_file($_FILES['fichier']['tmp_name'], $target_dir . $fichier_name)) {
                    $data['fichier'] = $fichier_name;
                }
            }
        } elseif (isset($_POST['current_fichier'])) {
            $data['fichier'] = $_POST['current_fichier'];
        }

        // Création ou mise à jour
        if ($action === 'create') {
            $success = $modele->createCours($data);
        } else {
            $id = intval($_POST['id']);
            $success = $modele->updateCours($id, $data);
        }

        // Redirection
        $status = $success ? 'success' : 'error';
        header("Location: /Formation-Express/index.php?page=dashboard&menu=cours&status={$status}&action={$action}");
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

    if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
        $id = intval($_GET['id']);
        if ($modele->deleteCours($id)) {
            header("Location: /Formation-Express/index.php?page=dashboard&cours&status=success&action=delete");
        } else {
            header("Location: /Formation-Express/index.php?page=dashboard&cours&status=error&action=delete");
        }
        exit();
    }
}

?>
