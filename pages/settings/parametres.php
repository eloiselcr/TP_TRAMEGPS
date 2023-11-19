<?php

session_start();
include("../../bdd/bdd.php");
include("../../classes/fonctions.php");

$pseudo = $_SESSION['id_utilisateur'];
$admin = $_SESSION['admin'];

// Vérifier si l'utilisateur est connecté via la session
if (!isset($_SESSION['id_utilisateur'])) {
    header('location: ../../index.php');
    exit;
}

// Si l'utilisateur souhaite se déconnecter
if (isset($_POST['deconnexion'])) {
    Users::Deconnexion(); // Appel de la fonction "Deconnexion" dans la Class User
    header('location: ../../index.php'); // Redirection vers la page de connexion
    exit;
}

// Si l'utilisateur souhaite modifier le pseudo
if (isset($_POST['modifier_pseudo'])) {
    $nouveaupseudo = $_POST['new_pseudo'];
    $result = Users::ModifierPseudo($pseudo, $nouveaupseudo);

    if ($result === true) {
        // La modification du pseudo a réussi
        header('location: parametres.php'); // Redirection vers la page parametres.php
        exit;
    } else {
        // La modification a échoué, vous pouvez gérer l'erreur ici
        echo "Erreur : " . $result;
    }
}

// Si l'utilisateur souhaite modifier le mot de passe
if (isset($_POST['modifier_mdp'])) {
    $nouveaumdp = $_POST['new_mdp'];
    $result = Users::ModifierMotDePasse($pseudo, $nouveaumdp);

    if ($result === true) {
        // La modification du mot de passe a réussi
        header('location: parametres.php'); // Redirection vers la page parametres.php
        exit;
    } else {
        // La modification a échoué, vous pouvez gérer l'erreur ici
        echo "Erreur : " . $result;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Logitek - Paramètres</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../../bootstrap_requirements/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../../bootstrap_requirements/assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="../../bootstrap_requirements/assets/vendors/select2/select2.min.css">
    <link rel="stylesheet" href="../../bootstrap_requirements/assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="../../bootstrap_requirements/assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="../../bootstrap_requirements/assets/images/favicon.png" />
  </head>
  <body>
    <div class="container-scroller">
      <!-- partial:../../bootstrap_requirements/partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
          <a class="sidebar-brand brand-logo" href="../accueil/accueil.php"><img src="../../bootstrap_requirements/assets/images/logo.svg" alt="logo" /></a>
          <a class="sidebar-brand brand-logo-mini" href="../accueil/accueil.php"><img src="../../bootstrap_requirements/assets/images/logo-mini.svg" alt="logo" /></a>
        </div>
        <ul class="nav">
          <li class="nav-item profile">
            <div class="profile-desc">
              <div class="profile-pic">
                <div class="count-indicator">
                  <img class="img-xs rounded-circle " src="../../bootstrap_requirements/assets/images/faces/face15.jpg" alt="">
                  <span class="count bg-success"></span>
                </div>
                <div class="profile-name">
                  <h5 class="mb-0 font-weight-normal"><?php echo $pseudo ?></h5>
                  <span>Espace Membre</span>
                </div>
              </div>
            </div>
          </li>
  
          <li class="nav-item nav-category">
            <span class="nav-link">Navigation</span>
          </li>

          <li class="nav-item menu-items"> <!-- Lien vers l'accueil -->
            <a class="nav-link" href="../accueil/accueil.php">
              <span class="menu-icon">
                <i class="mdi mdi-speedometer"></i>
              </span>
              <span class="menu-title">Accueil</span>
            </a>
          </li>


          <?php if ($admin == true) : ?> <!-- Espace Admin si Admin = 1 -->
          <li class="nav-item menu-items">
            <a class="nav-link" href="../tables/gestion.php">
              <span class="menu-icon">
                <i class="mdi mdi-table-large"></i>
              </span>
              <span class="menu-title">Gestion Admin</span>
            </a>
          </li>
          <?php endif; ?>

        </ul>
      </nav>

      <!-- partial // BARRE DU HAUT -->

      <div class="container-fluid page-body-wrapper">
        <!-- partial:../../bootstrap_requirements/partials/_navbar.html -->
        <nav class="navbar p-0 fixed-top d-flex flex-row">
          <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
            <a class="navbar-brand brand-logo-mini" href="../accueil/accueil.php"><img src="../../bootstrap_requirements/assets/images/logo-mini.svg" alt="logo" /></a>
          </div>
          <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
              <span class="mdi mdi-menu"></span>
            </button>
            <ul class="navbar-nav navbar-nav-right">
              
              <li class="nav-item dropdown">
                <a class="nav-link" id="profileDropdown" href="#" data-bs-toggle="dropdown">
                  <div class="navbar-profile">
                    <img class="img-xs rounded-circle" src="../../bootstrap_requirements/assets/images/faces/face15.jpg" alt="">
                    <p class="mb-0 d-none d-sm-block navbar-profile-name"><?php echo $pseudo ?></p>
                    <i class="mdi mdi-menu-down d-none d-sm-block"></i>
                  </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="profileDropdown">
                <h6 class="p-3 mb-0">Profil</h6>
                <div class="dropdown-divider"></div>
                <!-- Bouton "Paramètres" transformé en lien -->
                <a class="dropdown-item preview-item" href="../settings/parametres.php">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-dark rounded-circle">
                      <i class="mdi mdi-settings text-success"></i>
                    </div>
                  </div>
                  <div class="preview-item-content">
                    <p class="preview-subject mb-1">Paramètres</p>
                  </div>
                </a>

                <!-- Bouton "Déconnexion" transformé en formulaire POST -->
                <form method="post" class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-dark rounded-circle">
                      <i class="mdi mdi-logout text-danger"></i>
                    </div>
                  </div>
                  <div class="preview-item-content">
                    <button type="submit" name="deconnexion" class="btn btn-link preview-subject mb-1" style="color: #dc3545;">Déconnexion</button>
                  </div>
                </form>
              </div>
            </li>
          </ul>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-format-line-spacing"></span>
          </button>
        </div>
      </nav>

        <!-- partial -->

        <div class="main-panel">
          <div class="content-wrapper">
            <div class="page-header">
              <h3 class="page-title"> Gestion de votre compte </h3>
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a>Settings</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Parametres</li>
                </ol>
              </nav>
            </div>

            <div class="row"> <!-- Changer de pseudo -->
              <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Changer de Pseudo</h4>
                    <p class="card-description"> C'est ici ! </p>

                    <form class="forms-sample">
                      <div class="form-group">
                        <label for="exampleInputUsername1">Nouveau Pseudo</label>
                        <input type="text" class="form-control" id="new_pseudo" placeholder="Choissisez...">
                      </div>

                      <button type="submit" class="btn btn-primary me-2">Envoyer</button>
                      <button class="btn btn-dark">Annuler</button>

                    </form>
                  </div>
                </div>
              </div>

              <div class="col-md-6 grid-margin stretch-card"> <!-- Changer le mdp -->
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Changer de mot de passe</h4>
                    <p class="card-description"> C'est ici ! </p>
                    
                    <form class="forms-sample">
                      <div class="form-group">
                        <label for="exampleInputUsername1">Nouveau Mot de passe</label>
                        <input type="text" class="form-control" id="new_mdp" placeholder="Choissisez...">
                      </div>
                      
                      <button type="submit" class="btn btn-primary me-2">Envoyer</button>
                      <button class="btn btn-dark">Annuler</button>

                    </form>
                  </div>
                </div>
              </div>
              
            </div>
          </div>
          <!-- content-wrapper ends -->
          <!-- partial:../../bootstrap_requirements/partials/_footer.html -->
          <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
              <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © Logitek</span>
            </div>
          </footer>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="../../bootstrap_requirements/assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="../../bootstrap_requirements/assets/vendors/select2/select2.min.js"></script>
    <script src="../../bootstrap_requirements/assets/vendors/typeahead.js/typeahead.bundle.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="../../bootstrap_requirements/assets/js/off-canvas.js"></script>
    <script src="../../bootstrap_requirements/assets/js/hoverable-collapse.js"></script>
    <script src="../../bootstrap_requirements/assets/js/misc.js"></script>
    <script src="../../bootstrap_requirements/assets/js/settings.js"></script>
    <script src="../../bootstrap_requirements/assets/js/todolist.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="../../bootstrap_requirements/assets/js/file-upload.js"></script>
    <script src="../../bootstrap_requirements/assets/js/typeahead.js"></script>
    <script src="../../bootstrap_requirements/assets/js/select2.js"></script>
    <!-- End custom js for this page -->
  </body>
</html>