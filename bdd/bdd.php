<?php
$ipServerSQL ="192.168.1.56"; // A modifier !
$NomBase = "BASE_TramesGPS";
$userBDD = "root";
$PassBDD = "root";

try {
    $GLOBALS["pdo"] = new PDO('mysql:host='.$ipServerSQL.';dbname='.$NomBase.';port=3306',$userBDD, $PassBDD);
} catch (Exception $e) {
    echo $e->getMessage();
}
?>
