<?php
require_once(__DIR__ . '/../config/db.php');
require_once(__DIR__ . '/../models/modele.class.php');
require_once(__DIR__ . '/../controllers/controllers.class.php');

$controller = new Controller($serveur, $bdd, $user, $mdp);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars($_POST['email'] ?? '');
    $mdp = htmlspecialchars($_POST['mdp'] ?? '');

    try {
        $enseignant = $controller->getEnseignantByEmail($email);
        $etudiant = $controller->getEtudiantsByEmail($email);
    
        if ($enseignant && password_verify($mdp, $enseignant['mdp'])) {
            $_SESSION['user'] = $enseignant;
            $_SESSION['role'] = 'enseignant';
            $_SESSION['message'] = 'Bienvenue ' . $enseignant['nom'] . '!';
            
            header('Location: index.php?page=accueil');
            exit();
        } elseif ($etudiant && password_verify($mdp, $etudiant['mdp'])) {
            $_SESSION['user'] = $etudiant;
            $_SESSION['role'] = 'etudiant';
            $_SESSION['message'] = 'Bienvenue ' . $etudiant['nom'] . '!';
            header('Location: index.php?page=accueil');

            exit();
        } else {
            $_SESSION['message'] = $verif;
            header('Location: index.php?page=connexion');
            exit();
        }
    } catch (Exception $e) {
        $_SESSION['message'] = 'Erreur : ' . $e->getMessage();
        header('Location: index.php?page=connexion');
        exit();
    }
}
?>