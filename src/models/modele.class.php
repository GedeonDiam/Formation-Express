<?php
//src/models/modele.class.php
class Modele
{
    private $unPdo;

    // La fonction qui permet de se connecter à la base de données--------------------------------
    public function __construct($serveur, $bdd, $user, $mdp)
    {

        try {
            $this->unPdo = new PDO("mysql:host=$serveur;dbname=$bdd;charset=utf8", $user, $mdp);
        } catch (PDOException $ex) {
            echo "Erreur de connexion à la base de données";
        }
    }

    //--------------------------FIN CONNEXION BDD ------------------------------------------------------


    public function inscriptionEnseignants($tab)
    {
        // Validate email
        if (!filter_var($tab['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email format');
        }

        // Hash the password
        $tab['mdp'] = password_hash($tab['mdp'], PASSWORD_DEFAULT);

        $requete = "insert into enseignants values (null, :nom, :telephone, :email, :diplome, :domaine, :mdp);";
        $exec = $this->unPdo->prepare($requete);
        $exec->bindValue(':nom', $tab['nom'], PDO::PARAM_STR);
        $exec->bindValue(':telephone', $tab['telephone'], PDO::PARAM_STR);
        $exec->bindValue(':email', $tab['email'], PDO::PARAM_STR);
        $exec->bindValue(':diplome', $tab['diplome'], PDO::PARAM_STR);
        $exec->bindValue(':domaine', $tab['domaine'], PDO::PARAM_STR);
        $exec->bindValue(':mdp', $tab['mdp'], PDO::PARAM_STR);
        $exec->execute();
        
        return true;
    }

    public function getEnseignantByEmail($email)
{
    $requete = "SELECT * FROM enseignants WHERE email = :email";
    $exec = $this->unPdo->prepare($requete);
    $exec->bindValue(':email', $email, PDO::PARAM_STR);
    $exec->execute();
    return $exec->fetch(PDO::FETCH_ASSOC);
}

}
