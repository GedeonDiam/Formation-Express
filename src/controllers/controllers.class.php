<!-- controllers.class.php -->

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
    public function getAllCours() {
        return $this->unModele->getAllCours();
    }

    public function createCours($data) {
        return $this->unModele->createCours($data);
    }

    public function getCoursByEnseignant($id_enseignant) {
        return $this->unModele->getCoursByEnseignant($id_enseignant);
    }

    public function getCoursById($id) {
        return $this->unModele->getCoursById($id);
    }

    public function isInscritCours($id_etudiant, $id_cours) {
        return $this->unModele->isInscritCours($id_etudiant, $id_cours);
    }

    public function inscrireCours($id_etudiant, $id_cours) {
        return $this->unModele->inscrireCours($id_etudiant, $id_cours);
    }

    public function desinscrireCours($id_etudiant, $id_cours) {
        return $this->unModele->desinscrireCours($id_etudiant, $id_cours);
    }

    public function getCoursInscrits($id_etudiant) {
        return $this->unModele->getCoursInscrits($id_etudiant);
    }

    public function getTotalTempsParCategorie($id_etudiant) {
        return $this->unModele->getTotalTempsParCategorie($id_etudiant);
    }

    public function getTotalTemps($id_etudiant) {
        return $this->unModele->getTotalTemps($id_etudiant);
    }

    public function getCoursParCategorie($id_etudiant) {
        return $this->unModele->getCoursParCategorie($id_etudiant);
    }
}
?>