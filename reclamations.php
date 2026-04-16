<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../connexion.php");
    exit();
}

// Marquer comme traitée
if (isset($_GET['traite'])) {
    $id = (int) $_GET['traite'];
    $stmt = $pdo->prepare("UPDATE reclamations SET statut = 'traitée' WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: reclamations.php");
    exit();
}

// Supprimer une réclamation
if (isset($_GET['supprimer'])) {
    $id = (int) $_GET['supprimer'];
    $stmt = $pdo->prepare("DELETE FROM reclamations WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: reclamations.php");
    exit();
}

// Récupérer les réclamations
$stmt = $pdo->query("
    SELECT r.id, r.message, r.statut, r.date_envoi, e.nom, e.prenom
    FROM reclamations r
    JOIN etudiants e ON r.etudiant_id = e.id
    ORDER BY r.date_envoi DESC
");
$reclamations = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <title>Réclamations Étudiants - Admin</title>
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

<!-- Menu latéral -->
<?php include 'menu_admin.php';?>

<!-- Contenu principal -->
<main class="flex-grow ml-64 p-8 overflow-auto animate-fade-in max-w-7xl">

    <header class="mb-10 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <h2 class="text-4xl font-extrabold text-blue-900 drop-shadow-sm">📩 Réclamations des étudiants</h2>
    </header>

    <?php if (empty($reclamations)): ?>
        <p class="text-center text-gray-700">Aucune réclamation trouvée.</p>
    <?php else: ?>
        <section class="overflow-x-auto bg-white rounded-xl shadow-md p-6">
            <table class="min-w-full text-left table-auto border-collapse border border-blue-300">
                <thead class="bg-blue-100 sticky top-0">
                    <tr>
                        <th class="border border-blue-300 px-4 py-2">Étudiant</th>
                        <th class="border border-blue-300 px-4 py-2">Message</th>
                        <th class="border border-blue-300 px-4 py-2">Date</th>
                        <th class="border border-blue-300 px-4 py-2 text-center">Statut</th>
                        <th class="border border-blue-300 px-4 py-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reclamations as $reclam): ?>
                        <tr class="hover:bg-blue-50 transition">
                            <td class="border border-blue-300 px-4 py-2"><?= htmlspecialchars($reclam['nom'] . ' ' . $reclam['prenom']) ?></td>
                            <td class="border border-blue-300 px-4 py-2"><?= nl2br(htmlspecialchars($reclam['message'])) ?></td>
                            <td class="border border-blue-300 px-4 py-2"><?= date('d/m/Y H:i', strtotime($reclam['date_envoi'])) ?></td>
                            <td class="border border-blue-300 px-4 py-2 text-center">
                                <span class="inline-block px-2 py-1 text-sm rounded-full 
                                    <?= $reclam['statut'] === 'traitée' ? 'bg-green-200 text-green-800' : 'bg-yellow-200 text-yellow-800' ?>">
                                    <?= ucfirst($reclam['statut']) ?>
                                </span>
                            </td>
                            <td class="border border-blue-300 px-4 py-2 text-center space-x-2">
                                <?php if ($reclam['statut'] !== 'traitée'): ?>
                                    <a href="?traite=<?= $reclam['id'] ?>" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">Traiter</a>
                                <?php else: ?>
                                    <span class="text-gray-400 text-sm italic">Traité</span>
                                <?php endif; ?>

                                <!-- Bouton supprimer -->
                                <a href="?supprimer=<?= $reclam['id'] ?>"
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réclamation ?');"
                                   class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                   Supprimer
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    <?php endif; ?>

</main>
</body>
</html>
