<?php
// Démarre la session si ce n'est pas déjà fait
if(session_status() == PHP_SESSION_NONE){
    session_start();
}
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-6 mt-5 ">
            <div class="me-5">
                <div class="content">
                    <div>

                    </div>
                    <h1 style="font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;">Apprenez une <span>Nouvelle Compétence</span> Chaque jour, à tout moment, et Partout.</h1>
                    <p>Plus de 1000 cours couvrant tous les domaines technologiques pour vous permettre d'apprendre et d'explorer de nouvelles opportunités. Apprenez auprès d'experts du secteur et décrochez l'emploi de vos rêves.</p>
                    <div class="buttons">
                        <a href="index.php?page=cours" class="start-trial">Commencer</a>
                    </div>
                </div>

                <div class="stats">
                    <div class="stat">
                        <h3 style="color: #FFDC1D;">1000+</h3>
                        <p>Cours parmi lesquels choisir</p>
                    </div>

                    <div class="stat">
                        <h3 style="color: #007bff;">5000+</h3>
                        <p>Étudiants formés</p>
                    </div>
                    <div class="stat">
                        <h3 style="color: #F08650;">200+</h3>
                        <p>Formateurs professionnels</p>
                    </div>
                </div>
            </div>


        </div>

        <div class="col-6 ">
            <img src="./src/asset/images/accueil.png" alt="">
        </div>
    </div> <br><br>

    <div class="container">
        <div class="container">
            <div>
                <h1 style="text-align: center;">Une équipe pédagogique <span style="color: #732BF5;">Expérimentée</span> & <span style="color: #732BF5 ;">Fiable</span> </h1>
            </div><br><br>
            <div class="row">
            <div class="col-4">
                <div class="card card-hover">
                    <img src="./src/asset/images/professeur.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h1 style="color:#732BF5;">200+ Enseignants</h1>
                        <p class="card-text">de l'Education nationale qui conçoivent et rédigent les parcours pédagogiques.</p>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card card-hover">
                    <img src="./src/asset/images/student.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h1 style="color:#732BF5;">15 ans +</h1>
                        <p class="card-text">d'expertise dans la réussite scolaire auprès d'enfants de tous niveaux.</p>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card card-hover">
                    <img src="./src/asset/images/eleve.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h1 style="color:#732BF5;">Assistance 7j/7</h1>
                        <p class="card-text">Des conseillers pédagogiques compétents, efficaces et agréables pour répondre à vos questions.</p>
                    </div>
                </div>
            </div>
        </div> <br><br>
        </div>

        
    </div>
</div>