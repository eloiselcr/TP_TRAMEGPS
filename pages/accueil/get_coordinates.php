<?php

// Fichier pour récupérer les coordonnées dans la BDD

include("../../bdd/bdd.php");

// Sélectionner les 5 dernières entrées de la table GPS triées par l'heure décroissante
$sql = "SELECT id, Longitude, Latitude, Heure FROM GPS ORDER BY Heure DESC LIMIT 5";
$result = $GLOBALS["pdo"]->query($sql);

// Vérifier si la requête a échoué
if ($result === false) {
    die('Erreur SQL : ' . $GLOBALS["pdo"]->errorInfo()[2]);
}

// Init tableau pour coordonnées
$coordinates = [];

// Parcourir les résultats de la requête + ajout au tableau coordinates
while ($row = $result->fetch()) {
    // Ajouter l'id, la latitude, la longitude et l'heure dans le tableau $coordinates
    $coordinates[] = [
        'id' => (int)$row['id'], 
        'latitude' => (float)$row['Latitude'],
        'longitude' => (float)$row['Longitude'],
        'heure' => $row['Heure']
    ];
}

// Convertir le tableau $coordinates en format JSON et l'afficher
echo json_encode($coordinates);

?>
