<?php
// Connexion à la base de données
require "../config/dv_connect.php";

session_start();

// Gestion de la soumission du formulaire
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Vérification des champs
    if (!empty($email) && !empty($password)) {
        // Requête pour trouver l'utilisateur
        $query = "SELECT id_users, nom, prenom, type_user FROM users WHERE email = ? AND password = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$email, $password]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Stockage des données de session
            $_SESSION['id_patient'] = $user['id_users'];
            $_SESSION['type_user'] = $user['type_user'];
            $_SESSION['nom'] = $user['nom'];
            $_SESSION['prenom'] = $user['prenom'];

            // Redirection selon le type d'utilisateur
            if ($user['type_user'] === 'patient') {
                header('Location: dashboard_patient.php');
            } elseif ($user['type_user'] === 'médcin') {
                header('Location: dashboard_medcin.php');
            }
            exit;
        } else {
            $error = 'Identifiants incorrects.';
        }
    } else {
        $error = 'Veuillez remplir tous les champs.';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
        <h1 class="text-2xl font-bold text-center text-blue-600 mb-4">Connexion</h1>
        <?php if ($error): ?>
            <p class="text-red-500 text-sm mb-4"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form action="" method="POST" class="space-y-4">
            <div>
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" id="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div>
                <label for="password" class="block text-gray-700">Mot de passe</label>
                <input type="password" id="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">Se connecter</button>
        </form>
        <p class="text-gray-600 text-center mt-4">Pas encore inscrit ? <a href="register.php" class="text-blue-500">Créer un compte</a></p>
    </div>
</body>
</html>
