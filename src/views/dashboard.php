<?php
// Récupère le paramètre 'menu' de l'URL
$menu = isset($_GET['menu']) ? $_GET['menu'] : 'accueil';
?>

<style>
    /* Fixe la barre latérale à gauche */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: 250px;
        background-color: #343a40;
        color: white;
        transition: width 0.3s;
    }

    /* Animation pour élargir la barre latérale */
    .sidebar:hover {
        width: 300px;
    }

    /* Fixe le contenu principal à droite */
    .content {
        margin-left: 250px;
        /* Égal à la largeur initiale de la sidebar */
        padding: 20px;
        transition: margin-left 0.3s;
    }

    /* Ajustement si la barre latérale s'élargit */
    .sidebar:hover~.content {
        margin-left: 300px;
    }

    .sidebar .nav-link {
        color: white;
    }

    .sidebar .nav-link:hover {
        background-color: #1abc9c;
    }
</style>
</head>

<body>

    <!-- Barre latérale (fixée à gauche) -->
    <div class="sidebar d-flex flex-column p-4">
        <a href="#" class="d-flex align-items-center mb-3 text-white text-decoration-none">
            <span class="fs-4">Menu</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="index.php?page=dashboard&menu=accueil" class="nav-link <?php echo ($menu === 'accueil') ? 'active' : 'text-white'; ?>">
                    Dashboard
                </a>
            </li>
            <li>
                <a href="index.php?page=dashboard&menu=cours" class="nav-link <?php echo ($menu === 'cours') ? 'active' : 'text-white'; ?>">
                    Cours
                </a>
            </li>
           
            <li>
                <a href="index.php?page=dashboard&menu=quiz" class="nav-link <?php echo ($menu === 'quiz') ? 'active' : 'text-white'; ?>">
                    Quiz
                </a>
            </li>
        </ul>
        <hr>
        <?php
        if (isset($_SESSION['user'])) {
            echo '
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" 
           id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
            <strong>'.$_SESSION['user']['nom'].'</strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
          
            <li><a class="dropdown-item" href="./src/views/profil.php">Profil</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="index.php?page=deconnexion">Déconnexion</a></li>
        </ul>
    </div>';
        }


        ?>
    </div>

    <!-- Contenu principal (fixé à droite) -->
    <div class="content">
        <?php
        switch ($menu) {
            case 'cours':
                include 'views_dashboard/cours.php';
                break;
            case 'accueil':
                include 'views_dashboard/accueil.php';
                break;
            case 'messages':
                include 'views_dashboard/messages.php';
                break;
            case 'quiz':
                include 'views_dashboard/quiz.php';
                break;
            default:
                include 'views_dashboard/accueil.php';
                break;
        }
        ?>
    </div>
</body>

</html>