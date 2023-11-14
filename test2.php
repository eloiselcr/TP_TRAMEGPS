<?php
session_start();

include "BDD.php";
include "Fonction.php";

Deconnexion();

Verif();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["updateSelected"])) {
        // Mettre à jour les utilisateurs sélectionnés
        if (isset($_POST["selectedIds"])) {

            foreach ($_POST["selectedIds"] as $userId) {
                // Récupérer les valeurs des champs de formulaire
                $newPseudo = $_POST["pseudo_" . $userId];
                $newMDP = $_POST["MDP_" . $userId];
                $newAdmin = isset($_POST["admin_" . $userId]) ? 1 : 0;

                // Mettre à jour les valeurs dans la base de données
                $updateQuery = "UPDATE user SET pseudo = :pseudo, MDP = :MDP, admin = :admin WHERE id = :id";

                try {
                    $stmt = $BasePDO->prepare($updateQuery);
                    $stmt->execute([
                        ':id' => $userId,
                        ':pseudo' => $newPseudo,
                        ':MDP' => $newMDP,
                        ':admin' => $newAdmin
                    ]);
                    
                } catch (Exception $e) {
                    echo "Erreur : " . $e->getMessage();
                }
            }
            echo "Les utilisateurs sélectionnés ont été mis à jour.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page</title>
    <link rel="stylesheet" href="delete.css">
</head>
<body>
<?php
// Inclure le fichier BDD.php pour obtenir la connexion à la base de données
include 'BDD.php';

// Requête SQL SELECT
$query = "SELECT * FROM user";

?>
<!-- Formulaire pour mettre à jour un utilisateur -->

<!-- Affichage du tableau des utilisateurs -->
<form method="POST" action="">
    <table border='3' style="background-color:white; margin:auto;">
        <tr>
            <th>choisir</th>
            <th>ID</th>
            <th>pseudo</th>
            <th>MDP</th>
            <th>admin</th>
        </tr>
        <?php
        $result = $BasePDO->query($query);
        while ($row = $result->fetch()) {
            echo "<tr>";
            echo "<td><input type='checkbox' name='selectedIds[]' value='" . $row['id'] . "'></td>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td><input type='text' name='pseudo_" . $row['id'] . "' value='" . $row['pseudo'] . "'></td>";
            echo "<td><input type='text' name='MDP_" . $row['id'] . "' value='" . $row['MDP'] . "'></td>";
            echo "<td><input type='checkbox' name='admin_" . $row['id'] . "' value='1' " . ($row['admin'] == 1 ? 'checked' : '') . "></td>";
            echo "</tr>";
        }
        ?>
    </table>
    <!-- Ajout du champ pour les selectedIds -->
    <input type="hidden" name="selectedIds[]" value="">
    <!-- Bouton pour mettre à jour les utilisateurs sélectionnés -->
    <input style="margin-left:45%;" type="submit" name="updateSelected" value="Update Selected">
</form>
</body>
</html>
