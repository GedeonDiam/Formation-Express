<?php
// Démarre la session pour pouvoir gérer les messages et la connexion
session_start();

// Inclure la configuration de la base de données et le contrôleur
require_once('./src/config/db.php');
require_once('./src/controllers/controllers.class.php');

// Créer une instance du contrôleur
$controller = new Controller($serveur, $bdd, $user, $mdp);

// Vérifie si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupère les données envoyées via POST
    $nom = htmlspecialchars($_POST['nom'] ?? '');
    $telephone = htmlspecialchars($_POST['telephone'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $specialite = htmlspecialchars($_POST['specialite'] ?? '');
    $mdp = htmlspecialchars($_POST['mdp'] ?? '');

    // Vérifie que tous les champs sont remplis
    if (!empty($nom) && !empty($telephone) && !empty($email) && !empty($specialite) && !empty($mdp)) {
        // Vérifier si l'email existe déjà
        if($controller->getEtudiantsByEmail($email)) {
            $_SESSION['message'] = "Cette adresse email est déjà utilisée.";
            header('Location: index.php?page=inscription_etudiants');
            exit();
        }

        // Hachage du mot de passe pour la sécurité
        $hashedPassword = password_hash($mdp, PASSWORD_BCRYPT);

        // Prépare les données pour l'insertion
        $tab = [
            'nom' => $nom,
            'telephone' => $telephone,
            'email' => $email,
            'specialite' => $specialite,
            'mdp' => $hashedPassword
        ];

        try {
            // Tente d'inscrire l'utilisateur via le contrôleur
            $controller->inscriptionEtudiants($tab);

            // Si l'inscription est réussie, message de succès et redirection
            $_SESSION['message'] = "Inscription réussie ! Veuillez vous connecter.";
            header('Location: index.php?page=connexion');
            exit();
        } catch (Exception $e) {
            // En cas d'erreur, message d'erreur et redirection
            $_SESSION['message'] = "Erreur lors de l'inscription : " . $e->getMessage();
            header('Location: index.php?page=inscription_etudiants');
            exit();
        }
    } else {
        // Si les champs sont vides, message d'erreur
        $_SESSION['message'] = "Veuillez remplir tous les champs.";
        header('Location: index.php?page=inscription_etudiants');
        exit();
    }
} else {
    // Si aucun formulaire n'a été soumis, message d'erreur
    $_SESSION['message'] = "Aucune donnée soumise.";
    header('Location: index.php?page=inscription_etudiants');
    exit();
}
?>
