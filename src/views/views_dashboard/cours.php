<?php
if (isset($_SESSION['user'])) {
    $id_enseignant = $_SESSION['user']['id'];
    $cours = $unController->getCoursByEnseignant($id_enseignant);
}

if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $course_id = intval($_GET['edit']);
    $course_data = $unController->getCoursById($course_id);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['edit_course'])) {
        $course_id = intval($_POST['course_id']);
        $course_data = $unController->getCoursById($course_id);
        if ($course_data) {
            // Préremplir le formulaire modal
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    const modal = document.getElementById('addCourseModal');
                    modal.querySelector('[name=\"action\"]').value = 'update';
                    modal.querySelector('[name=\"id\"]').value = '{$course_data['id']}';
                    modal.querySelector('[name=\"titre\"]').value = '" . addslashes($course_data['titre']) . "';
                    modal.querySelector('[name=\"description\"]').value = '" . addslashes($course_data['description']) . "';
                    modal.querySelector('[name=\"categorie\"]').value = '" . addslashes($course_data['categorie']) . "';
                    modal.querySelector('[name=\"current_image\"]').value = '" . addslashes($course_data['image']) . "';
                    modal.querySelector('[name=\"current_fichier\"]').value = '" . addslashes($course_data['fichier']) . "';
                    
                    document.getElementById('modalTitle').textContent = 'Modifier le cours';
                    
                    const modalInstance = new bootstrap.Modal(modal);
                    modalInstance.show();
                });
            </script>";
        }
    } elseif (isset($_POST['delete_course'])) {
        $course_id = intval($_POST['course_id']);
        if ($unController->deleteCours($course_id)) {
            header("Location: index.php?page=dashboard&menu=cours&status=success&action=delete");
        } else {
            header("Location: index.php?page=dashboard&menu=cours&status=error&action=delete");
        }
        exit();
    }
}
?>

<!-- Alerts -->
<?php if (isset($_GET['status']) && isset($_GET['action'])): ?>
    <?php 
    $action = $_GET['action'];
    $actionText = [
        'create' => 'créé',
        'update' => 'modifié',
        'delete' => 'supprimé'
    ][$action] ?? '';
    ?>
    
    <?php if ($_GET['status'] === 'success'): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        Le cours a été <?= $actionText ?> avec succès !
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php elseif ($_GET['status'] === 'error'): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        Une erreur est survenue lors de l'opération.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
<?php endif; ?>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="header-title">Mes Cours</h1>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCourseModal">
        Ajouter un nouveau cours
    </button>
</div>

<!-- Statistiques rapides -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Cours</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($cours) ?></div>
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
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Catégories</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= count(array_unique(array_column($cours, 'categorie'))) ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-folder fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtres et recherche -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Filtres</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-3">
                <select class="form-select" id="filterCategorie">
                    <option value="">Toutes les catégories</option>
                    <?php 
                    $categories = array_unique(array_column($cours, 'categorie'));
                    foreach($categories as $categorie): 
                    ?>
                    <option value="<?= htmlspecialchars($categorie) ?>"><?= htmlspecialchars($categorie) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <select class="form-select" id="filterDate">
                    <option value="">Trier par date</option>
                    <option value="recent">Plus récent</option>
                    <option value="ancien">Plus ancien</option>
                </select>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text" class="form-control" id="searchCours" placeholder="Rechercher...">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Liste des cours en format cards -->
<div class="row" id="coursesList">
    <?php foreach($cours as $course): ?>
    <div class="col-xl-4 col-md-6 mb-4 course-card" 
         data-categorie="<?= htmlspecialchars($course['categorie']) ?>"
         data-date="<?= $course['date_creation'] ?>">
        <div class="card shadow h-100">
            <?php if(!empty($course['image'])): ?>
            <img src="src/uploads/images/<?= htmlspecialchars($course['image']) ?>" 
                 class="card-img-top" alt="Image du cours"
                 style="height: 200px; object-fit: cover;">
            <?php endif; ?>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <h5 class="card-title"><?= htmlspecialchars($course['titre']) ?></h5>
                    <span class="badge bg-primary"><?= htmlspecialchars($course['categorie']) ?></span>
                </div>
                <p class="card-text text-truncate"><?= htmlspecialchars($course['description']) ?></p>
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        <i class="far fa-calendar-alt"></i> 
                        <?= date('d/m/Y', strtotime($course['date_creation'])) ?>
                    </small>
                    <div class="btn-group">
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                            <button type="submit" name="edit_course" class="btn btn-sm btn-info">
                                <i class="fas fa-edit"></i>
                            </button>
                        </form>
                        <form method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce cours ?');">
                            <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                            <button type="submit" name="delete_course" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>



<!-- Modal Ajout/Modification Cours -->
<div class="modal fade" id="addCourseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="src/controllers/cours_controller.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Ajouter un cours</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="create">
                    <input type="hidden" name="id" value="">
                    <input type="hidden" name="current_image" value="">
                    <input type="hidden" name="current_fichier" value="">
                    
                    <div class="mb-3">
                        <label>Titre</label>
                        <input type="text" class="form-control" name="titre" required>
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea class="form-control" name="description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Catégorie</label>
                        <input type="text" class="form-control" name="categorie" required>
                    </div>
                    <div class="mb-3">
                        <label>Image (JPG, PNG, GIF)</label>
                        <input type="file" class="form-control" name="image" 
                               accept="image/jpeg,image/png,image/gif">
                        <small class="text-muted">Formats acceptés: .jpg, .jpeg, .png, .gif</small>
                    </div>
                    <div class="mb-3">
                        <label>Support de cours (PDF)</label>
                        <input type="file" class="form-control" name="fichier" 
                               accept="application/pdf">
                        <small class="text-muted">Format accepté: .pdf uniquement</small>
                    </div>
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

// Fonction de filtrage des cours
function filterCourses() {
    const categorie = document.getElementById('filterCategorie').value;
    const dateSort = document.getElementById('filterDate').value;
    const search = document.getElementById('searchCours').value.toLowerCase();
    
    const cards = document.querySelectorAll('.course-card');
    
    cards.forEach(card => {
        const cardCategorie = card.dataset.categorie;
        const cardTitle = card.querySelector('.card-title').textContent.toLowerCase();
        const cardDate = new Date(card.dataset.date);
        
        let show = true;
        
        // Filtre par catégorie
        if (categorie && cardCategorie !== categorie) show = false;
        
        // Filtre par recherche
        if (search && !cardTitle.includes(search)) show = false;
        
        card.style.display = show ? '' : 'none';
    });
    
    // Tri par date
    if (dateSort) {
        const cardArray = Array.from(cards);
        cardArray.sort((a, b) => {
            const dateA = new Date(a.dataset.date);
            const dateB = new Date(b.dataset.date);
            return dateSort === 'recent' ? dateB - dateA : dateA - dateB;
        });
        
        const container = document.getElementById('coursesList');
        cardArray.forEach(card => container.appendChild(card));
    }
}

// Écouteurs d'événements pour les filtres
document.getElementById('filterCategorie').addEventListener('change', filterCourses);
document.getElementById('filterDate').addEventListener('change', filterCourses);
document.getElementById('searchCours').addEventListener('input', filterCourses);
</script>

<style>
.card-img-top {
    transition: transform 0.3s ease;
}

.card:hover .card-img-top {
    transform: scale(1.05);
}

.badge {
    font-size: 0.8em;
    padding: 0.5em 0.7em;
}

.course-card {
    transition: transform 0.3s ease;
}

.course-card:hover {
    transform: translateY(-5px);
}

.btn-group .btn {
    padding: 0.25rem 0.5rem;
}
</style>