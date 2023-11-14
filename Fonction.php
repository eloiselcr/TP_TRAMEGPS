<?php
function Deconnexion(){
    if (isset($_POST['Exit'])) {
        session_unset();
        session_destroy();
        }
}
function Verif(){
    if ((empty($_SESSION['id'])) || (empty($_SESSION['pseudo'])) || (empty($_SESSION['MDP']))) {
        header('Location: connexion.php');
    }
}
// Fonction pour afficher le statut de l'utilisateur en tant qu'administrateur ou non
function afficherStatutAdmin($isAdmin) {
    if ($isAdmin) {
        return "Oui";
    } else {
        return "Non";
    }
}
?>
 <h1 style="text-align: center;"> <b>Logitok<b></h1>