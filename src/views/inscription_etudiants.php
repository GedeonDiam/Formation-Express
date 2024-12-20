<div class="">
    <div class="container">
        <div class="row">
            <div class="col-5">
                <div class="formulaire">
                    <form action="index.php?page=gestion_inscription_etudiants" method="POST">
                        <div class="centre text-center">
                            <h1>Inscription des Etudiants</h1>
                            <hr>
                        </div>

                        <div>
                            <div class="mb-3">
                                <label for="nom" style="display: block; font-weight: bold;" class="form-label">Nom</label>
                                <div>
                                    <input type="text" class="form-control" id="nom" name="nom" style="width:100%" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="mail" style="display: block; font-weight: bold;" class="form-label">Téléphone</label>
                                <div>
                                    <input type="text" class="form-control" id="telephone" name="telephone" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="mail" style="display: block; font-weight: bold;" class="form-label">Adresse E-mail</label>
                                <div>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="mail" style="display: block; font-weight: bold;" class="form-label">Spécialité ou filière</label>
                                <div>
                                    <input type="text" class="form-control" id="specialite" name="specialite" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="motif" style="display: block; font-weight: bold;" class="form-label">Mot de passe</label>
                                <div>
                                    <input type="text" class="form-control" id="mdp" name="mdp" required>
                                </div>
                            </div>                           

                            <button type="submit" class="btn mt-3" style="width:100%; background-color:#732BF5 !important; color: white; font-weight: bold;">Inscription</button>
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

