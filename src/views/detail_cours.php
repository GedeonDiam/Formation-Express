<?php
if (!isset($_GET['id'])) {
    header('Location: index.php?page=cours');
    exit();
}

$cours = $unController->getCoursById($_GET['id']);
if (!$cours) {
    header('Location: index.php?page=cours');
    exit();
}
?>

<div class="container-fluid py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-lg rounded-lg overflow-hidden">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0"><?= htmlspecialchars($cours['titre']) ?></h2>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h5 class="text-muted mb-3">Description du cours</h5>
                        <p class="lead"><?= htmlspecialchars($cours['description']) ?></p>
                    </div>

                    <?php if ($cours['fichier']): ?>
                    <div class="embed-responsive embed-responsive-16by9 mb-4"style="height: 100vh;">
                        <embed 
                            src="./src/asset/files/<?= htmlspecialchars($cours['fichier']) ?>" 
                            type="application/pdf"
                            class="embed-responsive-item shadow-sm"
                            style="width: 100%; height: 100vh;"
                        >
                    </div>
                    <?php endif; ?>

                    <div class="mt-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-calendar3 text-primary me-2"></i>
                            <span>Créé le: <?= date('d/m/Y', strtotime($cours['date_creation'])) ?></span>
                        </div>
                        <div class="d-flex align-items-center mt-2">
                            <i class="bi bi-tag text-primary me-2"></i>
                            <span>Catégorie: <?= htmlspecialchars($cours['categorie']) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-4">Informations sur l'enseignant</h5>
                    <div class="d-flex flex-column">
                        <div class="teacher-info mb-4">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-person-circle text-primary me-2 fs-4"></i>
                                <h6 class="mb-0"><?= htmlspecialchars($cours['nom_enseignant']) ?></h6>
                            </div>
                            
                            <div class="ms-4">
                                <p class="mb-2">
                                    <i class="bi bi-envelope text-muted me-2"></i>
                                    <?= htmlspecialchars($cours['email_enseignant']) ?>
                                </p>
                                <p class="mb-2">
                                    <i class="bi bi-mortarboard text-muted me-2"></i>
                                    Diplôme: <?= htmlspecialchars($cours['diplome']) ?>
                                </p>
                                <p class="mb-2">
                                    <i class="bi bi-briefcase text-muted me-2"></i>
                                    Domaine: <?= htmlspecialchars($cours['domaine']) ?>
                                </p>
                            </div>
                        </div>

                        <div class="course-meta">
                            <h6 class="mb-3">Informations du cours</h6>
                            <p class="mb-2">
                                <i class="bi bi-calendar3 text-muted me-2"></i>
                                Date de création: <?= date('d/m/Y', strtotime($cours['date_creation'])) ?>
                            </p>
                            <p class="mb-2">
                                <i class="bi bi-tag text-muted me-2"></i>
                                Catégorie: <?= htmlspecialchars($cours['categorie']) ?>
                            </p>
                        </div>

                        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'etudiant'): 
                            $estInscrit = $unController->isInscritCours($_SESSION['user']['id'], $_GET['id']);
                        ?>
                            <div class="mt-4">
                                <form method="POST" action="src/controllers/inscription_cours.php">
                                    <input type="hidden" name="id_cours" value="<?= $_GET['id'] ?>">
                                    <input type="hidden" name="id_etudiant" value="<?= $_SESSION['user']['id'] ?>">
                                    <input type="hidden" name="action" value="<?= $estInscrit ? 'desinscription' : 'inscription' ?>">
                                    
                                    <button type="submit" class="btn btn-<?= $estInscrit ? 'danger' : 'primary' ?> w-100">
                                        <i class="bi bi-bookmark-<?= $estInscrit ? 'dash' : 'plus' ?> me-2"></i>
                                        <?= $estInscrit ? 'Se désinscrire du cours' : "S'inscrire au cours" ?>
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
