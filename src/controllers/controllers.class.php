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

    public function getQuizzesByCours($cours_id) {
        return $this->unModele->getQuizzesByCours($cours_id);
    }

    public function getQuizWithQuestions($quiz_id) {
        return $this->unModele->getQuizWithQuestions($quiz_id);
    }

    public function getQuestionsByQuiz($quiz_id) {
        return $this->unModele->getQuestionsByQuiz($quiz_id);
    }

    public function saveQuizResult($quiz_id, $student_id, $score) {
        return $this->unModele->saveQuizResult($quiz_id, $student_id, $score);
    }

    public function getQuizResults($student_id, $quiz_id = null) {
        return $this->unModele->getQuizResults($student_id, $quiz_id);
    }

    // Nouvelles méthodes pour les statistiques
    public function getTotalStudentsByTeacher($id_enseignant) {
        return $this->unModele->getTotalStudentsByTeacher($id_enseignant);
    }

    public function getTotalQuizzesByTeacher($id_enseignant) {
        return $this->unModele->getTotalQuizzesByTeacher($id_enseignant);
    }

    public function getRecentActivities($id_enseignant) {
        return $this->unModele->getRecentActivities($id_enseignant);
    }

    public function getCourseStatsByCategory($id_enseignant) {
        return $this->unModele->getCourseStatsByCategory($id_enseignant);
    }

    public function deleteCours($id) {
        // Vérifier que le cours existe
        $cours = $this->getCoursById($id);
        if (!$cours) {
            return false;
        }

        // Si le cours existe, supprimer les fichiers associés
        if (!empty($cours['image'])) {
            $image_path = __DIR__ . '/../uploads/images/' . $cours['image'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }

        if (!empty($cours['fichier'])) {
            $fichier_path = __DIR__ . '/../uploads/pdf/' . $cours['fichier'];
            if (file_exists($fichier_path)) {
                unlink($fichier_path);
            }
        }

        // Supprimer le cours de la base de données
        return $this->unModele->deleteCours($id);
    }

    public function createQuiz($data) {
        return $this->unModele->createQuiz($data);
    }

    public function updateQuiz($id, $data) {
        return $this->unModele->updateQuiz($id, $data);
    }

    public function deleteQuiz($id) {
        return $this->unModele->deleteQuiz($id);
    }
}
?>