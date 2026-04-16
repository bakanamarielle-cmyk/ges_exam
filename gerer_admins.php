<?php
session_start();
require_once '../config/database.php';

// Rediriger si l'admin n'est pas connecté
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../connexion.php");
    exit();
}

// Récupération des administrateurs
try {
    $stmt = $pdo->query("SELECT id, nom_utilisateur FROM admins");
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des administrateurs : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer les administrateurs</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen">

<!-- Menu latéral gauche -->
<?php include 'menu_admin.php';?>

<!-- Contenu principal -->
<div class="ml-64 p-6">
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Liste des administrateurs</h2>
            <a href="ajouter_admin.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Ajouter un administrateur</a>
        </div>

        <table class="min-w-full bg-white border border-gray-300">
            <thead class="bg-gray-200">
                <tr>
                    <th class="text-left py-2 px-4 border-b">ID</th>
                    <th class="text-left py-2 px-4 border-b">Nom d'utilisateur</th>
                    <th class="text-left py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($admins) > 0): ?>
                    <?php foreach ($admins as $admin): ?>
                        <tr class="hover:bg-gray-100">
                            <td class="py-2 px-4 border-b"><?= htmlspecialchars($admin['id']) ?></td>
                            <td class="py-2 px-4 border-b"><?= htmlspecialchars($admin['nom_utilisateur']) ?></td>
                            <td class="py-2 px-4 border-b space-x-2">
                                <a href="modifier_admin.php?id=<?= $admin['id'] ?>" class="text-blue-600 hover:underline">Modifier</a>
                                <a href="supprimer_admin.php?id=<?= $admin['id'] ?>" onclick="return confirm('Voulez-vous vraiment supprimer cet administrateur ?');" class="text-red-600 hover:underline">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="py-4 px-4 text-center">Aucun administrateur trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
