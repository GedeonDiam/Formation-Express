<?php
// Démarre la session si ce n'est pas déjà fait
if(session_status() == PHP_SESSION_NONE){
    session_start();
}
?>
<div class="">
    <div class="container">
        <div class="row">
            <div class="col-5">
                <div class="formulaire">
                    <form action="index.php?page=gestion_inscription" method="POST"  autocomplete="off">
                        <?php
                        if(isset($_SESSION['message'])) {
                            echo '<div class="alert alert-info">'.$_SESSION['message'].'</div>';
                            unset($_SESSION['message']);
                        }
                        ?>
                        <div class="centre text-center">
                            <h1>Inscription des Enseignants</h1>
                            <hr>
                        </div>

                        <div>
                            <div class="mb-3">
                                <label for="nom" class="form-label" style="font-weight: bold;">Nom</label>
                                <input type="text" class="form-control" id="nom" name="nom" required>
                            </div>

                            <div class="mb-3">
                                <label for="telephone" class="form-label" style="font-weight: bold;">Téléphone</label>
                                <input type="text" class="form-control" id="telephone" name="telephone" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label" style="font-weight: bold;">Adresse E-mail</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="mb-3">
                                <label for="diplome" class="form-label" style="font-weight: bold;">Diplôme ou qualification</label>
                                <input type="text" class="form-control" id="diplome" name="diplome" required>
                            </div>

                            <div class="mb-3">
                                <label for="domaine" class="form-label" style="font-weight: bold;">Domaine d'expertise</label>
                                <input type="text" class="form-control" id="domaine" name="domaine" required>
                            </div>
                           
                            <div class="mb-3">
                                <label for="mdp" class="form-label" style="font-weight: bold;">Mot de passe</label>
                                <input type="password" class="form-control" id="mdp" name="mdp" required>
                            </div>

                            <button type="submit" name="boutton" class="btn mt-3 btn-primary" style="width:100%;">Inscription</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-7" style="padding-top:100px">
                <img src="./src/asset/images/inscription.png" width="700" alt="Inscription">
            </div>
        </div>
    </div> 
    <br><br>
</div>