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

<!-- Search Bar -->
<div class="input-group mb-4">
    <input type="text" class="form-control" placeholder="Rechercher un cours..." aria-label="Rechercher un cours">
    <button class="btn btn-outline-secondary" type="button">Rechercher</button>
</div>

<!-- Table of Courses -->
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Titre du Cours</th>
                <th>Date de Création</th>
                <th>Catégorie</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $cours = $unController->getAllCours();
            foreach($cours as $course): 
            ?>
            <tr>
                <td><?= $course['id'] ?></td>
                <td><?= htmlspecialchars($course['titre']) ?></td>
                <td><?= date('d/m/Y', strtotime($course['date_creation'])) ?></td>
                <td><?= htmlspecialchars($course['categorie']) ?></td>
                <td>
                    <button class="btn btn-sm btn-info" onclick="editCours(<?= $course['id'] ?>)">Modifier</button>
                    <button class="btn btn-sm btn-danger" onclick="deleteCours(<?= $course['id'] ?>)">Supprimer</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
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
                        <label>Image</label>
                        <input type="file" class="form-control" name="image">
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
function editCours(id) {
    console.log('Édition du cours:', id); // Debug
    fetch(`/Formation-Express/src/controllers/cours_controller.php?action=get&id=${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Réponse réseau non OK');
            }
            return response.json();
        })
        .then(data => {
            console.log('Données reçues:', data); // Debug
            const modal = document.getElementById('addCourseModal');
            if (!modal) {
                throw new Error('Modal non trouvé');
            }
            
            const modalTitle = modal.querySelector('.modal-title');
            modalTitle.textContent = 'Modifier le cours';
            
            modal.querySelector('[name="action"]').value = 'update';
            modal.querySelector('[name="id"]').value = id;
            modal.querySelector('[name="titre"]').value = data.titre || '';
            modal.querySelector('[name="description"]').value = data.description || '';
            modal.querySelector('[name="categorie"]').value = data.categorie || '';
            modal.querySelector('[name="current_image"]').value = data.image || '';
            
            const modalInstance = new bootstrap.Modal(modal);
            modalInstance.show();
        })
        .catch(error => {
            console.error('Erreur détaillée:', error);
            alert('Erreur lors de la récupération des données du cours: ' + error.message);
        });
}

function deleteCours(id) {
    if(confirm('Êtes-vous sûr de vouloir supprimer ce cours ?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'src/controllers/cours_controller.php';
        form.innerHTML = `
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="${id}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>