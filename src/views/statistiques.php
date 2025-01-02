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
</script>