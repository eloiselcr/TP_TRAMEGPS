<?php
session_start();
include "BDD.php";
include "connexion.php";
include "Fonction.php";
Deconnexion();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page</title>
    <link rel="stylesheet" href="main.css">
    <!-- Assure-toi que Leaflet est inclus ici -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>

<body>

<nav>
    <ul>
        <li><a href="Ajouter.php">Ajouter un User</a></li>
        <li><a href="Update.php">Modifier un User</a></li>
        <li><a href="delete.php">Supprimer un User</a></li>
        <li style="float: left;margin-top: 20px;">
            <form method="POST">
                <input type="submit" name="Exit" value="Se deconnecter">
            </form>
        </li>
    </ul>
</nav>

<!-- Ajout de la balise div avec l'id "map" -->
<div id="map" style="height: 400px;"></div>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
    var map = L.map('map').setView([0, 0], 2); // Initialise la carte avec un zoom de 2

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

// Utilisez PHP pour récupérer la dernière donnée GPS de la base de données
<?php
$sql = "SELECT Longitude, Latitude, Heure FROM GPS ORDER BY Heure"; // Sélectionne le dernier enregistrement
$result = $BasePDO->query($sql);

if ($result === false) {
    die('Erreur SQL : ' . $BasePDO->errorInfo()[2]);
}

// Récupérez la seule ligne résultat, s'il y en a
if ($row = $result->fetch()) {
    // Utilisez un marqueur standard pour représenter la position GPS
    echo "L.marker([" . $row['Latitude'] . ", " . $row['Longitude'] . "]).addTo(map).bindPopup('Heure : " . $row['Heure'] . "');";
}
 // Utilisez L.polyline pour afficher la courbe sur la carte
 echo "L.polyline(" . json_encode($coordinates) . ").addTo(map);";
?>

</script>
</body>
</html>
