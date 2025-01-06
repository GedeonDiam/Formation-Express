<?php
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'etudiant') {
    header('Location: index.php?page=connexion');
    exit();
}

$coursInscrits = $unController->getCoursInscrits($_SESSION['user']['id']);
$coursParCategorie = $unController->getCoursParCategorie($_SESSION['user']['id']);

// Récupérer les statistiques des quiz
$quizResults = $unController->getQuizResults($_SESSION['user']['id']);
$totalQuizzes = count($quizResults);
$averageScore = 0;
$bestScore = 0;

if ($totalQuizzes > 0) {
    $totalScore = array_sum(array_column($quizResults, 'score'));
    $averageScore = round($totalScore / $totalQuizzes);
    $bestScore = max(array_column($quizResults, 'score'));
}
?>

<div class="container mt-4">
    <h2>Mes Statistiques</h2>
    
    <div class="row mt-4">
        <div class="col-md-4 mb-4">
            <div class="card text-white" style="background-color:#732BF5;">
                <div class="card-body">
                    <h5>Total Cours Suivis</h5>
                    <h2><?= count($coursInscrits) ?></h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Catégories suivies</h5>
                    <h2><?= count($coursParCategorie) ?></h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>Date d'inscription</h5>
                    <h2><?= date('d/m/Y', strtotime($coursInscrits[0]['date_inscription'] ?? 'now')) ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5>Quiz Complétés</h5>
                    <h2><?= $totalQuizzes ?></h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card text-white" style="background-color: #FF6B6B;">
                <div class="card-body">
                    <h5>Score Moyen</h5>
                    <h2><?= $averageScore ?>%</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Distribution par catégorie</h5>
                </div>
                <div class="card-body">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Nouvelle section pour les Quiz -->
    <?php if (!empty($quizResults)): ?>
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Performance des Quiz</h5>
                    <span class="badge bg-success">Meilleur score : <?= $bestScore ?>%</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Cours</th>
                                    <th>Quiz</th>
                                    <th>Score</th>
                                    <th>Date</th>
                                    <th>Performance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($quizResults as $result): ?>
                                <tr>
                                    <td><?= htmlspecialchars($result['cours_titre']) ?></td>
                                    <td><?= htmlspecialchars($result['quiz_title']) ?></td>
                                    <td><?= $result['score'] ?>%</td>
                                    <td><?= date('d/m/Y H:i', strtotime($result['completed_at'])) ?></td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar <?= $result['score'] >= 70 ? 'bg-success' : ($result['score'] >= 50 ? 'bg-warning' : 'bg-danger') ?>" 
                                                role="progressbar" 
                                                style="width: <?= $result['score'] ?>%"
                                                aria-valuenow="<?= $result['score'] ?>" 
                                                aria-valuemin="0" 
                                                aria-valuemax="100">
                                                <?= $result['score'] ?>%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique des performances -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Évolution des scores</h5>
                </div>
                <div class="card-body">
                    <canvas id="quizChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Liste des cours suivis</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Cours</th>
                                <th>Date d'inscription</th>
                                <th>Catégorie</th>
                                <th>Enseignant</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($coursInscrits as $cours): ?>
                            <tr>
                                <td><?= htmlspecialchars($cours['titre']) ?></td>
                                <td><?= date('d/m/Y', strtotime($cours['date_inscription'])) ?></td>
                                <td><?= htmlspecialchars($cours['categorie']) ?></td>
                                <td><?= htmlspecialchars($cours['nom_enseignant']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const categoryData = {
    labels: <?= json_encode(array_column($coursParCategorie, 'categorie')) ?>,
    datasets: [{
        data: <?= json_encode(array_column($coursParCategorie, 'nombre')) ?>,
        backgroundColor: ['#732BF5', '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0']
    }]
};

const categoryChart = new Chart(document.getElementById('categoryChart'), {
    type: 'pie',
    data: categoryData,
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'Répartition des cours par catégorie'
            }
        }
    }
});

// Graphique d'évolution des scores
const quizData = {
    labels: <?= json_encode(array_map(function($result) {
        return date('d/m', strtotime($result['completed_at']));
    }, $quizResults)) ?>,
    datasets: [{
        label: 'Score des Quiz (%)',
        data: <?= json_encode(array_column($quizResults, 'score')) ?>,
        borderColor: '#FF6B6B',
        backgroundColor: 'rgba(255, 107, 107, 0.1)',
        fill: true
    }]
};

const quizChart = new Chart(document.getElementById('quizChart'), {
    type: 'line',
    data: quizData,
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                max: 100
            }
        },
        plugins: {
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'Progression des scores aux quiz'
            }
        }
    }
});
</script>