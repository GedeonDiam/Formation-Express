<?php
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'enseignant') {
    header('Location: index.php?page=connexion');
    exit();
}

$message = '';
$error = '';

if (isset($_POST['updateProfile'])) {
    $data = [
        'nom' => $_POST['nom'],
        'telephone' => $_POST['telephone'],
        'email' => $_POST['email'],
        'diplome' => $_POST['diplome'],
        'domaine' => $_POST['domaine']
    ];

    try {
        if ($unController->updateEnseignantProfile($_SESSION['user']['id'], $data)) {
            $_SESSION['user'] = array_merge($_SESSION['user'], $data);
            $message = "Profil mis à jour avec succès!";
        }
    } catch (Exception $e) {
        $error = "Erreur lors de la mise à jour: " . $e->getMessage();
    }
}

if (isset($_POST['updatePassword'])) {
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    $enseignant = $unController->getEnseignantById($_SESSION['user']['id']);

    if (password_verify($currentPassword, $enseignant['mdp'])) {
        if ($newPassword === $confirmPassword) {
            if ($unController->updatePassword($_SESSION['user']['id'], $newPassword, 'enseignant')) {
                $message = "Mot de passe mis à jour avec succès!";
            } else {
                $error = "Erreur lors de la mise à jour du mot de passe.";
            }
        } else {
            $error = "Les nouveaux mots de passe ne correspondent pas.";
        }
    } else {
        $error = "Mot de passe actuel incorrect.";
    }
}

$enseignant = $unController->getEnseignantById($_SESSION['user']['id']);
?>

<div class="container mt-5">
    <?php if ($message): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-6">
            <h3>Modifier le profil</h3>
            <form method="POST" class="mt-4">
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom complet</label>
                    <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($enseignant['nom']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="telephone" class="form-label">Téléphone</label>
                    <input type="tel" class="form-control" id="telephone" name="telephone" value="<?= htmlspecialchars($enseignant['telephone']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($enseignant['email']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="diplome" class="form-label">Diplôme</label>
                    <input type="text" class="form-control" id="diplome" name="diplome" value="<?= htmlspecialchars($enseignant['diplome']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="domaine" class="form-label">Domaine d'expertise</label>
                    <input type="text" class="form-control" id="domaine" name="domaine" value="<?= htmlspecialchars($enseignant['domaine']) ?>" required>
                </div>
                <button type="submit" name="updateProfile" class="btn btn-primary">Mettre à jour le profil</button>
            </form>
        </div>

        <div class="col-md-6">
            <h3>Changer le mot de passe</h3>
            <form method="POST" class="mt-4">
                <div class="mb-3">
                    <label for="currentPassword" class="form-label">Mot de passe actuel</label>
                    <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
                </div>
                <div class="mb-3">
                    <label for="newPassword" class="form-label">Nouveau mot de passe</label>
                    <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                </div>
                <div class="mb-3">
                    <label for="confirmPassword" class="form-label">Confirmer le nouveau mot de passe</label>
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                </div>
                <button type="submit" name="updatePassword" class="btn btn-primary">Changer le mot de passe</button>
            </form>
        </div>
    </div>
</div>
