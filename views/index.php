<?php
// Connexion à la base de données
require "../config/dv_connect.php";

session_start();
$id_patient = $_SESSION['id_patient'] ?? 1; 

$query_patient = "SELECT nom, prenom FROM users WHERE id_users = ? AND type_user = 'patient'";
$stmt_patient = $conn->prepare($query_patient);
$stmt_patient->execute([$id_patient]);
$patient = $stmt_patient->fetch(PDO::FETCH_ASSOC);

if (!$patient) {
    die("Patient non trouvé ou non connecté.");
}

//  rdv
$query_rdv = "SELECT r.date_rdv, r.statut, u.nom AS medecin, u.prenom AS medecin_prenom 
              FROM rdv r
              JOIN users u ON r.id_medcin = u.id_users
              WHERE r.id_patient = ?";
$stmt_rdv = $conn->prepare($query_rdv);
$stmt_rdv->execute([$id_patient]);
$rendezvous = $stmt_rdv->fetchAll(PDO::FETCH_ASSOC);

$query_factures = "SELECT f.date_facture, f.montant, r.date_rdv 
                   FROM facture f
                   JOIN rdv r ON f.id_redv = r.id_rdv
                   WHERE r.id_patient = ?";
$stmt_factures = $conn->prepare($query_factures);
$stmt_factures->execute([$id_patient]);
$factures = $stmt_factures->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Patient</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

    <header class="bg-blue-600 text-white p-4">
        <h1 class="text-2xl font-bold">Bienvenue, <?= htmlspecialchars($patient['prenom']) ?> <?= htmlspecialchars($patient['nom']) ?> !</h1>
    </header>

    <main class="p-6">

        <!-- formulaire d'ajout d'un réservation  -->
         <section>
         <h2 class="text-xl font-semibold text-blue-600 mb-4">Réserver un Rendez-vous</h2>
        <form class="flex flex-col w-[500px] justify-self-center" action="ajoute_reservation.php?id_patient=<?php echo "$id_patient";?>" method="post">
            <label for="Médcins">
            <select class="w-[400px] h-10 m-5" name="medcin" id="medcin">
                <option value="default">Choisir Votre Médecin</option>
                <?php 
                    
                    $medecin = $conn->prepare("SELECT * 
                                            FROM users
                                            WHERE type_user='médcin'");
                    $medecin->execute();
                
                    while ($row = $medecin->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$row['id_users']}'>{$row['nom']} {$row['prenom']}</option>";
                    }
                ?>
                </select>

            <label for="date_res">
            <input class="w-[400px] h-10 m-5" placeholder="Date Réservation" type="date" name="date-rdv" id="dt_rdv">
            <input class="bg-purple-500 rounded-lg h-10 w-[100px]" type="submit" value="Réserver">
        </form>
         </section>
        
        <!-- rdv -->
        <section class="mb-6">
            <h2 class="text-xl font-semibold text-blue-600 mb-4">Vos Rendez-vous</h2>
            <?php if (count($rendezvous) > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white shadow-md rounded-lg">
                        <thead class="bg-purple-500 text-white">
                            <tr>
                                <th class="py-2 px-4 text-left">Date</th>
                                <th class="py-2 px-4 text-left">Statut</th>
                                <th class="py-2 px-4 text-left">Médecin</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rendezvous as $rdv): ?>
                                <tr class="border-b">
                                    <td class="py-2 px-4"><?= htmlspecialchars($rdv['date_rdv']) ?></td>
                                    <td class="py-2 px-4"><?= htmlspecialchars($rdv['statut']) ?></td>
                                    <td class="py-2 px-4"><?= htmlspecialchars($rdv['medecin']) ?> <?= htmlspecialchars($rdv['medecin_prenom']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-gray-600">Aucun rendez-vous trouvé.</p>
            <?php endif; ?>
        </section>

        <!-- facture -->
        <section>
            <h2 class="text-xl font-semibold text-blue-600 mb-4">Vos Factures</h2>
            <?php if (count($factures) > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white shadow-md rounded-lg">
                        <thead class="bg-blue-500 text-white">
                            <tr>
                                <th class="py-2 px-4 text-left">Date de Facture</th>
                                <th class="py-2 px-4 text-left">Montant</th>
                                <th class="py-2 px-4 text-left">Date du Rendez-vous</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($factures as $facture): ?>
                                <tr class="border-b">
                                    <td class="py-2 px-4"><?= htmlspecialchars($facture['date_facture']) ?></td>
                                    <td class="py-2 px-4"><?= htmlspecialchars(number_format($facture['montant'], 2)) ?> MAD</td>
                                    <td class="py-2 px-4"><?= htmlspecialchars($facture['date_rdv']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-gray-600">Aucune facture trouvée.</p>
            <?php endif; ?>
        </section>
    </main>

    <footer class="bg-gray-800 text-white text-center py-4 mt-6 mb-0">
        <p>Centre Médical &copy; <?= date('Y') ?></p>
    </footer>
</body>
</html>
