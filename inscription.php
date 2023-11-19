<?php

session_start();
include "bdd/bdd.php";
include "classes/fonctions.php";

$message; // Variable utilisée en cas d'erreur(s)

// Traitement du formulaire d'inscription
if (isset($_POST['inscription'])) {
    $pseudo = $_POST['user_holder'];
    $mdp = $_POST['mdp_holder'];

    // On effectue l'inscription en utilisant la fonction "Inscription" de la Class User
    $result = Users::Inscription($pseudo, $mdp);

    if ($result === true) {
        if (Users::Verification($pseudo, $mdp)) {
            // Stocker l'ID de l'utilisateur dans la session
            $_SESSION['id_utilisateur'] = $pseudo;
    
            // Redirection de l'utilisateur vers la page d'accueil
            header('location: pages/accueil/accueil.php');
            exit();
        } 
    } else {
        $message = "Erreur d'inscription : $result";
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
        <h3>S'inscrire</h3>

        <div class="form-group">
            <label for="username">Pseudo</label>
            <input type="text" class="form-control form-control-user" id="user_holder" name="user_holder" placeholder="Choisir un Pseudo...">
        </div>

        <div class="form-group">
            <label for="mdp">Mot de passe</label>
            <input type="password" class="form-control form-control-user" id="mdp_holder" name="mdp_holder" placeholder="Choisir un Mot de Passe...">
            
            <input type="submit" class="btn" name="inscription" value="S'inscrire">
            <a class="btn" href="index.php">Connexion</a>
        </div>
    </form>

</body>
</html>