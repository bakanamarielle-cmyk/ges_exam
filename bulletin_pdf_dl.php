<?php
require_once '../config/database.php';
require_once '../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

session_start();

if (!isset($_SESSION['etudiant_id'])) {
    header('Location: ../connexion.php');
    exit();
}

$etudiant_id = $_SESSION['etudiant_id'];

// Récupération des infos de l'étudiant
$stmt = $pdo->prepare("SELECT nom, prenom FROM etudiants WHERE id = ?");
$stmt->execute([$etudiant_id]);
$etudiant = $stmt->fetch();

if (!$etudiant) {
    die("Étudiant non trouvé.");
}

// Récupération des notes de l'étudiant
$stmt = $pdo->prepare("
    SELECT m.nom AS nom_matiere, n.note
    FROM notes n
    JOIN matieres m ON n.matiere_id = m.id
    WHERE n.etudiant_id = ?
");
$stmt->execute([$etudiant_id]);
$notes = $stmt->fetchAll();

// Calcul moyenne
$total = 0;
$count = count($notes);
foreach ($notes as $note) {
    $total += $note['note'];
}
$moyenne = $count > 0 ? round($total / $count, 2) : 0;

// Détermination de la mention
if ($moyenne >= 16) {
    $mention = "Très bien";
} elseif ($moyenne >= 14) {
    $mention = "Bien";
} elseif ($moyenne >= 12) {
    $mention = "Assez bien";
} elseif ($moyenne >= 10) {
    $mention = "Passable";
} else {
    $mention = "Insuffisant";
}

// Préparation du contenu HTML pour le PDF
ob_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bulletin PDF</title>
    <style>
        <?php include '../assets/css/style_bulletin.css'; ?>
    </style>
</head>
<body>
    <h1>Bulletin de Notes</h1>
    <p><strong>Nom :</strong> <?= htmlspecialchars($etudiant['nom']) ?></p>
    <p><strong>Prénom :</strong> <?= htmlspecialchars($etudiant['prenom']) ?></p>

    <table>
        <thead>
            <tr>
                <th>Matière</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($notes as $note): ?>
                <tr>
                    <td><?= htmlspecialchars($note['nom_matiere']) ?></td>
                    <td><?= htmlspecialchars($note['note']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="mention">
        Moyenne : <?= $moyenne ?> — Mention : <?= $mention ?>
    </div>
</body>
</html>
<?php
$html = ob_get_clean();

// Options pour Dompdf
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Téléchargement automatique
$dompdf->stream("bulletin_{$etudiant['nom']}_{$etudiant['prenom']}.pdf", ["Attachment" => false]);
exit;
