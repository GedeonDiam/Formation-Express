<?php
session_start();
require_once '../models/modele.class.php';
require_once("../config/db.php");

try {
    $modele = new Modele($serveur, $bdd, $user, $mdp);
} catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}

// Traitement des formulaires
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    // Vérifier l'authentification pour toutes les actions
    if (!isset($_SESSION['user'])) {
        header('Location: /Formation-Express/index.php?page=connexion');
        exit();
    }
    
    switch($action) {
        case 'create':
        case 'edit':
        case 'delete':
            // Vérifier le rôle enseignant pour ces actions uniquement
            if ($_SESSION['user']['role'] !== 'enseignant') {
                header('Location: /Formation-Express/index.php?page=dashboard&status=error&message=unauthorized');
                exit();
            }
            switch($action) {
                case 'create':
                    try {
                        $data = [
                            'title' => $_POST['title'],
                            'cours_id' => $_POST['cours_id'],
                            'teacher_id' => $_SESSION['user']['id'],
                            'questions' => $_POST['questions'] ?? []
                        ];
                        
                        if ($modele->createQuiz($data)) {
                            header("Location: /Formation-Express/index.php?page=dashboard&menu=quiz&status=success&action=create");
                        } else {
                            throw new Exception("Erreur lors de la création du quiz");
                        }
                    } catch (Exception $e) {
                        header("Location: /Formation-Express/index.php?page=dashboard&menu=quiz&status=error&action=create");
                    }
                    exit();
                    break;

                case 'edit':
                    try {
                        $quiz_id = intval($_POST['quiz_id']);
                        $data = [
                            'title' => $_POST['title'],
                            'cours_id' => $_POST['cours_id'],
                            'questions' => $_POST['questions'] ?? []
                        ];
                        
                        if ($modele->updateQuiz($quiz_id, $data)) {
                            header("Location: /Formation-Express/index.php?page=dashboard&menu=quiz&status=success&action=update");
                        } else {
                            throw new Exception("Erreur lors de la modification du quiz");
                        }
                    } catch (Exception $e) {
                        header("Location: /Formation-Express/index.php?page=dashboard&menu=quiz&status=error&action=update");
                    }
                    exit();
                    break;

                case 'delete':
                    try {
                        $quiz_id = intval($_POST['quiz_id']);
                        if ($modele->deleteQuiz($quiz_id)) {
                            header("Location: /Formation-Express/index.php?page=dashboard&menu=quiz&status=success&action=delete");
                        } else {
                            throw new Exception("Erreur lors de la suppression du quiz");
                        }
                    } catch (Exception $e) {
                        header("Location: /Formation-Express/index.php?page=dashboard&menu=quiz&status=error&action=delete");
                    }
                    exit();
                    break;
            }
            break;

        case "valided_quiz":
            // Vérifier le rôle étudiant pour la validation du quiz
            if ($_SESSION['user']['role'] !== 'etudiant') {
                header('Location: /Formation-Express/index.php?page=dashboard&status=error&message=unauthorized');
                exit();
            }
            try {
                $quiz_id = intval($_POST['quiz_id']);
                $student_id = $_SESSION['user']['id'];
                $cours_id = intval($_POST['cours_id']);
                
                // Récupérer les questions du quiz
                $questions = $modele->getQuestionsByQuiz($quiz_id);
                
                if (empty($questions)) {
                    throw new Exception("Aucune question trouvée pour ce quiz");
                }

                $totalQuestions = count($questions);
                $correctAnswers = 0;
                
                // Vérifier chaque réponse
                foreach ($questions as $question) {
                    $questionKey = 'question_' . $question['id'];
                    if (isset($_POST[$questionKey])) {
                        $selectedAnswerId = intval($_POST[$questionKey]);
                        
                        // Vérifier si la réponse sélectionnée est correcte
                        foreach ($question['answers'] as $answer) {
                            if ($answer['id'] === $selectedAnswerId && $answer['is_correct']) {
                                $correctAnswers++;
                                break;
                            }
                        }
                    }
                }
                
                // Calculer le score en pourcentage
                $score = ($correctAnswers / $totalQuestions) * 100;
                
                // Enregistrer le résultat
                if ($modele->saveQuizResult($quiz_id, $student_id, round($score))) {
                    $message = "Quiz terminé ! Votre score : " . round($score) . "%";
                    header("Location: /Formation-Express/index.php?page=quiz&cours_id=" . $cours_id . "&status=success&message=" . urlencode($message));
                } else {
                    throw new Exception("Erreur lors de l'enregistrement du résultat");
                }
            } catch (Exception $e) {
                header("Location: /Formation-Express/index.php?page=quiz&cours_id=" . $cours_id . "&status=error&message=" . urlencode($e->getMessage()));
            }
            exit();
            break;
    }
}
?>
