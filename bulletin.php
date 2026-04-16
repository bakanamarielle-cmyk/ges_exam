<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['etudiant_id'])) {
    header("Location: ../connexion.php");
    exit();
}

$etudiant_id = $_SESSION['etudiant_id'];

// Récupération infos étudiant
$stmt = $pdo->prepare("SELECT nom, prenom, sexe, date_naissance, option_etude, niveau_etude FROM etudiants WHERE id = ?");
$stmt->execute([$etudiant_id]);
$etudiant = $stmt->fetch();

// Récupération notes et matières
$sql = "SELECT n.note, m.nom_matiere, m.coefficient 
        FROM notes n 
        JOIN matieres m ON n.matiere_id = m.id 
        WHERE n.etudiant_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$etudiant_id]);
$notes = $stmt->fetchAll();

function calculerMention($moyenne) {
    if ($moyenne >= 16) return "Très Bien";
    if ($moyenne >= 14) return "Bien";
    if ($moyenne >= 12) return "Assez Bien";
    if ($moyenne >= 10) return "Passable";
    return "Insuffisant";
}

// Calcul moyenne
$total_notes = 0;
$total_coeffs = 0;
foreach ($notes as $note) {
    $total_notes += $note['note'] * $note['coefficient'];
    $total_coeffs += $note['coefficient'];
}
$moyenne = $total_coeffs > 0 ? round($total_notes / $total_coeffs, 2) : 0;
$mention = calculerMention($moyenne);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Mon Bulletin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Pour éviter que le menu fixe cache le contenu */
        main {
            padding-top: 4.5rem; /* Ajuste selon la hauteur de ton menu */
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-blue-100 to-blue-200 min-h-screen flex flex-col">

    <?php include 'menu.php'; ?>
   
    <div style="margin: 20px 0;"></div>
    <main class="max-w-5xl mx-auto px-6 py-10 flex-grow">

        <div class="bg-white rounded-xl shadow-xl p-8">
            <h1 class="text-3xl font-bold text-blue-800 mb-6">📋 Bulletin de notes</h1>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8 text-gray-700">
                <p><strong>Nom :</strong> <?= htmlspecialchars($etudiant['nom']) ?></p>
                <p><strong>Prénom :</strong> <?= htmlspecialchars($etudiant['prenom']) ?></p>
                <p><strong>Sexe :</strong> <?= htmlspecialchars($etudiant['sexe']) ?></p>
                <p><strong>Date de naissance :</strong> <?= htmlspecialchars($etudiant['date_naissance']) ?></p>
                <p><strong>Option :</strong> <?= htmlspecialchars($etudiant['option_etude']) ?></p>
                <p><strong>Niveau :</strong> <?= htmlspecialchars($etudiant['niveau_etude']) ?></p>
            </div>

            <table class="w-full table-auto border border-gray-300 mb-8 text-sm md:text-base">
                <thead class="bg-blue-200 text-blue-900">
                    <tr>
                        <th class="border px-4 py-2 text-left">Matière</th>
                        <th class="border px-4 py-2 text-center">Note</th>
                        <th class="border px-4 py-2 text-center">Coef</th>
                        <th class="border px-4 py-2 text-center">Note x Coef</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($notes) > 0): ?>
                        <?php foreach ($notes as $note): ?>
                            <tr class="even:bg-blue-50">
                                <td class="border px-4 py-2"><?= htmlspecialchars($note['nom_matiere']) ?></td>
                                <td class="border px-4 py-2 text-center"><?= number_format($note['note'], 2) ?></td>
                                <td class="border px-4 py-2 text-center"><?= $note['coefficient'] ?></td>
                                <td class="border px-4 py-2 text-center"><?= number_format($note['note'] * $note['coefficient'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="bg-blue-100 font-semibold">
                            <td colspan="3" class="border px-4 py-2 text-right">Moyenne générale</td>
                            <td class="border px-4 py-2 text-center"><?= number_format($moyenne, 2) ?></td>
                        </tr>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center text-red-600 py-6">Aucune note disponible.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="text-center 
                <?= $moyenne >= 16 ? 'bg-green-100 text-green-800' : '' ?>
                <?= $moyenne >= 14 && $moyenne < 16 ? 'bg-green-200 text-green-700' : '' ?>
                <?= $moyenne >= 12 && $moyenne < 14 ? 'bg-yellow-100 text-yellow-800' : '' ?>
                <?= $moyenne >= 10 && $moyenne < 12 ? 'bg-yellow-200 text-yellow-700' : '' ?>
                <?= $moyenne < 10 ? 'bg-red-100 text-red-700' : '' ?>
                rounded-lg py-4 font-semibold text-lg">
                ✨ Mention : <?= htmlspecialchars($mention) ?>
            </div>

        </div>

    </main>

</body>
</html>
