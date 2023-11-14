<?php
$host = '192.168.64.200';
$user = 'debian';
$pass = 'debian';
$db = 'BDD';
$conn = new mysqli($host,$user,$pass,$db) or die($mysqli->error);

if (!$conn) {
 die('Could not connect: ' . mysql_error());
}
$sql  = 'SELECT * FROM user';
if ($result = $conn->query($sql)) {
   while ( $row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}
$conn->close();
?>