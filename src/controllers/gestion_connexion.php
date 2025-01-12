<?php
require_once(__DIR__ . '/../config/db.php');
require_once(__DIR__ . '/../models/modele.class.php');
require_once(__DIR__ . '/../controllers/controllers.class.php');

$controller = new Controller($serveur, $bdd, $user, $mdp);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars($_POST['email'] ?? '');
    $mdp = htmlspecialchars($_POST['mdp'] ?? '');

    if (empty($email) || empty($mdp)) {
        $_SESSION['error'] = "Veuillez remplir tous les champs";
        header('Location: index.php?page=connexion');
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Format d'email invalide";
        header('Location: index.php?page=connexion');
        exit();
    }

    try {
        $enseignant = $controller->getEnseignantByEmail($email);
        $etudiant = $controller->getEtudiantsByEmail($email);
    
        if ($enseignant && password_verify($mdp, $enseignant['mdp'])) {
            $_SESSION['user'] = $enseignant;
            $_SESSION['role'] = 'enseignant';
            $_SESSION['success'] = 'Bienvenue ' . $enseignant['nom'] . '!';
            header('Location: index.php?page=accueil');
            exit();
        } elseif ($etudiant && password_verify($mdp, $etudiant['mdp'])) {
            $_SESSION['user'] = $etudiant;
            $_SESSION['role'] = 'etudiant';
            $_SESSION['success'] = 'Bienvenue ' . $etudiant['nom'] . '!';
            header('Location: index.php?page=accueil');
            exit();
        } else {
            if (!$enseignant && !$etudiant) {
                $_SESSION['error'] = "Aucun compte trouvé avec cet email";
            } else {
                $_SESSION['error'] = "Mot de passe incorrect";
            }
            header('Location: index.php?page=connexion');
            exit();
        }
    } catch (Exception $e) {
        $_SESSION['error'] = 'Une erreur est survenue : ' . $e->getMessage();
        header('Location: index.php?page=connexion');
        exit();
    }
}
?>