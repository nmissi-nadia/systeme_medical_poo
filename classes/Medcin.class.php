<?php 
include_once('Utilisateur.class.php');
    class Medecin extends Utilisateur {
        private $specialite;
    
        public function __construct($nom, $prenom, $specialite) {
            parent::__construct($nom, $prenom, 'médcin');
            $this->specialite = $specialite;
        }
    
        public function consulterPatient(Patient $patient) {
            return "Consultation avec " . $patient->afficherNomComplet();
        }
    }
?>