<?php
// src/controllers/gestion_connexion.php
require_once('./src/config/db.php');
require_once('./src/controllers/controllers.class.php');

// Initialisation du contrôleur
$controller = new Controller($serveur, $bdd, $user, $mdp);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars($_POST['email']);
    $mdp = htmlspecialchars($_POST['mdp']);

    try {
        // Récupération de l'utilisateur par email
        $enseignant = $controller->getEnseignantByEmail($email);
        $etudiant = $controller->getEtudiantsByEmail($email);

        if ($enseignant && password_verify($mdp, $enseignant['mdp'])) {
            // Connexion réussie
            session_start();
            $_SESSION['enseignant'] = $enseignant['id'];
            header('Location: index.php?page=accueil');
            exit();
        } else {
            echo '<div class="alert alert-danger">Email ou mot de passe incorrect.</div>';
        }
    } catch (Exception $e) {
        echo '<div class="alert alert-danger">Erreur : ' . $e->getMessage() . '</div>';
    }

    if ($etudiant && password_verify($mdp, $etudiant['mdp'])) {
        // Connexion réussie
        session_start();
        $_SESSION['etudiant'] = $etudiant['id'];
        header('Location: index.php?page=accueil');
        exit();
    } else {
        echo '<div class="alert alert-danger">Email ou mot de passe incorrect.</div>';
    }
}
?>
