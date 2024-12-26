<?php
// Démarre la session pour pouvoir gérer les messages et la connexion
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Inclure la configuration de la base de données et le contrôleur
require_once(__DIR__ . '/../config/db.php');
require_once(__DIR__ . '/../controllers/controllers.class.php');

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

    $erreurs = [];

    // Validation des champs
    if (empty($nom)) $erreurs[] = "Le nom est requis.";
    if (empty($telephone)) $erreurs[] = "Le téléphone est requis.";
    if (empty($email)) $erreurs[] = "L'email est requis.";
    if (empty($specialite)) $erreurs[] = "La spécialité est requise.";
    if (empty($mdp)) $erreurs[] = "Le mot de passe est requis.";

    if (empty($erreurs)) {
        try {
            // Vérifier si l'email existe déjà
            if ($controller->getEtudiantsByEmail($email)) {
                $_SESSION['message'] = "Cette adresse email est déjà utilisée.";
                header('Location: /Formation-Express/index.php?page=inscription_etudiants');
                exit();
            }

            // Prépare les données pour l'insertion
            $tab = [
                'nom' => $nom,
                'telephone' => $telephone,
                'email' => $email,
                'specialite' => $specialite,
                'role' => 'etudiant',
                'mdp' => $mdp
            ];

            // Tente d'inscrire l'utilisateur via le contrôleur
            $controller->inscriptionEtudiants($tab);
            try {
                

                $enseignant = $controller->getEnseignantByEmail($email);
                $etudiant = $controller->getEtudiantsByEmail($email);


        if ($etudiant && password_verify($mdp, $etudiant['mdp'])) {
                    $_SESSION['user'] = $etudiant;
                    $_SESSION['role'] = 'etudiant';
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
        } catch (Exception $e) {
            // En cas d'erreur, message d'erreur et redirection
            $_SESSION['message'] = "Erreur lors de l'inscription : " . $e->getMessage();
            header('Location: /Formation-Express/index.php?page=inscription_etudiants');
            exit();
        }
    } else {
        // Si des erreurs de validation existent, les afficher
        $_SESSION['message'] = implode('<br>', $erreurs);
        header('Location: /Formation-Express/index.php?page=inscription_etudiants');
        exit();
    }
} else {
    // Si aucun formulaire n'a été soumis, rediriger avec un message
    $_SESSION['message'] = "Aucune donnée soumise.";
    header('Location: /Formation-Express/index.php?page=inscription_etudiants');
    exit();
}
