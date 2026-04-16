<?php
session_start();
require_once '../config/database.php';

// Vérification de la connexion admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: connexion.php");
    exit;
}

// Récupération des matières
$stmt = $pdo->query("SELECT * FROM matieres ORDER BY nom_matiere ASC");
$matieres = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <title>Gérer les matières</title>
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
        /* Scrollbar menu latéral */
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
    
    <!-- Contenu principal -->
    <main class="flex-grow ml-64 p-8 overflow-auto animate-fade-in">

        <header class="mb-10 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <h2 class="text-4xl font-extrabold text-blue-900 drop-shadow-sm">📚 Liste des Matières</h2>
            <a href="ajouter_matiere.php" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow-md font-semibold transition">
                + Ajouter une matière
            </a>
        </header>

        <!-- Barre de recherche -->
        <section class="mb-6">
            <input type="text" id="searchInput" placeholder="🔍 Rechercher une matière ou une option..." 
                   class="w-full md:w-1/2 px-4 py-3 rounded-lg border border-blue-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </section>

        <!-- Tableau des matières -->
        <section class="overflow-x-auto rounded-lg shadow-lg bg-white">
            <?php if (empty($matieres)): ?>
                <div class="p-6 text-center text-yellow-700 bg-yellow-100 rounded-lg font-semibold">
                    Aucune matière enregistrée pour le moment.
                </div>
            <?php else: ?>
            <table class="min-w-full border-collapse border border-blue-200">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="p-4 text-left border border-blue-400">Nom de la matière</th>
                        <th class="p-4 text-left border border-blue-400">Option</th>
                        <th class="p-4 text-left border border-blue-400">Coefficient</th>
                        <th class="p-4 text-center border border-blue-400">Actions</th>
                    </tr>
                </thead>
                <tbody id="matieresTableBody" class="text-gray-800">
                    <?php foreach ($matieres as $m): ?>
                        <tr class="hover:bg-blue-50 transition">
                            <td class="p-4 border border-blue-300"><?= htmlspecialchars($m['nom_matiere']) ?></td>
                            <td class="p-4 border border-blue-300"><?= htmlspecialchars($m['option_etude']) ?></td>
                            <td class="p-4 border border-blue-300"><?= htmlspecialchars($m['coefficient']) ?></td>
                            <td class="p-4 border border-blue-300 text-center space-x-3">
                                <a href="modifier_matiere.php?id=<?= $m['id'] ?>" 
                                   class="text-blue-700 hover:text-blue-900 font-semibold">Modifier</a>
                                <span class="text-gray-300">|</span>
                                <a href="supprimer_matiere.php?id=<?= $m['id'] ?>" 
                                   onclick="return confirm('Supprimer cette matière ?')" 
                                   class="text-red-600 hover:text-red-800 font-semibold">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </section>

    </main>

    <script>
        const searchInput = document.getElementById("searchInput");
        const tableBody = document.getElementById("matieresTableBody");
        const rows = Array.from(tableBody.getElementsByTagName("tr"));

        searchInput.addEventListener("input", () => {
            const search = searchInput.value.toLowerCase();

            rows.forEach(row => {
                const nom = row.cells[0].textContent.toLowerCase();
                const option = row.cells[1].textContent.toLowerCase();

                const match = nom.includes(search) || option.includes(search);

                row.style.display = match ? "" : "none";
            });
        });
    </script>

</body>
</html>
