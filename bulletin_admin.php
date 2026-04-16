<?php
session_start();
require_once '../config/database.php';
require_once '../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Vérifier la connexion admin ou étudiant avec paramètre id
if (isset($_SESSION['admin_id']) && isset($_GET['id'])) {
    $etudiant_id = (int) $_GET['id'];
    $retour_url = "liste_bulletin.php";
} elseif (isset($_SESSION['etudiant_id'])) {
    $etudiant_id = $_SESSION['etudiant_id'];
    $retour_url = "../etudiant/bulletin.php";
} else {
    header("Location: ../connexion.php");
    exit();
}

// Récupérer les infos de l'étudiant
$stmt = $pdo->prepare("SELECT nom, prenom, sexe, date_naissance, option_etude, niveau_etude FROM etudiants WHERE id = ?");
$stmt->execute([$etudiant_id]);
$etudiant = $stmt->fetch();

if (!$etudiant) {
    echo "Étudiant non trouvé.";
    exit();
}

// Récupérer les notes
$sql = "SELECT m.nom_matiere, n.note, m.coefficient
        FROM notes n
        JOIN matieres m ON n.matiere_id = m.id
        WHERE n.etudiant_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$etudiant_id]);
$notes = $stmt->fetchAll();

if (!$notes) {
    echo "Aucune note trouvée pour cet étudiant.";
    exit();
}

// Calcul moyenne pondérée
$total = 0;
$coefTotal = 0;
foreach ($notes as $n) {
    $total += $n['note'] * $n['coefficient'];
    $coefTotal += $n['coefficient'];
}
$moyenne = $coefTotal > 0 ? round($total / $coefTotal, 2) : 0;

// Détermination de la mention
if ($moyenne >= 16) {
    $mention = "Très Bien";
    $mentionColor = "text-green-700";
} elseif ($moyenne >= 14) {
    $mention = "Bien";
    $mentionColor = "text-green-600";
} elseif ($moyenne >= 12) {
    $mention = "Assez Bien";
    $mentionColor = "text-yellow-600";
} elseif ($moyenne >= 10) {
    $mention = "Passable";
    $mentionColor = "text-yellow-500";
} else {
    $mention = "Ajourné";
    $mentionColor = "text-red-600";
}

// Champs formatés
$sexe = $etudiant['sexe'] ?? 'Non défini';
$dateNaissance = $etudiant['date_naissance'] ?? 'Non définie';
$option = $etudiant['option_etude'] ?? 'Non définie';
$niveau = $etudiant['niveau_etude'] ?? 'Non défini';

// Fonction pour générer le HTML du bulletin (pour affichage et PDF)
function genererHTMLBulletin($etudiant, $notes, $moyenne, $mention, $mentionColor) {
    ob_start();
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8" />
        <title>Bulletin de l'Étudiant</title>
        <link rel="stylesheet" href="../assets/css/style_bulletin.css" />
        <style>
body {
    font-family: Arial, sans-serif;
    color: #1a237e;
    background-color: #f0f4ff;
    margin: 0;
    padding: 1rem;
}

h1 {
    text-align: center;
    color: #0d47a1;
    margin-bottom: 2rem;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 2rem;
}

th, td {
    border: 1px solid #90caf9;
    padding: 0.5rem 1rem;
    text-align: center;
}

thead {
    background-color: #bbdefb;
    color: #0d47a1;
    font-weight: bold;
}

tbody tr:nth-child(even) {
    background-color: #e3f2fd;
}

.mention {
    font-weight: bold;
    font-size: 1.2rem;
    color: #0d47a1;
    text-align: center;
    padding: 1rem;
    border-radius: 8px;
    background-color: #bbdefb;
}

/* Styles additionnels */
.header {
    text-align: center;
    margin-bottom: 2rem;
}

.info p {
    margin: 0.2rem 0;
}
</style>

    </head>
    <body>
        <h1>🎓 Bulletin de l'Étudiant</h1>
        <div>
            <p><strong>Nom :</strong> <?= htmlspecialchars($etudiant['nom']) ?></p>
            <p><strong>Prénom :</strong> <?= htmlspecialchars($etudiant['prenom']) ?></p>
            <p><strong>Sexe :</strong> <?= htmlspecialchars($sexe) ?></p>
            <p><strong>Date de naissance :</strong> <?= htmlspecialchars($dateNaissance) ?></p>
            <p><strong>Option :</strong> <?= htmlspecialchars($option) ?></p>
            <p><strong>Niveau :</strong> <?= htmlspecialchars($niveau) ?></p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Matière</th>
                    <th>Note /20</th>
                    <th>Coefficient</th>
                    <th>Note x Coef</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notes as $note): ?>
                <tr>
                    <td><?= htmlspecialchars($note['nom_matiere']) ?></td>
                    <td><?= number_format($note['note'], 2) ?></td>
                    <td><?= htmlspecialchars($note['coefficient']) ?></td>
                    <td><?= number_format($note['note'] * $note['coefficient'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
                <tr style="font-weight:bold; background-color:#bbdefb;">
                    <td colspan="3" style="text-align:right;">Moyenne générale</td>
                    <td><?= number_format($moyenne, 2) ?></td>
                </tr>
            </tbody>
        </table>

        <div class="mention <?= $mentionColor ?>">
            ✨ Mention : <?= htmlspecialchars($mention) ?>
        </div>
    </body>
    </html>
    <?php
    return ob_get_clean();
}

// Gérer la génération PDF à la soumission du formulaire
if (isset($_POST['telecharger_pdf'])) {
    $html = genererHTMLBulletin($etudiant, $notes, $moyenne, $mention, $mentionColor);

    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $filename = "bulletin_{$etudiant['nom']}_{$etudiant['prenom']}.pdf";

    // Envoi du PDF en téléchargement sans redirection
    header('Content-Type: application/pdf');
    header("Content-Disposition: attachment; filename=\"$filename\"");
    echo $dompdf->output();
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <title>Bulletin de l'Étudiant</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/css/style_bulletin.css" />
</head>
<body class="bg-blue-100 min-h-screen flex items-center justify-center p-6">

    <div class="bg-white shadow-lg rounded-lg max-w-xl w-full p-8">
        <h1 class="text-2xl md:text-3xl font-bold text-center text-blue-800 mb-6 flex items-center justify-center gap-2">
            <span>🎓</span> Bulletin de l'Étudiant
        </h1>

        <div class="mb-6 text-center text-gray-900 space-y-1">
            <p><span class="font-semibold">Nom</span> : <?= htmlspecialchars($etudiant['nom']) ?></p>
            <p><span class="font-semibold">Prénom</span> : <?= htmlspecialchars($etudiant['prenom']) ?></p>
            <p><span class="font-semibold">Sexe</span> : <?= htmlspecialchars($sexe) ?></p>
            <p><span class="font-semibold">Date de naissance</span> : <?= htmlspecialchars($dateNaissance) ?></p>
            <p><span class="font-semibold">Option</span> : <?= htmlspecialchars($option) ?></p>
            <p><span class="font-semibold">Niveau</span> : <?= htmlspecialchars($niveau) ?></p>
        </div>

        <table class="w-full text-left border border-gray-300 rounded-md overflow-hidden">
            <thead class="bg-blue-300 text-gray-900">
                <tr>
                    <th class="px-4 py-2">Matière</th>
                    <th class="px-4 py-2">Note /20</th>
                    <th class="px-4 py-2">Coefficient</th>
                    <th class="px-4 py-2">Note x Coef</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notes as $note): ?>
                <tr class="bg-white even:bg-blue-50 hover:bg-blue-100 transition-colors">
                    <td class="border px-4 py-2"><?= htmlspecialchars($note['nom_matiere']) ?></td>
                    <td class="border px-4 py-2 text-blue-700 font-semibold"><?= number_format($note['note'], 2) ?>/20</td>
                    <td class="border px-4 py-2"><?= htmlspecialchars($note['coefficient']) ?></td>
                    <td class="border px-4 py-2"><?= number_format($note['note'] * $note['coefficient'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
                <tr class="font-semibold bg-blue-200">
                    <td colspan="3" class="text-right px-4 py-2">Moyenne générale</td>
                    <td class="px-4 py-2"><?= number_format($moyenne, 2) ?>/20</td>
                </tr>
            </tbody>
        </table>

        <div class="mt-6 flex flex-col md:flex-row md:justify-between gap-4">
            <div class="bg-blue-200 text-blue-800 rounded-md p-4 flex-1 text-center">
                <p class="font-semibold">Moyenne Générale</p>
                <p class="text-xl md:text-2xl font-bold"><?= number_format($moyenne, 2) ?>/20</p>
            </div>
            <div class="bg-green-200 <?= $mentionColor ?> rounded-md p-4 flex-1 text-center">
                <p class="font-semibold">Mention</p>
                <p class="text-xl md:text-2xl font-bold"><?= htmlspecialchars($mention) ?></p>
            </div>
        </div>

        <div class="mt-8 flex justify-between items-center">
            <a href="<?= $retour_url ?>" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded transition">
                ⬅️ Retour à la liste
            </a>

            <form method="post" style="margin:0;">
                <button type="submit" name="telecharger_pdf" 
                    class="bg-blue-700 hover:bg-blue-800 text-white font-semibold px-6 py-2 rounded transition">
                    📄 Télécharger PDF
                </button>
            </form>
        </div>

    </div>

</body>
</html>
