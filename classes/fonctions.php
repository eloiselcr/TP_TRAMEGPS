<?php

class Users
{
    private $id;
    private $pseudo;
    private $mdp;
    private $Admin;

    public function __construct($id, $pseudo, $mdp, $Admin)
    {
        $this->id = $id;
        $this->pseudo = $pseudo;
        $this->mdp = $mdp;
        $this->Admin = $Admin;
    }


    // -- Méthodes : accès aux propriétés --


    public function getId()
    {
        return $this->id;
    }

    public function getpseudo()
    {
        return $this->pseudo;
    }

    public function getmdp()
    {
        return $this->mdp;
    }

    public function getAdmin()
    {
        return $this->Admin;
    }


    // -- Méthodes : fonctions pour interagir avec l'Users --

    // 1) Inscription d'un Users
    public function Inscription($pseudo, $mdp)
    {
        // Vérifier si le pseudo existe pas déjà
        $sql = "SELECT * FROM Users WHERE pseudo = '" . $pseudo . "'";
        $result = $GLOBALS["pdo"]->query($sql);

        // Vérification si personne n'a le même pseudo que celui rentré 
        if ($result && $result->rowCount() > 0) {
            return "Un utilisateur avec le même pseudo existe déjà.";
        }

        // Insérer le nouvel Users dans la BDD
        $sql = "INSERT INTO Users (pseudo, mdp) VALUES ('$pseudo', '$mdp')";

        if ($GLOBALS["pdo"]->exec($sql) !== false) {
            return true; // Inscription réussie
        } else {
            return "Erreur lors de l'inscription.";
        }
    }


    // 2) Autorisation pour que l'Users accès au site + vérification s'il est Admin ou pas
    public function Verification($pseudo, $mdp)
    {
        // Recherche de l'Users dans la BDD avec le pseudo
        $sql = "SELECT * FROM Users WHERE pseudo = '" . $pseudo . "'";
        $result = $GLOBALS["pdo"]->query($sql);

        if ($result && $result->rowCount() > 0) {
            // L'Users existe, vérifie le mot de passe
            $sql = "SELECT * FROM Users WHERE pseudo = '" . $pseudo . "' AND mdp = '" . $mdp . "'";
            $result = $GLOBALS["pdo"]->query($sql);

            if ($result && $result->rowCount() > 0) {
                // Authentification réussie
                $UsersData = $result->fetch(PDO::FETCH_ASSOC);

                // Stocker le nom d'User dans la session
                $_SESSION['id_Users'] = $UsersData['pseudo'];

                if ($UsersData['admin'] == 1) {
                    $_SESSION['admin'] = true;
                } else {
                    // Sinon c'est un utilisateur normal
                    $_SESSION['admin'] = false;
                }
                return true;
            }
        }
        return false; // Authentification échouée
    }

    // 3) Modifier le pseudo d'un utilisateur
    public function ModifierPseudo($pseudo, $nouveaupseudo)
    {
        $sql = "UPDATE Users SET pseudo = '$nouveaupseudo' WHERE pseudo = '$pseudo'";
        if ($GLOBALS["pdo"]->exec($sql) !== false) {
            return true; // Modification réussie
        } else {
            return "Erreur lors de la modification du pseudo de l'utilisateur.";
        }
    }

    // 4) Modifier le mot de passe d'un utilisateur
    public function ModifierMotDePasse($pseudo, $nouveaumdp)
    {
        $sql = "UPDATE Users SET mdp = '$nouveaumdp' WHERE pseudo = '$pseudo'";
        if ($GLOBALS["pdo"]->exec($sql) !== false) {
            return true; // Modification réussie
        } else {
            return "Erreur lors de la modification du mot de passe de l'utilisateur.";
        }
    }

    // 5) Supprimer un User
    public function SupprimerUsers($pseudo)
    {
        $sql = "DELETE FROM Users WHERE pseudo = '$pseudo'";
        if ($GLOBALS["pdo"]->exec($sql) !== false) {
            echo '<script>setTimeout(function(){ window.location = "admin.php"; }, 2000);</script>';
            return true; // Suppression réussie
        } else {
            return "Erreur lors de la suppression de l'utilisateur.";
        }
    }


    // 6) Déconnecter l'User
    public function Deconnexion()
    {
        session_unset();
        session_destroy();
        return true; // Déconnexion réussie

        // Supprime également le cookie de session
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
    }

    // 7) Afficher tous les users dans un tableau
    public static function AfficherTableauUtilisateurs()
    {
        $sql = "SELECT id, pseudo, mdp, admin FROM Users"; // On récupère tous les utilisateurs de la BDD
        $result = $GLOBALS["pdo"]->query($sql);

        if ($result && $result->rowCount() > 0) { // Config pour le tableau
            echo '<div class="table-responsive">';
            echo '<form method="post" action="">'; // Formulaire pour les champs de saisie
            echo '<table class="table table-bordered">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>id</th>'; // id
            echo '<th>Pseudo</th>'; // Pseudo
            echo '<th>Admin</th>'; // Admin ou pas
            echo '<th>Nouveau Pseudo</th>'; // Changer Pseudo
            echo '<th></th>'; // Bouton Supprimer
            echo '</thead>';
            echo '<tbody>';

            $count = 1; // Initialisation du compteur

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>';
                echo '<td>' . $count++ . '</td>';
                echo '<td>' . $row['pseudo'] . '</td>';
                echo '<td>' . ($row['admin'] ? 'Oui' : 'Non') . '</td>'; // Affichage Oui/Non pour Admin

                echo '<td>';
                echo '<div class="input-group">';
                echo '<input type="text" class="form-control" id="new_pseudo_' . $row['id'] . '" name="new_pseudo_' . $row['id'] . '" placeholder="Nouveau Pseudo...">';
                echo '<div class="input-group-append">';
                echo '<button class="btn btn-primary" name="appliquer_pseudo_' . $row['id'] . '">Appliquer Pseudo</button>';
                echo '</div>';
                echo '</div>';
                echo '</td>';

                echo '<td>';
                echo '<form method="post" action="">';
                echo '<input type="hidden" name="pseudoToDelete" value="' . $row['pseudo'] . '">';
                echo '<button type="submit" class="btn btn-danger" name="supprimer">Supprimer</button>';
                echo '</form>';
                echo '</td>';

                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
            echo '</form>'; // Fin du formulaire
            echo '</div>';
        } else {
            echo 'Aucun utilisateur trouvé.';
        }
    }


    public static function getPseudoById($userID)
    {
        $sql = "SELECT pseudo FROM Users WHERE id = '$userID'";
        $result = $GLOBALS["pdo"]->query($sql);

        if ($result && $result->rowCount() > 0) {
            $userData = $result->fetch(PDO::FETCH_ASSOC);
            return $userData['pseudo'];
        } else {
            return null;
        }
    }
}
