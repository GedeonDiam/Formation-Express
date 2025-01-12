<?php
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'etudiant') {
    header('Location: index.php?page=connexion');
    exit();
}

$etudiant = $unController->getEtudiantById($_SESSION['user']['id']);
$coursInscrits = $unController->getCoursInscrits($_SESSION['user']['id']);
$statsTemps = $unController->getTotalTempsParCategorie($_SESSION['user']['id']);
$tempsTotal = $unController->getTotalTemps($_SESSION['user']['id']);

// Traitement de la mise à jour du profil
if (isset($_POST['updateProfile'])) {
    $data = [
        'nom' => $_POST['nom'],
        'telephone' => $_POST['telephone'],
        'email' => $_POST['email'],
        'specialite' => $_POST['specialite']
    ];
    
    if ($unController->updateEtudiantProfile($_SESSION['user']['id'], $data)) {
        header("Location: index.php?page=accueil");

        $_SESSION['user']['nom'] = $data['nom'];
    }
}

// Traitement du changement de mot de passe
if (isset($_POST['updatePassword'])) {
    if (password_verify($_POST['currentPassword'], $etudiant['mdp'])) {
        if ($_POST['newPassword'] === $_POST['confirmPassword']) {
            if ($unController->updatePassword($_SESSION['user']['id'], $_POST['newPassword'])) {
                $messagePassword = "Mot de passe modifié avec succès";
            }
        } else {
            $errorPassword = "Les nouveaux mots de passe ne correspondent pas";
        }
    } else {
        $errorPassword = "Mot de passe actuel incorrect";
    }
}
?>

<div class="container my-5">


    <div class="row">
        <!-- Informations personnelles -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Mes informations</h4>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label>Nom complet</label>
                            <input type="text" class="form-control" name="nom" value="<?= $etudiant['nom'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Téléphone</label>
                            <input type="tel" class="form-control" name="telephone" value="<?= $etudiant['telephone'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" value="<?= $etudiant['email'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Spécialité</label>
                            <input type="text" class="form-control" name="specialite" value="<?= $etudiant['specialite'] ?>" required>
                        </div>
                        <button type="submit" name="updateProfile" class="btn btn-primary">Mettre à jour</button>
                    </form>
                </div>
            </div>

            <!-- Changement de mot de passe -->
            <div class="card">
                <div class="card-header">
                    <h4>Changer mon mot de passe</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($messagePassword)): ?>
                        <div class="alert alert-success"><?= $messagePassword ?></div>
                    <?php endif; ?>
                    <?php if (isset($errorPassword)): ?>
                        <div class="alert alert-danger"><?= $errorPassword ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label>Mot de passe actuel</label>
                            <input type="password" class="form-control" name="currentPassword" required>
                        </div>
                        <div class="mb-3">
                            <label>Nouveau mot de passe</label>
                            <input type="password" class="form-control" name="newPassword" required>
                        </div>
                        <div class="mb-3">
                            <label>Confirmer le nouveau mot de passe</label>
                            <input type="password" class="form-control" name="confirmPassword" required>
                        </div>
                        <button type="submit" name="updatePassword" class="btn btn-warning">Changer le mot de passe</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Statistiques et cours -->
        <div class="col-md-6">

            <!-- Cours inscrits -->
            <div class="card">
                <div class="card-header">
                    <h4>Mes cours</h4>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <?php foreach ($coursInscrits as $cours): ?>
                            <a href="index.php?page=detail_cours&id=<?= $cours['id'] ?>" 
                               class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1"><?= $cours['titre'] ?></h5>
                                    <small>Inscrit le <?= date('d/m/Y', strtotime($cours['date_inscription'])) ?></small>
                                </div>
                                <p class="mb-1">Par <?= $cours['nom_enseignant'] ?></p>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
