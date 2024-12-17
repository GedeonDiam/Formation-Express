<?php
    if (isset($_POST['boutton'])){
        include ('./src/models/modele.class.php');

        $nom = $_POST['nom'];
        $telephone = $_POST['telephone'];
        $email = $_POST['email'];
        $diplome = $_POST['diplome'];
        $domaine = $_POST['domaine'];
        $mdp = $_POST['mdp'];   
      

        //lancement de la requette
        $requete = $this->pdo->prepare("INSERT INTO enseignant(nom, telephone, email, diplome, domaine, mdp, ) VALUES($nom,$telephone,$email,$diplome,$domaine,$mdp)");

    }
?>




<div class="">
    <div class="container">
        <div class="row">
            <div class="col-5">
                <div class="formulaire">
                    <form action="" method="POST">
                        <div class="centre text-center">
                            <h1>Inscription des Enseignants </h1>
                            <hr>
                        </div>

                        <div>
                            <div class="mb-3">
                                <label for="nom" style="display: block; width:80%; font-weight: bold;" class="form-label">Nom</label>
                                <div>
                                    <input type="text" class="form-control" id="nom" name="nom" style="width:100%" required>
                                </div>
                            </div>



                            <div class="mb-3">
                                <label for="mail" style="display: block; width:80%; font-weight: bold;" class="form-label">Téléphone</label>
                                <div>
                                    <input type="email" class="form-control" id="telephone" name="telephone" required>
                                </div>
                            </div>


                            <div class="mb-3">
                                <label for="mail" style="display: block; width:80%; font-weight: bold;" class="form-label">Diplôme ou qualification</label>
                                <div>
                                    <input type="email" class="form-control" id="diplome" name="email" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="mail" style="display: block; width:80%; font-weight: bold;" class="form-label">Domaine d'expertise</label>
                                <div>
                                    <input type="email" class="form-control" id="domaine" name="email" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="mail" style="display: block; width:80%; font-weight: bold;" class="form-label">Adresse E-mail</label>
                                <div>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="motif" style="display: block; width:80%; font-weight: bold;" class="form-label">Mot de passe</label>
                                <div>
                                    <input type="text" class="form-control" id="mdp" name="mdp" required>
                                </div>
                            </div>

                           

                            <button type="submit" class="btn mt-3" style="width:80%; background-color:#732BF5 !important; color: white; font-weight: bold;">Inscription</button>
                        </div>

                    </form>
                </div>

            </div>
            <div class="col-7" style="padding-top:100px">
                <img src="./src/asset/images/inscription.png" class="" width="700">
            </div>
        </div>
    </div> <br><br>
</div>