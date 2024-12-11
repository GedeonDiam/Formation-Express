<?php
class Modele {
    private $pdo;
    public function __construct() {
        $serveur = "localhost";
        $bdd = "BTS_Express.sql";
        $user="root";
        $mdp="";

        try {
            $this->pdo = new PDO("mysql:host=$serveur;dbname=$bdd;charset=utf8", $user, $mdp);
        } catch (PDOException $ex) {
           echo "Erreur de connexion à la base de données";
        }
    }
   
}
?>