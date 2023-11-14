<?php
    $ipServerSQL ="192.168.65.195";
    $NomBase = "BASE";
    $userBDD = "root";
    $PassBDD = "root";
    try {
        $BasePDO = new PDO('mysql:host='.$ipServerSQL.';dbname='.$NomBase.';port=3306',$userBDD, $PassBDD);
    }catch (Exception $e) {
        echo $e->getMessage();
        }
    ?>