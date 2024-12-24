<?php
    class Patient extends Utilisateur{
        private $rendezvous;

        public __construct($nom,$prenom){
            parent ::__construct($nom,$prenom,'patient'){
                $this->rendez_vous[] = $date;
            }
        }

        public function afficherRendezVous() {
            return $this->rendez_vous;
        }
    }

?>