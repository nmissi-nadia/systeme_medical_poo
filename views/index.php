
<?php
include_once('../classes/Utilisateur.class.php');
include_once('../classes/Patient.class.php');
include_once('../classes/Medcin.class.php');

$medecin_1 = new Medecin('nmissi', 'nadia', 'medecin', 'psycologie');

$afficherMessage = $medecin_1 -> afficherNomComplet();
echo $afficherMessage;
echo "<br>";

$patient_1 = new Patient("el ouah", "fadwa", "patient", "24-01-2030");

$afficherMessage_2 = $patient_1 -> afficherNomComplet();
echo $afficherMessage_2;
echo "<br>";

$medecin_1 -> consulterPatient($afficherMessage_2);


?>
