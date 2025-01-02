<div class="container" style="background-color:rgb(235, 225, 218); margin-top: 55px;">
    <div class="row">
        <div class="col-6 ">
            <div class="card-hover" style="margin: 50px; border: solid 1px #FFFFFF; background-color: #FFFFFF; padding: 20px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);">
                <h1>Commencez à apprendre en ligne</h1><br>
                <p>Découvrez une variété de cours captivants conçus pour développer vos compétences et vous accompagner dans la réussite de vos objectifs professionnels et personnels.</p>
            </div>
        </div>
        <div class="col-6">
            <img src="./src/asset/images/cours.png" alt="Cours" width="750">
        </div>
    </div>
</div>

<br><br>

<div class="container">
    <h1>Toutes les compétences dont vous avez besoin au même endroit !</h1>
    <p>Des compétences essentielles aux sujets techniques, BoostSkills contribue à votre dévéloppement professionnel.</p><br><br>

  
  <form class="d-flex mt-3 w-25" role="search"  >
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
    </form><br><br>

    <div class="row">

    <?php 
            $cours = $unController->getAllCours();
            foreach($cours as $course): 
            ?>
        <div class="col-lg-4 col-md-6 mb-4">
            <a href="index.php?page=detail_cours&id=<?= $course['id'] ?>" class="text-decoration-none">
                <div class="card h-100 shadow-sm hover-card">
                    <img src="./src/asset/images/coder.jpg" class="card-img-top" alt="Coder">
                    <div class="card-body">
                        <h5 class="card-title text-dark"><?= htmlspecialchars($course['titre']) ?></h5>
                        <p class="card-text text-muted"><?= htmlspecialchars(substr($course['description'], 0, 100)) ?>...</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <button class="btn btn-outline-primary btn-sm">Voir le cours</button>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <?php endforeach; ?>

       
    </div>
</div><br><br>