<?php
if (!isset($_GET['cours_id'])) {
    header('Location: index.php?page=cours');
    exit();
}

$cours_id = intval($_GET['cours_id']);
$cours = $unController->getCoursById($cours_id);

if (!$cours) {
    header('Location: index.php?page=cours');
    exit();
}

$quizzes = $unController->getQuizzesByCours($cours_id);

// Récupérer les résultats des quiz de l'étudiant pour ce cours
$quiz_results = [];
if (isset($_SESSION['user'])) {
    foreach ($quizzes as $quiz) {
        $result = $unController->getQuizResults($_SESSION['user']['id'], $quiz['id']);
        if (!empty($result)) {
            $quiz_results[$quiz['id']] = $result[0]; // Prendre le dernier résultat
        }
    }
}

// Si un quiz spécifique est sélectionné
$selectedQuiz = null;
$questions = null;
if (isset($_GET['quiz_id'])) {
    $questions = $unController->getQuestionsByQuiz($_GET['quiz_id']);
}
?>

<?php if (isset($_GET['status']) && isset($_GET['message'])): ?>
    <div class="alert alert-<?= $_GET['status'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_GET['message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col">
            <h2>Quiz - <?= htmlspecialchars($cours['titre']) ?></h2>
            <p class="text-muted">Sélectionnez un quiz pour commencer</p>
        </div>
    </div>

    <?php if (empty($quizzes)): ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            Aucun quiz n'est disponible pour ce cours pour le moment.
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($quizzes as $quiz): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($quiz['title']) ?></h5>
                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="bi bi-person me-2"></i>
                                Par <?= htmlspecialchars($quiz['nom_enseignant']) ?>
                            </small>
                        </div>
                        <p class="mb-2">
                            <i class="bi bi-question-circle me-2"></i>
                            <?= $quiz['nb_questions'] ?> questions
                        </p>
                        <p class="text-muted">
                            <i class="bi bi-calendar3 me-2"></i>
                            Créé le : <?= date('d/m/Y', strtotime($quiz['created_at'])) ?>
                        </p>
                        
                        <?php if (isset($quiz_results[$quiz['id']])): ?>
                        <div class="alert alert-info mb-3">
                            <i class="bi bi-trophy-fill me-2"></i>
                            Meilleur score : <?= $quiz_results[$quiz['id']]['score'] ?>%
                            <br>
                            <small class="text-muted">
                                Le <?= date('d/m/Y H:i', strtotime($quiz_results[$quiz['id']]['completed_at'])) ?>
                            </small>
                        </div>
                        <?php endif; ?>

                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" 
                                data-bs-target="#quizModal<?= $quiz['id'] ?>">
                            <i class="bi bi-play-fill me-2"></i>
                            <?= isset($quiz_results[$quiz['id']]) ? 'Refaire le quiz' : 'Commencer le quiz' ?>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal pour chaque quiz -->
            <div class="modal fade" id="quizModal<?= $quiz['id'] ?>" tabindex="-1" 
                 aria-labelledby="quizModalLabel<?= $quiz['id'] ?>" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="quizModalLabel<?= $quiz['id'] ?>">
                                <?= htmlspecialchars($quiz['title']) ?>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="src/controllers/quiz_controller.php" method="POST">
                            <div class="modal-body">
                                <?php 
                                $questions = $unController->getQuestionsByQuiz($quiz['id']);
                                foreach ($questions as $index => $question): 
                                ?>
                                    <div class="question mb-4">
                                        <h5>Question <?= $index + 1 ?>: <?= htmlspecialchars($question['content']) ?></h5>
                                        <div class="answers mt-3">
                                            <?php foreach ($question['answers'] as $answer): ?>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="radio" 
                                                           name="question_<?= $question['id'] ?>" 
                                                           value="<?= $answer['id'] ?>" 
                                                           id="answer_<?= $answer['id'] ?>">
                                                    <label class="form-check-label" for="answer_<?= $answer['id'] ?>">
                                                        <?= htmlspecialchars($answer['content']) ?>
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="modal-footer">
                                <input type="hidden" name="quiz_id" value="<?= $quiz['id'] ?>">
                                <input type="hidden" name="cours_id" value="<?= $cours_id ?>">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-primary">Valider mes réponses</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if (!empty($quiz_results)): ?>
        <div class="mt-5">
            <h3>Historique des résultats</h3>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Quiz</th>
                            <th>Score</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($quiz_results as $result): ?>
                        <tr>
                            <td><?= htmlspecialchars($result['quiz_title']) ?></td>
                            <td>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar" role="progressbar" 
                                         style="width: <?= $result['score'] ?>%;" 
                                         aria-valuenow="<?= $result['score'] ?>" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        <?= $result['score'] ?>%
                                    </div>
                                </div>
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($result['completed_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
