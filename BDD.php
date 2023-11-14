<?php
$ipserver = "192.168.65.195"; // A modifier si vous travaillez chez vous
$bdd = "BASE";
$login_bdd = "root";
$password = "root";

try {
    $pdo = new PDO('mysql:host=' . $ipserver . ';dbname=' . $bdd . '', $login_bdd, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

?>