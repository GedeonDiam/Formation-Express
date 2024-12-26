<header>
  <div class="logo">
    <a href="index.php?page=accueil" style="text-decoration:none"> BoostSkills</a>
  </div>
  <nav>
    <a href="index.php?page=accueil">Accueil</a>
    <a href="index.php?page=cours">Cours</a>
    <a href="index.php?page=categorie">Catégories</a>
  </nav>
  <div class="">

  <?php
  
  if (isset($_SESSION["user"])) {
    echo '<div class="btn-group">
      <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-person-circle"></i> '.$_SESSION['user']['nom'].'
      </button>
      <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="index.php?page=profil">Profil</a></li>
        <li><a class="dropdown-item" href="index.php?page=deconnexion">Déconnexion</a></li>
      </ul>
    </div>';
  }else{
    echo '

<div class="btn-group">
      <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-person-circle"></i>
      </button>
      <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="index.php?page=connexion">Connexion</a></li>
        <li>
          <hr class="dropdown-divider">
        </li>
        <li class="ms-3"><i><b>Etudiants</b></i></li>
        <li><a class="dropdown-item" href="index.php?page=inscription_etudiants">Démarrer mon inscription</a></li>
        <li>
          <hr class="dropdown-divider">
        </li>
        <li class="ms-3"><i><b>Professeurs</b></i></li>
        <li><a class="dropdown-item" href="index.php?page=inscription_prof">Démarrer mon inscription</a></li>

      </ul>
    </div>
    ';
  }
?>
  </div>
</header>