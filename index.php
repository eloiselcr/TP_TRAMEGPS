<?php

session_start();
include "bdd/bdd.php";
include "classes/fonctions.php";

$message; // Variable utilisée en cas d'erreur(s)

if (isset($_POST['connexion'])) { // Connexion
    $pseudo = $_POST['user_holder'];
    $mdp = $_POST['mdp_holder'];

    // Vérifier l'authentification
    if (Users::Verification($pseudo, $mdp)) {
        // Stocker l'ID de l'utilisateur dans la session
        $_SESSION['id_utilisateur'] = $pseudo;

        // Redirection de l'utilisateur vers la page d'accueil
        header('location: pages/accueil/accueil.php');
        exit();
    } 
    else {
        $message = "Erreur d'authentification. Veuillez réessayer.";
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Logiteck</title>
 
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <link href="index.css" rel="stylesheet">

</head>
<body>
    <div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <form class="user" method="post">
        <h3>Se connecter</h3>

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control form-control-user" id="user_holder" name="user_holder" placeholder="Entrez votre Username...">
        </div>

        <div class="form-group">
            <label for="mdp">Mot de passe</label>
            <input type="password" class="form-control form-control-user" id="mdp_holder" name="mdp_holder" placeholder="Entrez votre Mot de Passe...">
            
            <input type="submit" class="btn" name="connexion" value="Connexion">
            <a class="btn" href="inscription.php">Inscription</a>
        </div>
    </form>

</body>
</html>