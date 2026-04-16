<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: connexion.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM etudiants ORDER BY nom ASC");
$etudiants = $stmt->fetchAll();

// Extraire options et niveaux uniques
$options = array_unique(array_column($etudiants, 'option_etude'));
$niveaux = array_unique(array_column($etudiants, 'niveau_etude'));
sort($options);
sort($niveaux);
?>

<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <title>Gérer Étudiants</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
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

<main class="flex-grow ml-64 p-8 overflow-auto animate-fade-in">

    <header class="mb-10 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <h2 class="text-4xl font-extrabold text-blue-900 drop-shadow-sm">👨‍🎓 Liste des Étudiants</h2>
        <a href="ajouter_etudiant.php" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow-md font-semibold transition">
            + Ajouter un étudiant
        </a>
    </header>

    <section class="flex flex-col md:flex-row gap-4 mb-8">
        <input type="text" id="searchInput" placeholder="🔍 Rechercher nom ou matricule..." 
            class="flex-grow md:flex-shrink-0 md:w-1/3 px-4 py-3 rounded-lg border border-blue-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />

        <select id="filterOption" 
            class="md:w-1/4 px-4 py-3 rounded-lg border border-blue-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Toutes les options</option>
            <?php foreach ($options as $opt): ?>
                <option value="<?= htmlspecialchars($opt ?? '') ?>"><?= htmlspecialchars($opt ?? '') ?></option>
            <?php endforeach; ?>
        </select>

        <select id="filterNiveau" 
            class="md:w-1/4 px-4 py-3 rounded-lg border border-blue-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Tous les niveaux</option>
            <?php foreach ($niveaux as $niv): ?>
                <option value="<?= htmlspecialchars($niv ?? '') ?>"><?= htmlspecialchars($niv ?? '') ?></option>
            <?php endforeach; ?>
        </select>
    </section>

    <section class="overflow-x-auto rounded-lg shadow-lg bg-white">
        <table class="min-w-full border-collapse border border-blue-200">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="p-4 text-left border border-blue-400">Nom</th>
                    <th class="p-4 text-left border border-blue-400">Prénom</th>
                    <th class="p-4 text-left border border-blue-400">Matricule</th>
                    <th class="p-4 text-left border border-blue-400">Sexe</th>
                    <th class="p-4 text-left border border-blue-400">Date de naissance</th>
                    <th class="p-4 text-left border border-blue-400">Option</th>
                    <th class="p-4 text-left border border-blue-400">Niveau</th>
                    <th class="p-4 text-center border border-blue-400">Actions</th>
                </tr>
            </thead>
            <tbody id="etudiantsTableBody" class="text-gray-800">
                <?php foreach ($etudiants as $e): ?>
                    <tr class="hover:bg-blue-50 transition">
                        <td class="p-4 border border-blue-300"><?= htmlspecialchars($e['nom'] ?? '') ?></td>
                        <td class="p-4 border border-blue-300"><?= htmlspecialchars($e['prenom'] ?? '') ?></td>
                        <td class="p-4 border border-blue-300"><?= htmlspecialchars($e['matricule'] ?? '') ?></td>
                        <td class="p-4 border border-blue-300"><?= htmlspecialchars($e['sexe'] ?? 'Non défini') ?></td>
                        <td class="p-4 border border-blue-300">
                            <?= isset($e['date_naissance']) && $e['date_naissance'] ? htmlspecialchars(date('d/m/Y', strtotime($e['date_naissance']))) : 'Non définie' ?>
                        </td>
                        <td class="p-4 border border-blue-300"><?= htmlspecialchars($e['option_etude'] ?? '') ?></td>
                        <td class="p-4 border border-blue-300"><?= htmlspecialchars($e['niveau_etude'] ?? '') ?></td>
                        <td class="p-4 border border-blue-300 text-center space-x-3">
                            <a href="modifier_etudiant.php?id=<?= $e['id'] ?>" class="text-blue-700 hover:text-blue-900 font-semibold">Modifier</a>
                            <span class="text-gray-300">|</span>
                            <a href="supprimer_etudiant.php?id=<?= $e['id'] ?>" onclick="return confirm('Supprimer cet étudiant ?')" class="text-red-600 hover:text-red-800 font-semibold">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

</main>

<script>
    const searchInput = document.getElementById("searchInput");
    const filterOption = document.getElementById("filterOption");
    const filterNiveau = document.getElementById("filterNiveau");
    const tableBody = document.getElementById("etudiantsTableBody");
    const rows = Array.from(tableBody.getElementsByTagName("tr"));

    function filterTable() {
        const search = searchInput.value.toLowerCase();
        const option = filterOption.value.toLowerCase();
        const niveau = filterNiveau.value.toLowerCase();

        rows.forEach(row => {
            const nom = row.cells[0].textContent.toLowerCase();
            const prenom = row.cells[1].textContent.toLowerCase();
            const matricule = row.cells[2].textContent.toLowerCase();
            const opt = row.cells[5].textContent.toLowerCase();
            const niv = row.cells[6].textContent.toLowerCase();

            const matchSearch = nom.includes(search) || prenom.includes(search) || matricule.includes(search);
            const matchOption = !option || opt === option;
            const matchNiveau = !niveau || niv === niveau;

            row.style.display = (matchSearch && matchOption && matchNiveau) ? "" : "none";
        });
    }

    searchInput.addEventListener("input", filterTable);
    filterOption.addEventListener("change", filterTable);
    filterNiveau.addEventListener("change", filterTable);
</script>

</body>
</html>
