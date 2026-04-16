<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id']) || !isset($_GET['id'])) {
    header("Location: ../connexion.php");
    exit();
}

$id = (int) $_GET['id'];

// Récupération des infos actuelles de l'admin
$stmt = $pdo->prepare("SELECT nom_utilisateur FROM admins WHERE id = ?");
$stmt->execute([$id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admin) {
    echo "Administrateur introuvable.";
    exit();
}

$message = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_utilisateur = trim($_POST['nom_utilisateur'] ?? '');
    $mot_de_passe = trim($_POST['mot_de_passe'] ?? '');

    if (!empty($nom_utilisateur)) {
        if (!empty($mot_de_passe)) {
            // Mise à jour avec mot de passe
            $hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE admins SET nom_utilisateur = ?, mot_de_passe = ? WHERE id = ?");
            $stmt->execute([$nom_utilisateur, $hash, $id]);
        } else {
            // Mise à jour sans changer le mot de passe
            $stmt = $pdo->prepare("UPDATE admins SET nom_utilisateur = ? WHERE id = ?");
            $stmt->execute([$nom_utilisateur, $id]);
        }

        $message = "Informations mises à jour avec succès.";
        // Rafraîchir les données
        $admin['nom_utilisateur'] = $nom_utilisateur;
    } else {
        $message = "Veuillez remplir le champ nom d'utilisateur.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Administrateur</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-900 to-blue-500 min-h-screen text-white">
    <nav class="bg-blue-800 p-4 shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-semibold">Modifier Administrateur</h1>
            <a href="gerer_admins.php" class="text-sm hover:underline">← Retour à la liste</a>
        </div>
    </nav>

    <div class="container mx-auto mt-12 bg-white bg-opacity-90 text-gray-800 p-8 rounded-lg shadow-lg max-w-lg">
        <h2 class="text-2xl font-bold mb-6 text-blue-800">Modifier un administrateur</h2>

        <?php if ($message): ?>
            <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1">Nom d'utilisateur</label>
                <input type="text" name="nom_utilisateur" value="<?= htmlspecialchars($admin['nom_utilisateur']) ?>" class="w-full p-2 border border-gray-300 rounded">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold mb-1">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
                <input type="password" name="mot_de_passe" class="w-full p-2 border border-gray-300 rounded">
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full">
                Enregistrer les modifications
            </button>
        </form>
    </div>
</body>
</html>
