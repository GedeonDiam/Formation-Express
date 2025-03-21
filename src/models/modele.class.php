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
        $sql = "INSERT INTO cours (titre, description, id_enseignant, categorie, image, fichier) 
                VALUES (:titre, :description, :id_enseignant, :categorie, :image, :fichier)";
        $stmt = $this->unPdo->prepare($sql);
        return $stmt->execute([
            ':titre' => $data['titre'],
            ':description' => $data['description'],
            ':id_enseignant' => $data['id_enseignant'],
            ':categorie' => $data['categorie'],
            ':image' => $data['image'],
            ':fichier' => $data['fichier']
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
                    image = :image,
                    fichier = :fichier 
                WHERE id = :id";
        $stmt = $this->unPdo->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':titre' => $data['titre'],
            ':description' => $data['description'],
            ':categorie' => $data['categorie'],
            ':image' => $data['image'],
            ':fichier' => $data['fichier']
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

    public function getQuizzesByCours($cours_id) {
        $sql = "SELECT q.*, COUNT(qst.id) as nb_questions, 
                e.nom as nom_enseignant
                FROM quizz q
                LEFT JOIN questions qst ON q.id = qst.quizz_id
                LEFT JOIN enseignants e ON q.created_by_teacher = e.id
                WHERE q.cours_id = :cours_id 
                GROUP BY q.id
                ORDER BY q.created_at DESC";
        $stmt = $this->unPdo->prepare($sql);
        $stmt->execute([':cours_id' => $cours_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getQuizWithQuestions($quiz_id) {
        // Récupérer le quiz
        $sql = "SELECT * FROM quizz WHERE id = :quiz_id";
        $stmt = $this->unPdo->prepare($sql);
        $stmt->execute([':quiz_id' => $quiz_id]);
        $quiz = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$quiz) return null;

        // Récupérer les questions
        $sql = "SELECT q.*, GROUP_CONCAT(
                    CONCAT(a.id, ':::', a.content, ':::', a.is_correct) 
                    SEPARATOR '|||'
                ) as answers
                FROM questions q
                LEFT JOIN answers a ON q.id = a.question_id
                WHERE q.quizz_id = :quiz_id
                GROUP BY q.id";
        $stmt = $this->unPdo->prepare($sql);
        $stmt->execute([':quiz_id' => $quiz_id]);
        $quiz['questions'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $quiz;
    }

    public function getQuestionsByQuiz($quiz_id) {
        $sql = "SELECT * FROM questions WHERE quizz_id = :quiz_id ORDER BY created_at";
        $stmt = $this->unPdo->prepare($sql);
        $stmt->execute([':quiz_id' => $quiz_id]);
        $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Récupérer les réponses pour chaque question
        foreach ($questions as &$question) {
            $sql = "SELECT * FROM answers WHERE question_id = :question_id";
            $stmt = $this->unPdo->prepare($sql);
            $stmt->execute([':question_id' => $question['id']]);
            $question['answers'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $questions;
    }

    public function saveQuizResult($quiz_id, $student_id, $score) {
        $sql = "INSERT INTO quizz_results (quizz_id, student_id, score) 
                VALUES (:quiz_id, :student_id, :score)";
        $stmt = $this->unPdo->prepare($sql);
        return $stmt->execute([
            ':quiz_id' => $quiz_id,
            ':student_id' => $student_id,
            ':score' => $score
        ]);
    }

    public function getQuizResults($student_id, $quiz_id = null) {
        $sql = "SELECT qr.*, q.title as quiz_title, c.titre as cours_titre
                FROM quizz_results qr
                JOIN quizz q ON qr.quizz_id = q.id
                JOIN cours c ON q.cours_id = c.id
                WHERE qr.student_id = :student_id";
        
        if ($quiz_id) {
            $sql .= " AND qr.quizz_id = :quiz_id";
        }
        
        $sql .= " ORDER BY qr.completed_at DESC";
        
        $stmt = $this->unPdo->prepare($sql);
        $params = [':student_id' => $student_id];
        
        if ($quiz_id) {
            $params[':quiz_id'] = $quiz_id;
        }
        
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalStudentsByTeacher($id_enseignant) {
        $sql = "SELECT COUNT(DISTINCT e.id) as total 
                FROM etudiants e 
                JOIN inscriptions_cours ic ON e.id = ic.id_etudiant 
                JOIN cours c ON ic.id_cours = c.id 
                WHERE c.id_enseignant = :id_enseignant";
        $stmt = $this->unPdo->prepare($sql);
        $stmt->execute([':id_enseignant' => $id_enseignant]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    }

    public function getTotalQuizzesByTeacher($id_enseignant) {
        $sql = "SELECT COUNT(*) as total FROM quizz WHERE created_by_teacher = :id_enseignant";
        $stmt = $this->unPdo->prepare($sql);
        $stmt->execute([':id_enseignant' => $id_enseignant]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    }

    public function getRecentActivities($id_enseignant) {
        $sql = "SELECT * FROM (
                    SELECT 'Nouveau cours' as type, titre as title, 
                           'Cours créé' as description, date_creation as date 
                    FROM cours 
                    WHERE id_enseignant = :id_enseignant
                    UNION ALL
                    SELECT 'Nouveau quiz' as type, title as title,
                           'Quiz créé' as description, created_at as date
                    FROM quizz 
                    WHERE created_by_teacher = :id_enseignant
                ) as activities 
                ORDER BY date DESC LIMIT 5";
        $stmt = $this->unPdo->prepare($sql);
        $stmt->execute([':id_enseignant' => $id_enseignant]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCourseStatsByCategory($id_enseignant) {
        $sql = "SELECT categorie as category, COUNT(*) as count 
                FROM cours 
                WHERE id_enseignant = :id_enseignant 
                GROUP BY categorie";
        $stmt = $this->unPdo->prepare($sql);
        $stmt->execute([':id_enseignant' => $id_enseignant]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createQuiz($data) {
        try {
            $this->unPdo->beginTransaction();

            // Créer le quiz
            $sql = "INSERT INTO quizz (title, created_by_teacher, cours_id) 
                    VALUES (:title, :teacher_id, :cours_id)";
            $stmt = $this->unPdo->prepare($sql);
            $stmt->execute([
                ':title' => $data['title'],
                ':teacher_id' => $data['teacher_id'],
                ':cours_id' => $data['cours_id']
            ]);
            $quiz_id = $this->unPdo->lastInsertId();

            // Ajouter les questions
            foreach ($data['questions'] as $q) {
                $sql = "INSERT INTO questions (content, quizz_id) VALUES (:content, :quiz_id)";
                $stmt = $this->unPdo->prepare($sql);
                $stmt->execute([
                    ':content' => $q['content'],
                    ':quiz_id' => $quiz_id
                ]);
                $question_id = $this->unPdo->lastInsertId();

                // Ajouter les réponses
                foreach ($q['answers'] as $index => $answer) {
                    $sql = "INSERT INTO answers (content, is_correct, question_id) 
                           VALUES (:content, :is_correct, :question_id)";
                    $stmt = $this->unPdo->prepare($sql);
                    $stmt->execute([
                        ':content' => $answer,
                        ':is_correct' => ($index == $q['correct']),
                        ':question_id' => $question_id
                    ]);
                }
            }

            $this->unPdo->commit();
            return true;
        } catch (Exception $e) {
            $this->unPdo->rollBack();
            throw $e;
        }
    }

    public function updateQuiz($id, $data) {
        try {
            $this->unPdo->beginTransaction();

            // Mettre à jour le quiz
            $sql = "UPDATE quizz SET title = :title, cours_id = :cours_id WHERE id = :id";
            $stmt = $this->unPdo->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':title' => $data['title'],
                ':cours_id' => $data['cours_id']
            ]);

            // Supprimer les anciennes questions et réponses
            $sql = "DELETE FROM questions WHERE quizz_id = :quiz_id";
            $stmt = $this->unPdo->prepare($sql);
            $stmt->execute([':quiz_id' => $id]);

            // Ajouter les nouvelles questions
            foreach ($data['questions'] as $q) {
                $sql = "INSERT INTO questions (content, quizz_id) VALUES (:content, :quiz_id)";
                $stmt = $this->unPdo->prepare($sql);
                $stmt->execute([
                    ':content' => $q['content'],
                    ':quiz_id' => $id
                ]);
                $question_id = $this->unPdo->lastInsertId();

                // Ajouter les réponses pour chaque question
                foreach ($q['answers'] as $index => $answer) {
                    $sql = "INSERT INTO answers (content, is_correct, question_id) 
                           VALUES (:content, :is_correct, :question_id)";
                    $stmt = $this->unPdo->prepare($sql);
                    $stmt->execute([
                        ':content' => $answer,
                        ':is_correct' => ($index == $q['correct']),
                        ':question_id' => $question_id
                    ]);
                }
            }

            $this->unPdo->commit();
            return true;
        } catch (Exception $e) {
            $this->unPdo->rollBack();
            throw $e;
        }
    }

    public function deleteQuiz($id) {
        try {
            $this->unPdo->beginTransaction();

            // Les suppressions en cascade sont gérées par les contraintes de la base de données
            $sql = "DELETE FROM quizz WHERE id = :id";
            $stmt = $this->unPdo->prepare($sql);
            $result = $stmt->execute([':id' => $id]);

            // Réinitialiser les auto-incréments
            $this->unPdo->exec("ALTER TABLE quizz AUTO_INCREMENT = 1");
            $this->unPdo->exec("ALTER TABLE questions AUTO_INCREMENT = 1");
            $this->unPdo->exec("ALTER TABLE answers AUTO_INCREMENT = 1");

            $this->unPdo->commit();
            return $result;
        } catch (Exception $e) {
            $this->unPdo->rollBack();
            throw $e;
        }
    }

    // Méthode pour récupérer le profil complet d'un enseignant
    public function getProfilEnseignant($id) {
        $requete = "SELECT * FROM enseignants WHERE id = :id";
        $stmt = $this->unPdo->prepare($requete);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Méthode pour récupérer le profil complet d'un étudiant
    public function getProfilEtudiant($id) {
        $requete = "SELECT * FROM etudiants WHERE id = :id";
        $stmt = $this->unPdo->prepare($requete);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
