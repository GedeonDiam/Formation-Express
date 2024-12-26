<?php
class Modele {
    private $unPdo;

    // La fonction qui permet de se connecter à la base de données
    public function __construct($serveur, $bdd, $user, $mdp) {
        try {
            $this->unPdo = new PDO("mysql:host=$serveur;dbname=$bdd;charset=utf8", $user, $mdp);
        } catch (PDOException $ex) {
            echo "Erreur de connexion à la base de données";
        }
    }

    // Méthode pour l'inscription des enseignants
    public function inscriptionEnseignants($tab) {
        if (!filter_var($tab['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email format');
        }

        // Hash the password
        $tab['mdp'] = password_hash($tab['mdp'], PASSWORD_DEFAULT);

        $requete = "INSERT INTO enseignants (nom, telephone, email, mdp, role, diplome, domaine) 
                    VALUES (:nom, :telephone, :email, :mdp, :role, :diplome, :domaine)";
        
        $exec = $this->unPdo->prepare($requete);
        $exec->execute([
            ':nom' => $tab['nom'],
            ':telephone' => $tab['telephone'],
            ':email' => $tab['email'],
            ':mdp' => $tab['mdp'],
            ':role' => $tab['role'],
            ':diplome' => $tab['diplome'],
            ':domaine' => $tab['domaine']
        ]);
        
        return true;
    }

    // Méthode pour l'inscription des étudiants
    public function inscriptionEtudiants($tab) {
        if (!filter_var($tab['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email format');
        }

        // Hash the password
        $tab['mdp'] = password_hash($tab['mdp'], PASSWORD_DEFAULT);

        $requete = "INSERT INTO etudiants (nom, telephone, email, role, specialite, mdp) 
                    VALUES (:nom, :telephone, :email, :role, :specialite, :mdp)";
        
        $exec = $this->unPdo->prepare($requete);
        $exec->execute([
            ':nom' => $tab['nom'],
            ':telephone' => $tab['telephone'],
            ':email' => $tab['email'],
            ':role' => $tab['role'],
            ':specialite' => $tab['specialite'],
            ':mdp' => $tab['mdp']
        ]);
        
        return true;
    }

    // Méthode pour récupérer un enseignant par email
    public function getEnseignantByEmail($email) {
        $requete = "SELECT * FROM enseignants WHERE email = :email";
        $exec = $this->unPdo->prepare($requete);
        $exec->bindValue(':email', $email, PDO::PARAM_STR);
        $exec->execute();
        return $exec->fetch(PDO::FETCH_ASSOC);
    }

    // Méthode pour récupérer un étudiant par email
    public function getEtudiantsByEmail($email) {
        $requete = "SELECT * FROM etudiants WHERE email = :email";
        $exec = $this->unPdo->prepare($requete);
        $exec->bindValue(':email', $email, PDO::PARAM_STR);
        $exec->execute();
        return $exec->fetch(PDO::FETCH_ASSOC);
    }

    public function deconnexion() {
        // Détruire la session
        session_destroy();
        
        // Rediriger vers la page d'accueil
        header('Location: /Formation-Express/index.php?page=accueil');
        exit();
    }

    public function createCours($data) {
        $sql = "INSERT INTO cours (titre, description, id_enseignant, categorie) 
                VALUES (:titre, :description, :id_enseignant, :categorie)";
        $stmt = $this->unPdo->prepare($sql);
        return $stmt->execute($data);
    }
    
    public function getCoursByEnseignant($id_enseignant) {
        $sql = "SELECT * FROM cours WHERE id_enseignant = :id_enseignant";
        $stmt = $this->unPdo->prepare($sql);
        $stmt->execute(['id_enseignant' => $id_enseignant]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>