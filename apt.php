





 <?php 
Session_start();

include "BDD.php";
include "Fonction.php";

Deconnexion();

Verif();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["deleteSelected"])) {
        // Supprimer les utilisateurs sélectionnés
        if (isset($_POST["selectedIds"]) && is_array($_POST["selectedIds"])) {
            foreach ($_POST["selectedIds"] as $userId) {
                $deleteQuery = "DELETE FROM user WHERE id = :id";

                try {
                    $stmt = $BasePDO->prepare($deleteQuery);
                    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
                    $stmt->execute();
                } catch (Exception $e) {
                    echo "Erreur : " . $e->getMessage();
                }
            }
            echo "Les utilisateurs sélectionnés ont été supprimés.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page </title>
    <link rel="stylesheet" href="main.css">
    <script>
        function toggleSelectAll() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            const selectAllCheckbox = document.getElementById('selectAll');

            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        }
    </script>
</head>
<body>
<?php
// Inclure le fichier BDD.php pour obtenir la connexion à la base de données
include 'BDD.php';

// Requête SQL SELECT
$query = "SELECT * FROM user";

?>
 <!-- Formulaire pour supprimer un utilisateur -->

 <!-- Affichage du tableau des utilisateurs -->
 <table border='1'>
        <tr><th>choisir</th><th>ID</th><th>pseudo</th><th>MDP</th><th>admin</th></tr>
        <form method="POST" action="">
        
        <tr>
           
            <?php
            $result = $BasePDO->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                
                echo "<td><input type='checkbox' name='selectedIds[]' value='" . $row['id'] . "'></td>";
                
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['pseudo'] . "</td>";
            echo "<td>" . $row['MDP'] . "</td>";
            echo "<td>" . $row['admin'] . "</td>";
            echo "</tr>";
            }
            ?>
        </tr>
        <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
        <label for="selectAll">Select All</label>
    </table>
    
    <!-- Bouton pour supprimer les utilisateurs sélectionnés -->
    <input type="submit" name="deleteSelected" value="Delete Selected">
    </form>
</body>
</html>
<script src="index.js"></script>



<!-- 
  
if(isset($_POST["connexionSubmit"])){


    $requete = "SELECT * FROM user";
$resultat = $connexion->query($requete);
if ($resultat === false) {
    die("Erreur d'exécution de la requête : " . $connexion->error);
    echo "hello";
}
echo "it works";

// Afficher les données récupérées


}

@import url(https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700);
->
