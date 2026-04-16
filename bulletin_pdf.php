<?php
require_once '../vendor/autoload.php';
require_once '../config/database.php';
session_start();

use Dompdf\Dompdf;

if (!isset($_SESSION['etudiant_id'])) {
    header("Location: ../connexion.php");
    exit();
}

$etudiant_id = $_SESSION['etudiant_id'];

// Récupérer les infos de l'étudiant
$stmt = $pdo->prepare("SELECT nom, prenom, sexe, date_naissance, option_etude, niveau_etude FROM etudiants WHERE id = ?");
$stmt->execute([$etudiant_id]);
$etudiant = $stmt->fetch();

if (!$etudiant) {
    die("Étudiant non trouvé.");
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
    die("Aucune note trouvée.");
}

// Calcul moyenne pondérée
$total = 0;
$totalCoef = 0;
foreach ($notes as $note) {
    $total += $note['note'] * $note['coefficient'];
    $totalCoef += $note['coefficient'];
}
$moyenne = $totalCoef > 0 ? round($total / $totalCoef, 2) : 0;

// Mention
if ($moyenne >= 16) {
    $mention = "Très Bien";
    $mentionColor = "mention-tres-bien";
} elseif ($moyenne >= 14) {
    $mention = "Bien";
    $mentionColor = "mention-bien";
} elseif ($moyenne >= 12) {
    $mention = "Assez Bien";
    $mentionColor = "mention-assez-bien";
} elseif ($moyenne >= 10) {
    $mention = "Passable";
    $mentionColor = "mention-passable";
} else {
    $mention = "Ajourné";
    $mentionColor = "mention-ajourne";
}

// Charger le contenu CSS pour le PDF depuis le fichier
$cssPath = __DIR__ . '/../assets/css/style_bulletin.css';
$cssContent = '';
if (file_exists($cssPath)) {
    $cssContent = file_get_contents($cssPath);
} else {
    // Fallback CSS minimal
    $cssContent = "
        body { font-family: Arial, sans-serif; font-size: 12px; color: #1e40af; }
        h1 { text-align: center; color: #1e40af; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #cbd5e1; padding: 8px; text-align: left; }
        th { background-color: #bfdbfe; color: #1e40af; }
        .mention-tres-bien { color: #047857; font-weight: bold; }
        .mention-bien { color: #065f46; font-weight: bold; }
        .mention-assez-bien { color: #854d0e; font-weight: bold; }
        .mention-passable { color: #b45309; font-weight: bold; }
        .mention-ajourne { color: #b91c1c; font-weight: bold; }
        .info { margin-top: 10px; }
    ";
}

ob_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Bulletin de notes</title>
    <style>
        <?= $cssContent ?>
        /* Styles additionnels spécifiques au PDF */
        body {
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .info p {
            margin: 4px 0;
        }
        .mention {
            margin-top: 20px;
            font-weight: bold;
            font-size: 1.2em;
            text-align: center;
            padding: 10px;
            border-radius: 8px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>📋 Bulletin de notes</h1>
    </div>

    <div class="info">
        <p><strong>Nom :</strong> <?= htmlspecialchars($etudiant['nom']) ?></p>
        <p><strong>Prénom :</strong> <?= htmlspecialchars($etudiant['prenom']) ?></p>
        <p><strong>Sexe :</strong> <?= htmlspecialchars($etudiant['sexe']) ?></p>
        <p><strong>Date de naissance :</strong> <?= htmlspecialchars($etudiant['date_naissance']) ?></p>
        <p><strong>Option :</strong> <?= htmlspecialchars($etudiant['option_etude']) ?></p>
        <p><strong>Niveau :</strong> <?= htmlspecialchars($etudiant['niveau_etude']) ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Matière</th>
                <th>Note</th>
                <th>Coefficient</th>
                <th>Note x Coef</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($notes as $note): ?>
                <tr>
                    <td><?= htmlspecialchars($note['nom_matiere']) ?></td>
                    <td style="text-align:center;"><?= number_format($note['note'], 2) ?></td>
                    <td style="text-align:center;"><?= htmlspecialchars($note['coefficient']) ?></td>
                    <td style="text-align:center;"><?= number_format($note['note'] * $note['coefficient'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
            <tr style="font-weight:bold; background-color:#bfdbfe;">
                <td colspan="3" style="text-align:right;">Moyenne générale</td>
                <td style="text-align:center;"><?= number_format($moyenne, 2) ?></td>
            </tr>
        </tbody>
    </table>

    <div class="mention <?= $mentionColor ?>">
        ✨ Mention : <?= htmlspecialchars($mention) ?>
    </div>

</body>
</html>

<?php
$html = ob_get_clean();

$dompdf = new Dompdf();
$dompdf->loadHtml($html);

// Format papier et orientation
$dompdf->setPaper('A4', 'portrait');

$dompdf->render();
$dompdf->stream("bulletin_{$etudiant['nom']}_{$etudiant['prenom']}.pdf", ["Attachment" => true]);
exit();
