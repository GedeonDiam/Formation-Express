<?php
//scr/controllers/controllers.class.php
require_once("./src/models/modele.class.php");

Class Controller {
    private $unModele;

    public function __construct($serveur, $bdd, $user, $mdp) {
        $this->unModele = new Modele($serveur, $bdd, $user, $mdp);
    }


    // ----------------------INSCRIPTION ENSEIGNANTS--------------------------------
    public function inscriptionEnseignants($tab) {
        $this->unModele->inscriptionEnseignants($tab);
    }

    public function getEnseignantByEmail($email) {
        return $this->unModele->getEnseignantByEmail($email);
    }
    
    

}