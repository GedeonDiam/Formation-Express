<?php
if (isset($_SESSION['user'])) {
    $id_enseignant = $_SESSION['user']['id'];
    $cours = $unController->getCoursByEnseignant($id_enseignant);
}

// Si on est en mode édition
if (isset($_GET['edit'])) {
    $quiz_id = intval($_GET['edit']);
    $quiz_to_edit = $unController->getQuizWithQuestions($quiz_id);
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="header-title">Gestion des Quiz</h1>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addQuizModal">
        Créer un nouveau quiz
    </button>
</div>

<!-- Liste des Quiz -->
<div class="row">
    <?php
    foreach ($cours as $course):
        $quizzes = $unController->getQuizzesByCours($course['id']);
    ?>
    <div class="col-12 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="m-0"><?= htmlspecialchars($course['titre']) ?></h5>
            </div>
            <div class="card-body">
                <?php if (empty($quizzes)): ?>
                    <p>Aucun quiz pour ce cours</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Date de création</th>
                                    <th>Nombre de questions</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($quizzes as $quiz): ?>
                                <tr>
                                    <td><?= htmlspecialchars($quiz['title']) ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($quiz['created_at'])) ?></td>
                                    <td><?= $quiz['nb_questions'] ?></td>
                                    <td>
                                        <form method="POST" action="src/controllers/quiz_controller.php" class="d-inline">
                                            <input type="hidden" name="quiz_id" value="<?= $quiz['id'] ?>">
                                            <button type="submit" name="action" value="edit" class="btn btn-sm btn-info">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="src/controllers/quiz_controller.php" class="d-inline" 
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce quiz ?')">
                                            <input type="hidden" name="quiz_id" value="<?= $quiz['id'] ?>">
                                            <button type="submit" name="action" value="delete" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Modal Création/Édition Quiz -->
<div class="modal fade" id="addQuizModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="src/controllers/quiz_controller.php">
                <div class="modal-header">
                    <h5 class="modal-title"><?= isset($quiz_to_edit) ? 'Modifier' : 'Créer' ?> un Quiz</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="<?= isset($quiz_to_edit) ? 'edit' : 'create' ?>">
                    <?php if (isset($quiz_to_edit)): ?>
                        <input type="hidden" name="quiz_id" value="<?= $quiz_to_edit['id'] ?>">
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label>Cours associé</label>
                        <select class="form-select" name="cours_id" required>
                            <?php foreach ($cours as $course): ?>
                            <option value="<?= $course['id'] ?>" 
                                <?= (isset($quiz_to_edit) && $quiz_to_edit['cours_id'] == $course['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($course['titre']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label>Titre du Quiz</label>
                        <input type="text" class="form-control" name="title" required 
                               value="<?= isset($quiz_to_edit) ? htmlspecialchars($quiz_to_edit['title']) : '' ?>">
                    </div>

                    <div id="questions-container">
                        <?php if (isset($quiz_to_edit)): ?>
                            <?php foreach ($quiz_to_edit['questions'] as $index => $question): ?>
                                <!-- Afficher les questions existantes -->
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <input type="text" class="form-control mb-2" 
                                               name="questions[<?= $index ?>][content]"
                                               value="<?= htmlspecialchars($question['content']) ?>" required>
                                        
                                        <?php 
                                        $answers = explode('|||', $question['answers']);
                                        foreach ($answers as $i => $answer):
                                            list($id, $content, $is_correct) = explode(':::', $answer);
                                        ?>
                                        <div class="input-group mb-2">
                                            <div class="input-group-text">
                                                <input type="radio" name="questions[<?= $index ?>][correct]" 
                                                       value="<?= $i ?>" <?= $is_correct ? 'checked' : '' ?> required>
                                            </div>
                                            <input type="text" class="form-control" 
                                                   name="questions[<?= $index ?>][answers][]"
                                                   value="<?= htmlspecialchars($content) ?>" required>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <button type="button" class="btn btn-success" onclick="addQuestionForm()">
                        <i class="fas fa-plus"></i> Ajouter une question
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">Sauvegarder</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function addQuestionForm() {
    const container = document.getElementById('questions-container');
    const questionIndex = container.children.length;
    const questionDiv = document.createElement('div');
    questionDiv.classList.add('card', 'mb-3');
    
    questionDiv.innerHTML = `
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6>Question ${questionIndex + 1}</h6>
                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.card').remove(); updateQuestionNumbers();">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mb-3">
                <label>Question</label>
                <input type="text" class="form-control" name="questions[${questionIndex}][content]" required>
            </div>
            <div class="answers-container">
                <label>Réponses</label>
                <small class="d-block text-muted mb-2">Sélectionnez la bonne réponse avec le bouton radio</small>
                ${[1, 2, 3, 4].map(num => `
                    <div class="input-group mb-2">
                        <div class="input-group-text">
                            <input type="radio" name="questions[${questionIndex}][correct]" value="${num-1}" 
                                   ${num === 1 ? 'required checked' : ''}>
                        </div>
                        <input type="text" class="form-control" 
                               name="questions[${questionIndex}][answers][]"
                               placeholder="Réponse ${num}" required>
                        ${num > 2 ? `<button type="button" class="btn btn-outline-danger" 
                                           onclick="this.closest('.input-group').remove();">
                                    <i class="fas fa-times"></i>
                                   </button>` : ''}
                    </div>
                `).join('')}
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm" 
                    onclick="addAnswerField(this.previousElementSibling, ${questionIndex})">
                <i class="fas fa-plus"></i> Ajouter une réponse
            </button>
        </div>
    `;
    
    container.appendChild(questionDiv);
}

function addAnswerField(container, questionIndex) {
    const answersCount = container.children.length;
    if (answersCount >= 6) { // Maximum 6 réponses
        alert('Maximum 6 réponses par question');
        return;
    }

    const answerGroup = document.createElement('div');
    answerGroup.className = 'input-group mb-2';
    answerGroup.innerHTML = `
        <div class="input-group-text">
            <input type="radio" name="questions[${questionIndex}][correct]" value="${answersCount}">
        </div>
        <input type="text" class="form-control" 
               name="questions[${questionIndex}][answers][]"
               placeholder="Réponse ${answersCount + 1}" required>
        <button type="button" class="btn btn-outline-danger" 
                onclick="this.closest('.input-group').remove();">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(answerGroup);
}

function updateQuestionNumbers() {
    const questions = document.querySelectorAll('#questions-container .card');
    questions.forEach((q, idx) => {
        const title = q.querySelector('h6');
        title.textContent = `Question ${idx + 1}`;
        
        // Mettre à jour les indices dans les noms des champs
        const inputs = q.querySelectorAll('input[name^="questions["]');
        inputs.forEach(input => {
            input.name = input.name.replace(/questions\[\d+\]/, `questions[${idx}]`);
        });
    });
}

// Ajouter une première question au chargement si c'est un nouveau quiz
if (!document.querySelector('#questions-container .card')) {
    addQuestionForm();
}
</script>
