
<body>
    <div class="container mt-5">
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'enseignant'): ?>
            <h1 class="mb-4">Profil Enseignant</h1>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Informations personnelles</h5>
                            <dl class="row mt-3">
                                <dt class="col-sm-4">Nom</dt>
                                <dd class="col-sm-8"><?= $getProfilEnseignant['nom'] ?? 'Non renseigné' ?></dd>

                                <dt class="col-sm-4">Téléphone</dt>
                                <dd class="col-sm-8"><?= $getProfilEnseignant['telephone'] ?? 'Non renseigné' ?></dd>

                                <dt class="col-sm-4">Email</dt>
                                <dd class="col-sm-8"><?= $getProfilEnseignant['email'] ?? 'Non renseigné' ?></dd>

                                <dt class="col-sm-4">Diplôme</dt>
                                <dd class="col-sm-8"><?= $getProfilEnseignant['diplome'] ?? 'Non renseigné' ?></dd>

                                <dt class="col-sm-4">Domaine</dt>
                                <dd class="col-sm-8"><?= $getProfilEnseignant['domaine'] ?? 'Non renseigné' ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <h1 class="mb-4">Profil Étudiant</h1>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Informations personnelles</h5>
                            <dl class="row mt-3">
                                <dt class="col-sm-4">Nom</dt>
                                <dd class="col-sm-8"><?= $getProfilEtudiant['nom'] ?? 'Non renseigné' ?></dd>

                                <dt class="col-sm-4">Téléphone</dt>
                                <dd class="col-sm-8"><?= $getProfilEtudiant['telephone'] ?? 'Non renseigné' ?></dd>

                                <dt class="col-sm-4">Email</dt>
                                <dd class="col-sm-8"><?= $getProfilEtudiant['email'] ?? 'Non renseigné' ?></dd>

                                <dt class="col-sm-4">Spécialité</dt>
                                <dd class="col-sm-8"><?= $getProfilEtudiant['specialite'] ?? 'Non renseigné' ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
