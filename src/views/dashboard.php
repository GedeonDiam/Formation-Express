<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

// Vérification de la connexion
if(!isset($_SESSION['enseignant']) && !isset($_SESSION['etudiant'])) {
    header('Location: index.php?page=connexion');
    exit();
}

$isEnseignant = isset($_SESSION['enseignant']);
?>

<div class="container mt-4">
    <h1 class="text-center mb-4">Tableau de bord <?php echo $isEnseignant ? 'Enseignant' : 'Étudiant'; ?></h1>
    
    <?php if($isEnseignant): ?>
        <!-- Interface Enseignant -->
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Mes Cours</h5>
                        <p class="card-text">Gérez vos cours existants</p>
                        <a href="#" class="btn" style="background-color:#732BF5; color:white;">Voir mes cours</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Nouveau Cours</h5>
                        <p class="card-text">Créer un nouveau cours</p>
                        <a href="#" class="btn" style="background-color:#732BF5; color:white;">Créer</a>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Interface Étudiant -->
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Mes Formations</h5>
                        <p class="card-text">Accédez à vos formations</p>
                        <a href="#" class="btn" style="background-color:#732BF5; color:white;">Voir mes formations</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Explorer</h5>
                        <p class="card-text">Découvrez nos formations</p>
                        <a href="index.php?page=cours" class="btn" style="background-color:#732BF5; color:white;">Parcourir</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>