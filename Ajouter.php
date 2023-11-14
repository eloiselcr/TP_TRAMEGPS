<?php
session_start();	// Nous connect à la base de donnee
include "BDD.php";
include "Fonction.php";

Deconnexion();

Verif();


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout d'utilisateur</title>
    <link rel="stylesheet" href="main.css">

</head>
<body class="bodyCo">
<?php
     
    if(isset($_POST["AjoutSubmit"])){
        if(!empty($_POST["pseudo"]) && !empty($_POST["MDP"])){

            $pseudo = $_POST["pseudo"];
            $MDP = $_POST["MDP"];
            $isAdmin = isset($_POST['admin']) ? 1 : 0;

            // Ajoutez "1 " ou "0 " devant le pseudo en fonction de la case à cocher "admin"
            $pseudoAjuste = $isAdmin ? '1 ' . $pseudo : '0 ' . $pseudo;

            $req = "INSERT INTO `user`(`pseudo`, `MDP`, `admin`) VALUES (?,?,?)";
            $RequeteStatement = $BasePDO->prepare($req);
            $RequeteStatement->execute([$pseudo, $MDP, $isAdmin]);
            
            $_SESSION['id'] = $userData['id'];
            $_SESSION['pseudo'] = $userData['pseudo'];
            $_SESSION['MDP'] = $userData['MDP'];
            $_SESSION['admin'] = $isAdmin;

            // Afficher les détails de l'utilisateur
            echo "<h2>Vous avez ajoute:</h2>";
            echo "<p><strong>Nom :</strong> $pseudo</p>";
            echo "<p><strong>Est-il administrateur? :</strong> " . afficherStatutAdmin($isAdmin) . "</p>";

        }
        else{
                echo "echec d'ajout</p1>";
            }
        if ($RequeteStatement === false) {
                die('Erreur SQL : ' . $BasePDO->errorInfo()[2]);
        }
            
            
    }
    
?>
    <H1>Ajout d'utilisateur </H1>
    <div class="formulaire">
        <form action="" id="login-form" method="post">
            <h2 style="text-align:center;">Inscription</h2>
            <div class="group-form">
               <input type="text" class="fat" name="pseudo" id="pseudo" required>
               <label for="pseudo">Pseudo</label>
            </div>
            <div class="group-form">
                <input type="password"   class="fat" name="MDP" id="MDP" required>
                <label for="MDP" class="text-info">Mot De Passe</label>
            </div>
            <div>
                <input type="checkbox" class ="fat" id="isAdmin" name="admin"><br><br>
                <label for="admin">Est administrateur:</label>
            </div>
            <div class="group-form">
                <input type="submit" name="AjoutSubmit" value="Ajouter un user" class="fat">
            </div>
           
        </form>
    </div>

    
</body>
</html>
