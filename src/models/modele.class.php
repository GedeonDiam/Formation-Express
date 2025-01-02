<?php
class Modele {
    private $unPdo;

    public function __construct($serveur = "localhost", $bdd = "BTS_Express", $user = "root", $mdp = "") {
        try {
            $this->unPdo = new PDO(
                "mysql:host=" . $serveur . ";dbname=" . $bdd . ";charset=utf8", 
                $user, 
                $mdp, 
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }

        if (!$this->unPdo) {
            die("La connexion à la base de données a échoué");
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

    // Créer un nouveau cours
    public function createCours($data) {
        $sql = "INSERT INTO cours (titre, description, id_enseignant, categorie, image) 
                VALUES (:titre, :description, :id_enseignant, :categorie, :image)";
        $stmt = $this->unPdo->prepare($sql);
        return $stmt->execute([
            ':titre' => $data['titre'],
            ':description' => $data['description'],
            ':id_enseignant' => $data['id_enseignant'],
            ':categorie' => $data['categorie'],
            ':image' => $data['image'] ?? null
        ]);
    }

    // Récupérer tous les cours
    public function getAllCours() {
        $sql = "SELECT c.*, e.nom as nom_enseignant 
                FROM cours c 
                LEFT JOIN enseignants e ON c.id_enseignant = e.id 
                ORDER BY c.date_creation DESC";
        $stmt = $this->unPdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer un cours par son ID
    public function getCoursById($id) {
        $sql = "SELECT c.*, e.nom as nom_enseignant, e.email as email_enseignant, 
                e.domaine, e.diplome 
                FROM cours c 
                LEFT JOIN enseignants e ON c.id_enseignant = e.id 
                WHERE c.id = :id";
        $stmt = $this->unPdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Mettre à jour un cours
    public function updateCours($id, $data) {
        $sql = "UPDATE cours 
                SET titre = :titre, 
                    description = :description, 
                    categorie = :categorie, 
                    image = :image 
                WHERE id = :id";
        $stmt = $this->unPdo->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':titre' => $data['titre'],
            ':description' => $data['description'],
            ':categorie' => $data['categorie'],
            ':image' => $data['image'] ?? null
        ]);
    }

    // Supprimer un cours
    public function deleteCours($id) {
        $sql = "DELETE FROM cours WHERE id = :id";
        $stmt = $this->unPdo->prepare($sql);
        $stmt->execute([':id' => $id]);
    
        // Réinitialiser l'auto-incrément
        $sqlReset = "ALTER TABLE cours AUTO_INCREMENT = 1";
        $this->unPdo->exec($sqlReset);
    
        return true;
    }

    public function getCoursByEnseignant($id_enseignant) {
        $sql = "SELECT * FROM cours WHERE id_enseignant = :id_enseignant";
        $stmt = $this->unPdo->prepare($sql);
        $stmt->execute(['id_enseignant' => $id_enseignant]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function isInscritCours($id_etudiant, $id_cours) {
        $requete = "SELECT * FROM inscriptions_cours WHERE id_etudiant = :id_etudiant AND id_cours = :id_cours";
        $donnees = array(
            ':id_etudiant' => $id_etudiant,
            ':id_cours' => $id_cours
        );
        $select = $this->unPdo->prepare($requete);
        $select->execute($donnees);
        return $select->fetch() !== false;
    }

    public function inscrireCours($id_etudiant, $id_cours) {
        $requete = "INSERT INTO inscriptions_cours (id_etudiant, id_cours) VALUES (:id_etudiant, :id_cours)";
        $donnees = array(
            ':id_etudiant' => $id_etudiant,
            ':id_cours' => $id_cours
        );
        $insert = $this->unPdo->prepare($requete);
        return $insert->execute($donnees);
    }

    public function desinscrireCours($id_etudiant, $id_cours) {
        $requete = "DELETE FROM inscriptions_cours WHERE id_etudiant = :id_etudiant AND id_cours = :id_cours";
        $donnees = array(
            ':id_etudiant' => $id_etudiant,
            ':id_cours' => $id_cours
        );
        $delete = $this->unPdo->prepare($requete);
        return $delete->execute($donnees);
    }

    public function getCoursInscrits($id_etudiant) {
        $sql = "SELECT c.*, ic.date_inscription, e.nom as nom_enseignant
               FROM cours c 
               INNER JOIN inscriptions_cours ic ON c.id = ic.id_cours 
               LEFT JOIN enseignants e ON c.id_enseignant = e.id
               WHERE ic.id_etudiant = :id_etudiant
               ORDER BY ic.date_inscription DESC";
        $stmt = $this->unPdo->prepare($sql);
        $stmt->execute([':id_etudiant' => $id_etudiant]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalTempsParCategorie($id_etudiant) {
        $sql = "SELECT c.categorie, SUM(ic.temps_total) as temps_total
               FROM cours c 
               INNER JOIN inscriptions_cours ic ON c.id = ic.id_cours 
               WHERE ic.id_etudiant = :id_etudiant 
               GROUP BY c.categorie";
        $stmt = $this->unPdo->prepare($sql);
        $stmt->execute([':id_etudiant' => $id_etudiant]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalTemps($id_etudiant) {
        $sql = "SELECT SUM(temps_total) as total 
               FROM inscriptions_cours 
               WHERE id_etudiant = :id_etudiant";
        $stmt = $this->unPdo->prepare($sql);
        $stmt->execute([':id_etudiant' => $id_etudiant]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    }

    public function getCoursParCategorie($id_etudiant) {
        $sql = "SELECT c.categorie, COUNT(*) as nombre
               FROM cours c 
               INNER JOIN inscriptions_cours ic ON c.id = ic.id_cours 
               WHERE ic.id_etudiant = :id_etudiant 
               GROUP BY c.categorie";
        $stmt = $this->unPdo->prepare($sql);
        $stmt->execute([':id_etudiant' => $id_etudiant]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
