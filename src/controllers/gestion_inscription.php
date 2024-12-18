<?php
// Démarre la session pour pouvoir utiliser les variables de session
session_start();

// Inclure la configuration de la base de données et le contrôleur
require_once('./src/config/db.php');
require_once('./src/controllers/controllers.class.php');

// Crée une instance du contrôleur
$controller = new Controller($serveur, $bdd, $user, $mdp);

// Vérifie si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupère les données du formulaire
    $tab = [
        'nom' => $_POST['nom'] ?? '',
        'telephone' => $_POST['telephone'] ?? '',
        'email' => $_POST['email'] ?? '',
        'diplome' => $_POST['diplome'] ?? '',
        'domaine' => $_POST['domaine'] ?? '',
        'mdp' => $_POST['mdp'] ?? ''
    ];

    try {
        // Essaye d'inscrire l'enseignant dans la base de données
        $controller->inscriptionEnseignants($tab);

        // Si l'inscription est réussie, on définit un message de succès dans la session
        $_SESSION['message'] = "Inscription réussie ! Veuillez vous connecter.";

        // Redirige vers la page de connexion
        header('Location: index.php?page=connexion');
        exit();
    } catch (Exception $e) {
        // En cas d'erreur, on définit un message d'erreur dans la session
        $_SESSION['message'] = 'Erreur : ' . $e->getMessage();

        // Redirige à nouveau vers la page d'inscription pour afficher l'erreur
        header('Location: index.php?page=inscription_prof');
        exit();
    }
} else {
    // Si aucun formulaire n'a été soumis, on affiche un message
    echo "Aucun formulaire soumis.";
}
?>
