<?php 

class Utilisateur{
    // les variables
    public $nom;
    public $prenom;
    public $type_utilisateur;

    // les methodes 

    // Constructeur
    public function __construct($nom1, $prenom1, $type_utilisateur1) {
        $this->nom = $nom1;
        $this->prenom = $prenom1;
        $this->type_utilisateur = $type_utilisateur1;
    }

    public function afficherNomComplet(){
        return $this->prenom . ' ' . $this->nom;
    }

    public function changerNom($nv_nom){
        $this->nom=$nv_nom;
    }

    public function changerpreNom($nv_prenom){
        $this->prenom=$nv_prenom;
    }

}
?>