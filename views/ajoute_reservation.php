<?php
// Inclure le fichier de connexion
require_once '../config/dv_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    $id_patient = $_GET['id_patient']; 
    $id_medcin = $_POST['medcin'];
    $date_rdv = $_POST['date-rdv']; 

    if ($id_medcin !== 'default' && !empty($date_rdv)) {
        try {
            $stmt = $conn->prepare("
                INSERT INTO rdv (id_patient, id_medcin, date_rdv)
                VALUES (:id_patient, :id_medcin, :date_rdv)
            ");

            $stmt->bindParam(':id_patient', $id_patient, PDO::PARAM_INT);
            $stmt->bindParam(':id_medcin', $id_medcin, PDO::PARAM_INT);
            $stmt->bindParam(':date_rdv', $date_rdv, PDO::PARAM_STR);

            if ($stmt->execute()) {
                echo "<script>alert('Le rendez-vous a été ajouté avec succès.');</script>";
                header("Location :./index.php");
            } else {
                echo "<script>alert('Erreur : Impossible d'ajouter le rendez-vous.');</script>";
            }
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
    } else {
        echo "Veuillez remplir tous les champs correctement.";
    }
} else {
    echo "Requête invalide.";
}
?>
