<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./public/styles/accueil.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <title>Document</title>
    <div>
        <?php include ("./src/includes/header.php"); ?>
    </div>

    <div>

<?php
//$page = isset($_GET['page']) ? $_GET['page'] : 'accueil';
$page = isset($_GET['page']);
if ($page) {
    $page = $_GET['page'];
} else {
    $page = 'accueil';
}

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

        case 'a-propos':
        include('./src/views/a-propos.php');
        break; 

        case 'connexion':
        include('./src/views/connexion.php');
        break;

        case 'inscription':
        include('./src/views/inscription.php');
        break;
  
    default:
        include('./src/views/accueil.php');
        break;
}

?>
<div>
        <?php include ("./src/includes/footer.php"); ?>
    </div>
</head>
</body>
</html>