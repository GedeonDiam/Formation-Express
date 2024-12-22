<?php
require_once("src/config/db.php");
require_once("./src/controllers/controllers.class.php");

$unController = new Controller($serveur, $bdd, $user, $mdp);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./public/styles/accueil.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <title>Document</title>
    <div>
        <?php include("./src/includes/header.php"); ?>
    </div>

    <div>
        <?php
        $page = isset($_GET['page']) ? $_GET['page'] : 'accueil';

        switch ($page) {
            case 'accueil':
                include('./src/views/accueil.php');
                break;

            case 'cours':
                include('./src/views/cours.php');
                break;

            case 'categorie':
                include('./src/views/categories.php');
                break;

            case 'connexion':
                include('./src/views/connexion.php');
                break;

            case 'inscription_etudiants':
                include('./src/views/inscription_etudiants.php');
                break;

            case 'inscription_prof':
                include('./src/views/inscription_prof.php');
                break;

            case 'gestion_inscription':
                include('./src/controllers/gestion_inscription.php');
                break;

            case 'gestion_inscription_etudiants':
                include('./src/controllers/gestion_inscription_etudiants.php');
                break;

                case 'dashboard':
                    include('./src/views/dashboard.php');
                    break;

            default:
                include('./src/views/accueil.php');
                break;
        }
        ?>
    </div>
    <div>
        <?php include("./src/includes/footer.php"); ?>
    </div>
</head>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>