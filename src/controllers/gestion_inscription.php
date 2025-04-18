<?php

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
    $diplome = htmlspecialchars($_POST['diplome'] ?? '');
    $domaine = htmlspecialchars($_POST['domaine'] ?? '');
    $mdp = htmlspecialchars($_POST['mdp'] ?? '');

    $erreurs = [];

    // Validation des champs
        if (empty($nom)) $erreurs[] = "Le nom est requis.";
        if (empty($telephone)) $erreurs[] = "Le téléphone est requis.";
        if (empty($email)) $erreurs[] = "L'email est requis.";
        if (empty($diplome)) $erreurs[] = "Le diplôme ou la qualification est requis.";
    if (empty($domaine)) $erreurs[] = "Le domaine d'expertise est requis.";
    if (empty($mdp)) $erreurs[] = "Le mot de passe est requis.";

    if (empty($erreurs)) {
        try {
            // Vérifier si l'email existe déjà
            if($controller->getEnseignantByEmail($email)) {
                $_SESSION['message'] = "Cette adresse email est déjà utilisée.";
                header('Location: index.php?page=inscription_prof');
                exit();
            }

 
            // Prépare les données pour l'insertion
            $tab = [
                'nom' => $nom,
                'telephone' => $telephone,
                'email' => $email,
                'diplome' => $diplome,
                'domaine' => $domaine,
                "role" => "enseignant",
                
                'mdp' => $mdp
            ];

            
            // Tente d'inscrire l'utilisateur via le contrôleur
            $controller->inscriptionEnseignants($tab);
            
            try {
                $enseignant = $controller->getEnseignantByEmail($email);
                $etudiant = $controller->getEtudiantsByEmail($email);
                
                if ($enseignant && password_verify($mdp, $enseignant['mdp'])) {
                    $_SESSION['user'] = $enseignant;
                    $_SESSION['role'] = 'enseignant';
                    $_SESSION['message'] = 'Bienvenue ' . $enseignant['nom'] . '!';
                    header('Location: index.php?page=dashboard');
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
            header('Location: index.php?page=inscription_prof');
            exit();
        }
    } else {
        // Si des erreurs de validation existent, les afficher
        $_SESSION['message'] = implode('<br>', $erreurs);
        header('Location: index.php?page=inscription_prof');
        exit();
    }
} else {
    // Si aucun formulaire n'a été soumis, rediriger avec un message
    $_SESSION['message'] = "Aucune donnée soumise.";
    header('Location: index.php?page=inscription_prof');
    exit();
}
?>