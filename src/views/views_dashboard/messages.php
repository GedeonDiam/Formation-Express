<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .messages-container {
            height: calc(100vh - 250px);
            overflow-y: auto;
        }
        .chat-sidebar {
            height: calc(100vh - 100px);
            overflow-y: auto;
        }
        .user-item {
            cursor: pointer;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .user-item:hover {
            background-color: #f8f9fa;
        }
        .user-item.active {
            background-color: #e9ecef;
        }
        .message-bubble {
            max-width: 75%;
            padding: 10px;
            border-radius: 15px;
            margin-bottom: 10px;
        }
        .message-sent {
            background-color: #007bff;
            color: white;
            margin-left: auto;
        }
        .message-received {
            background-color: #e9ecef;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="row">
            <!-- Liste des utilisateurs -->
            <div class="col-md-3">
                <div class="card chat-sidebar">
                    <div class="card-header">
                        <h5 class="mb-0">Conversations</h5>
                    </div>
                    <div class="card-body p-0">
                        <?php
                        // Simuler une liste d'utilisateurs pour l'exemple
                        $users = [
                            ['id' => 1, 'nom' => 'John Doe', 'role' => 'Étudiant'],
                            ['id' => 2, 'nom' => 'Jane Smith', 'role' => 'Enseignant'],
                        ];

                        foreach($users as $user): ?>
                            <div class="user-item d-flex align-items-center">
                                <div class="rounded-circle bg-primary text-white p-2 me-2">
                                    <?php echo substr($user['nom'], 0, 2); ?>
                                </div>
                                <div>
                                    <h6 class="mb-0"><?php echo $user['nom']; ?></h6>
                                    <small class="text-muted"><?php echo $user['role']; ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Zone de chat -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary text-white p-2 me-2">JD</div>
                            <h6 class="mb-0">John Doe</h6>
                        </div>
                    </div>
                    <div class="card-body messages-container">
                        <!-- Messages exemple -->
                        <div class="message-bubble message-received">
                            <p class="mb-1">Bonjour, comment allez-vous ?</p>
                            <small class="text-muted">10:30</small>
                        </div>
                        <div class="message-bubble message-sent">
                            <p class="mb-1">Très bien, merci !</p>
                            <small class="text-white-50">10:31</small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <form class="d-flex gap-2">
                            <input type="text" class="form-control" placeholder="Écrivez votre message...">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

