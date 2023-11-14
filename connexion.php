<?php
session_start();	// Nous connect à la base de donnee
include "BDD.php";

if ((!empty($_SESSION['id'])) AND (!empty($_SESSION['pseudo'])) AND (!empty($_SESSION['MDP'])))
{	//si l'utilisateur est connecte a une session
	header("Location: index.php");	//renvoie vers la page d'accueil
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="main.css">

</head>
<body class="bodyCo">
    <?php
    if(isset($_POST["connexionSubmit"])){
        if(!empty($_POST["pseudo"]) && !empty($_POST["MDP"])){

            $pseudo = $_POST["pseudo"];
            $MDP = $_POST["MDP"];

            $req = "SELECT * FROM user WHERE pseudo ='$pseudo' AND MDP='$MDP'";
            $RequeteStatement = $BasePDO->query($req);
            
            $userData = $RequeteStatement->fetch();
            if(!empty($userData['pseudo']) && !empty($userData['MDP'])){
                $_SESSION['id'] = $userData['id'];
                $_SESSION['pseudo'] = $userData['pseudo'];
                $_SESSION['MDP'] = $userData['MDP'];
                $_SESSION['isAdmin'] = $userData['isAdmin'];
                header("Location: index.php");

            }else{
                echo "<p1>Mauvais Identifiant et/ou Mot De Passe</p1>";
            }
           
        }
    }    
    ?>
    <H1>Connexion </H1>
    <div class="formulaire">
        <form action="" id="login-form" method="post">
            <h2 style="text-align:center;">Login</h2>
            <div class="group-form">
               <input type="text" class="fat" name="pseudo" id="pseudo" required>
               <label for="pseudo">first name</label>
            </div>
            <div class="group-form">
                <input type="password"   class="fat" name="MDP" id="MDP" required>
                <label for="MDP" class="text-info">Password</label>
            </div>
            <div class="group-form">
                <input type="submit" name="connexionSubmit" value="Connexion" class="fat">
            </div>
           
        </form>
    </div>

    
</body>
</html>
<!--
<body class="preload">
<div class="formulaire">
<form action="" id="login-form" method="post">
    <h1>Bienvenue</h1>
    <div class="group-form">
        <input type="text" class="fat" name="pseudo" id="pseudo" required>
        <label for="pseudo">First Name</label>
    </div>
    <div class="group-form">
        <input type="password" class="fat" name="MDP" id="MDP" required>
        <label for="MDP" class="text-info">Password</label>
    </div>
    <div class="group-form">
     <input type="submit" name="connexionSubmit" value="connexion" class="btnConnexion">
    </div>
</form>
</div>
<footer>
    <span class="upgrade">Version 0.0.1</span> -
    <span class="upgrade">@JulienMiclo</span>
</footer>

<script src="main.js"></script>
-->