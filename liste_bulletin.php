<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: connexion.php");
    exit();
}

$recherche = trim($_GET['recherche'] ?? '');

$sql = "SELECT b.id AS bulletin_id, b.etudiant_id, b.moyenne, b.mention,
               e.nom, e.prenom, e.sexe, e.date_naissance, e.id AS matricule
        FROM bulletins b
        JOIN etudiants e ON b.etudiant_id = e.id";
if ($recherche) {
    $sql .= " WHERE e.nom LIKE :search OR e.prenom LIKE :search OR e.id LIKE :search";
}
$sql .= " ORDER BY e.nom ASC";

$stmt = $pdo->prepare($sql);
if ($recherche) {
    $stmt->bindValue(':search', '%' . $recherche . '%');
}
$stmt->execute();
$bulletins = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <title>Liste des Bulletins - Mon École</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .animate-fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background-color: #3b82f6;
            border-radius: 10px;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-blue-100 to-blue-200 min-h-screen flex">

<?php include 'menu_admin.php';?>

<main class="flex-grow ml-64 p-8 overflow-auto animate-fade-in max-w-7xl">

    <header class="mb-10 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <h2 class="text-4xl font-extrabold text-blue-900 drop-shadow-sm">📋 Liste des Bulletins</h2>
    </header>

    <section class="mb-6">
        <form method="GET" class="flex items-center space-x-4">
            <input type="text" name="recherche" placeholder="🔍 Rechercher nom, prénom ou matricule"
                   value="<?= htmlspecialchars($recherche) ?>"
                   class="px-4 py-2 w-full max-w-md border border-blue-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400" />
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition font-semibold">
                Rechercher
            </button>
            <?php if ($recherche): ?>
                <a href="liste_bulletin.php" class="text-blue-700 hover:underline font-semibold">❌ Réinitialiser</a>
            <?php endif; ?>
        </form>
    </section>

    <section class="overflow-x-auto bg-white rounded-xl shadow-md p-6">
        <table class="min-w-full text-left table-auto border-collapse border border-blue-300">
            <thead class="bg-blue-100 sticky top-0">
                <tr>
                    <th class="border border-blue-300 px-4 py-2">Matricule</th>
                    <th class="border border-blue-300 px-4 py-2">Nom et Prénom</th>
                    <th class="border border-blue-300 px-4 py-2">Sexe</th>
                    <th class="border border-blue-300 px-4 py-2">Date Naissance</th>
                    <th class="border border-blue-300 px-4 py-2">Moyenne</th>
                    <th class="border border-blue-300 px-4 py-2">Mention</th>
                    <th class="border border-blue-300 px-4 py-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="tbody-bulletins">
                <?php if (empty($bulletins)): ?>
                    <tr><td colspan="7" class="text-center py-6 text-gray-500">Aucun bulletin trouvé.</td></tr>
                <?php else: ?>
                    <?php foreach ($bulletins as $b): ?>
                        <tr class="hover:bg-blue-50 transition">
                            <td class="border border-blue-300 px-4 py-2"><?= htmlspecialchars($b['matricule']) ?></td>
                            <td class="border border-blue-300 px-4 py-2"><?= htmlspecialchars($b['nom'] . ' ' . $b['prenom']) ?></td>
                            <td class="border border-blue-300 px-4 py-2"><?= htmlspecialchars($b['sexe']) ?></td>
                            <td class="border border-blue-300 px-4 py-2"><?= htmlspecialchars($b['date_naissance']) ?></td>
                            <td class="border border-blue-300 px-4 py-2 font-semibold text-center"><?= htmlspecialchars($b['moyenne']) ?></td>
                            <td class="border border-blue-300 px-4 py-2 font-semibold text-center"><?= htmlspecialchars($b['mention']) ?></td>
                            <td class="border border-blue-300 px-4 py-2 text-center space-x-2 whitespace-nowrap">
                                <a href="bulletin_admin.php?id=<?= $b['etudiant_id'] ?>" class="inline-block px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition">👁️ Voir</a>
                                <a href="modifier_bulletin.php?id=<?= $b['bulletin_id'] ?>" class="inline-block px-3 py-1 bg-blue-400 text-white rounded hover:bg-blue-500 transition">✏️ Modifier</a>
                                <a href="supprimer_bulletin.php?id=<?= $b['bulletin_id'] ?>" onclick="return confirm('Supprimer ce bulletin ?');"class="inline-block px-3 py-1 bg-blue-600 text-white rounded hover:bg-red-600 transition">🗑️ Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </section>

</main>
</body>
</html>
