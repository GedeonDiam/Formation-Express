<?php
// Démarre la session si ce n'est pas déjà fait
if(session_status() == PHP_SESSION_NONE){
    session_start();
}
?>
<div class="formulaire">
    <div class="container">
        <div class="row">

            <div class="col-6" style="padding-top:70px">
                <img src="./src/asset/images/connexion.png" class="" width="600" alt="Connexion">
            </div>

            <div class="col-6 mt-5">
                <div class="formulaire">

                    <div class="centre text-center">
                        <h1>Connexion</h1>
                        <p>Connectez-vous pour accéder à votre compte</p>
                    </div>
                    <hr>

                    <!-- Affichage des messages de session -->
                    <?php if(isset($_SESSION['message'])): ?>
                        <div class="alert alert-success" role="alert">
                            <?php 
                                echo $_SESSION['message'];
                                unset($_SESSION['message']);
                            ?>
                        </div>
                    <?php endif; ?>

                    <form action="index.php?page=gestion_connexion" method="POST"  autocomplete="off">

                        <div class="mb-3">
                            <label for="email" style="display: block; font-weight: bold;" class="form-label">Adresse E-mail</label>
                            <div>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="mdp" style="display: block; font-weight: bold;" class="form-label">Mot de passe</label>
                            <div>
                                <input type="password" class="form-control" id="mdp" name="mdp" required>
                            </div>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">
                               <strong>Se souvenir de moi</strong>
                            </label>
                        </div>

                        <button type="submit" class="btn mt-3" style="width:100%; background-color:#732BF5 !important; color: white; font-weight: bold;">Connexion</button>
                        
                    </form>
                    <p><a href="#">Mot de passe oublié?</a></p>
                </div>

            </div>
        </div>
    </div>
</div>