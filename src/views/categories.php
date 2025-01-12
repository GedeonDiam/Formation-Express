<div class="container" style="background-color:rgb(183, 202, 221); margin-top: 55px;">
    <div class="row">
        <div class="col-6 ">
            <div class="card-hover" style="margin: 50px; border: solid 1px #FFFFFF; background-color: #FFFFFF; padding: 20px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);">
                <h1>Découvrez les catégories de nos programmes</h1><br>
                <p>Explorez nos catégories et découvrez un large éventail de cours conçus pour répondre à vos besoins, développer vos compétences et atteindre vos objectifs professionnels et personnels.</p>
            </div>
        </div>
        <div class="col-6">
            <img src="./src/asset/images/categorie.png" alt="Cours" width="500">
        </div>
    </div>
</div><br><br>

<?php
$categories = $unController->getUniqueCategories();
foreach($categories as $categorie):
    // Définir une image par défaut pour chaque catégorie
    $image = './src/asset/images/management.jpg'; // image par défaut
    $difficulte = 'Facile';
    $duree = '6 Heures';
    
    // Personnaliser l'image selon la catégorie
    switch(strtolower($categorie['categorie'])) {
        case 'informatique':
            $image = './src/asset/images/informatique.png';
            $duree = '10 Heures';
            break;
        case 'management':
            $image = './src/asset/images/management.jpg';
            break;
        case 'anglais':
            $image = './src/asset/images/anglais.jpg';
            $duree = '10 Heures';
            break;
        case 'culture générale':
            $image = './src/asset/images/culture.jpg';
            break;
    }
?>
<div class="container">
    <div class="row card-hover" style="border: solid 1px #FFFFFF; background-color: #FFFFFF; padding: 20px; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.2);">
        <div class="col-3">
            <div style="margin: 25px;">
                <img src="<?= $image ?>" alt="<?= htmlspecialchars($categorie['categorie']) ?>" width="250">
            </div>
        </div>

        <div class="col-9">
            <a href="index.php?page=cours&categorie=<?= urlencode($categorie['categorie']) ?>" style="text-decoration: none; color: black;">
                <div style="margin: 25px;">
                    <h5 style="color: #732BF5;"><?= htmlspecialchars($categorie['categorie']) ?></h5>
                    <hr>
                    <h4>Découvrez nos cours de <?= htmlspecialchars($categorie['categorie']) ?></h4>
                    <p><i class="bi bi-bar-chart"></i> <?= $difficulte ?> &nbsp; &nbsp;<i class="bi bi-clock"></i> <?= $duree ?></p>
                    <p><?= $categorie['count'] ?> cours disponibles dans cette catégorie</p>
                </div>
            </a>
        </div>
    </div>
</div><br><br>
<?php endforeach; ?>