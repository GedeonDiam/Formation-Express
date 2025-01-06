<?php
if (isset($_SESSION['user'])) {
    $id_enseignant = $_SESSION['user']['id'];
    $courses = $unController->getCoursByEnseignant($id_enseignant);
    $total_courses = count($courses);
    
    // Nouvelles statistiques
    $total_students = $unController->getTotalStudentsByTeacher($id_enseignant);
    $total_quizzes = $unController->getTotalQuizzesByTeacher($id_enseignant);
    $recent_activities = $unController->getRecentActivities($id_enseignant);
    $course_stats = $unController->getCourseStatsByCategory($id_enseignant);
}
?>

<!-- Chart.js pour les graphiques -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container-fluid">
    <!-- En-tête de bienvenue améliorée -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <h1 class="display-4">Bienvenue, <?= htmlspecialchars($_SESSION['user']['nom']) ?></h1>
                    <p class="lead">Tableau de bord de l'enseignant</p>
                    <hr>
                    <p>
                        <i class="fas fa-clock"></i> Dernière connexion : <?= date('d/m/Y H:i') ?><br>
                        <i class="fas fa-graduation-cap"></i> Domaine : <?= htmlspecialchars($_SESSION['user']['domaine']) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Cartes de statistiques avancées -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Cours</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_courses ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Étudiants Inscrits</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_students ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Quiz Créés</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_quizzes ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-question-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Taux de réussite moyen</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">78%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques et Analyses -->
    <div class="row mb-4">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistiques des cours par catégorie</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="coursesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Répartition des étudiants</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4">
                        <canvas id="studentsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activités récentes -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Activités récentes</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <?php foreach ($recent_activities as $activity): ?>
                        <div class="timeline-item">
                            <div class="timeline-date"><?= date('d/m/Y H:i', strtotime($activity['date'])) ?></div>
                            <div class="timeline-content">
                                <h6><?= htmlspecialchars($activity['title']) ?></h6>
                                <p><?= htmlspecialchars($activity['description']) ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Derniers cours -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Vos derniers cours</h6>
                </div>
                <div class="card-body">
                    <?php if ($total_courses > 0): ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Catégorie</th>
                                        <th>Date de création</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $count = 0;
                                    foreach($courses as $course): 
                                        if ($count >= 5) break; // Limite aux 5 derniers cours
                                        $count++;
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($course['titre']) ?></td>
                                        <td><?= htmlspecialchars($course['categorie']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($course['date_creation'])) ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-info" onclick="editCours(<?= $course['id'] ?>)">
                                                <i class="fas fa-edit"></i> Modifier
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-center">Vous n'avez pas encore créé de cours.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Actions rapides</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 mb-3">
                            <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#addCourseModal">
                                <i class="fas fa-plus-circle"></i> Créer un nouveau cours
                            </button>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-3">
                            <a href="index.php?page=dashboard&messages" class="btn btn-info w-100">
                                <i class="fas fa-envelope"></i> Voir les messages
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts pour les graphiques -->
<script>
// Graphique des cours par catégorie
const coursesCtx = document.getElementById('coursesChart').getContext('2d');
new Chart(coursesCtx, {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($course_stats, 'category')) ?>,
        datasets: [{
            label: 'Nombre de cours',
            data: <?= json_encode(array_column($course_stats, 'count')) ?>,
            backgroundColor: 'rgba(78, 115, 223, 0.5)',
            borderColor: 'rgba(78, 115, 223, 1)',
            borderWidth: 1
        }]
    },
    options: {
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Graphique de répartition des étudiants
const studentsCtx = document.getElementById('studentsChart').getContext('2d');
new Chart(studentsCtx, {
    type: 'doughnut',
    data: {
        labels: ['Actifs', 'Inactifs', 'En attente'],
        datasets: [{
            data: [65, 25, 10],
            backgroundColor: ['#1cc88a', '#e74a3b', '#f6c23e']
        }]
    },
    options: {
        maintainAspectRatio: false,
    }
});
</script>

<style>
.timeline {
    position: relative;
    padding: 20px 0;
}

.timeline-item {
    padding: 10px 40px;
    position: relative;
    border-left: 2px solid #e3e6f0;
    margin-bottom: 20px;
}

.timeline-date {
    color: #858796;
    font-size: 0.85rem;
}

.timeline-content {
    padding: 10px 0;
}

.timeline-item:before {
    content: '';
    position: absolute;
    left: -9px;
    top: 0;
    background: #4e73df;
    width: 16px;
    height: 16px;
    border-radius: 50%;
}
</style>
