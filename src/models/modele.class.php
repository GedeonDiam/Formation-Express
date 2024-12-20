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

        $requete = "INSERT INTO enseignants (nom, telephone, email, mdp, diplome, domaine) 
                    VALUES (:nom, :telephone, :email, :mdp, :diplome, :domaine)";
        
        $exec = $this->unPdo->prepare($requete);
        $exec->execute([
            ':nom' => $tab['nom'],
            ':telephone' => $tab['telephone'],
            ':email' => $tab['email'],
            ':mdp' => $tab['mdp'],
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

        $requete = "INSERT INTO etudiants (nom, telephone, email, specialite, mdp) 
                    VALUES (:nom, :telephone, :email, :specialite, :mdp)";
        
        $exec = $this->unPdo->prepare($requete);
        $exec->execute([
            ':nom' => $tab['nom'],
            ':telephone' => $tab['telephone'],
            ':email' => $tab['email'],
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
}
?>