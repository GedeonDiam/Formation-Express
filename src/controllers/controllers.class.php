<?php
require_once(__DIR__ . '/../models/modele.class.php');

class Controller {
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

    // ----------------------INSCRIPTION ETUDIANTS--------------------------------
    public function inscriptionEtudiants($tab) {
        $this->unModele->inscriptionEtudiants($tab);
    }
    
    public function getEtudiantsByEmail($email) {
        return $this->unModele->getEtudiantsByEmail($email);
    } 

    public function deconnexion() {
        $this->unModele->deconnexion();
    }

    // ----------------------GESTION DES COURS--------------------------------
    public function createCours($data) {
        return $this->unModele->createCours($data);
    }

    public function getCoursByEnseignant($id_enseignant) {
        return $this->unModele->getCoursByEnseignant($id_enseignant);
    }
}
?>