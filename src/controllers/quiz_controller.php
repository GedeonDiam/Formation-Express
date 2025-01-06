<?php
session_start();
require_once '../models/modele.class.php';
require_once("../config/db.php");

// Vérification de l'authentification et du rôle enseignant
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'enseignant') {
    header('Location: /Formation-Express/index.php?page=connexion');
    exit();
}

try {
    $modele = new Modele($serveur, $bdd, $user, $mdp);
} catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}

// Traitement des formulaires
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch($action) {
        case 'create':
            try {
                $data = [
                    'title' => $_POST['title'],
                    'cours_id' => $_POST['cours_id'],
                    'teacher_id' => $_SESSION['user']['id'],
                    'questions' => $_POST['questions'] ?? []
                ];
                
                if ($modele->createQuiz($data)) {
                    header("Location: /Formation-Express/index.php?page=dashboard&menu=quiz&status=success&action=create");
                } else {
                    throw new Exception("Erreur lors de la création du quiz");
                }
            } catch (Exception $e) {
                header("Location: /Formation-Express/index.php?page=dashboard&menu=quiz&status=error&action=create");
            }
            exit();
            break;

        case 'edit':
            try {
                $quiz_id = intval($_POST['quiz_id']);
                $data = [
                    'title' => $_POST['title'],
                    'cours_id' => $_POST['cours_id'],
                    'questions' => $_POST['questions'] ?? []
                ];
                
                if ($modele->updateQuiz($quiz_id, $data)) {
                    header("Location: /Formation-Express/index.php?page=dashboard&menu=quiz&status=success&action=update");
                } else {
                    throw new Exception("Erreur lors de la modification du quiz");
                }
            } catch (Exception $e) {
                header("Location: /Formation-Express/index.php?page=dashboard&menu=quiz&status=error&action=update");
            }
            exit();
            break;

        case 'delete':
            try {
                $quiz_id = intval($_POST['quiz_id']);
                if ($modele->deleteQuiz($quiz_id)) {
                    header("Location: /Formation-Express/index.php?page=dashboard&menu=quiz&status=success&action=delete");
                } else {
                    throw new Exception("Erreur lors de la suppression du quiz");
                }
            } catch (Exception $e) {
                header("Location: /Formation-Express/index.php?page=dashboard&menu=quiz&status=error&action=delete");
            }
            exit();
            break;
    }
}
?>
