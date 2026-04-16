<?php
session_start();
require_once __DIR__ . '/../config/database.php';

// Vérification que l’admin est connecté et qu’un ID étudiant est passé
if (!isset($_SESSION['admin_id']) || !isset($_GET['id'])) {
    http_response_code(403);
    exit('Accès refusé');
}

$etudiant_id = (int) $_GET['id'];

// Récupération des infos de l’étudiant
$stmt = $pdo->prepare("SELECT nom, prenom, sexe, date_naissance, option_etude, niveau_etude FROM etudiants WHERE id = ?");
$stmt->execute([$etudiant_id]);
$etudiant = $stmt->fetch();

if (!$etudiant) {
    echo "<div class='text-red-600 text-center'>Étudiant introuvable.</div>";
    exit();
}

// Récupération des notes de l’étudiant sans filtrer sur niveau (pour éviter problème données incohérentes)
$stmt = $pdo->prepare("
    SELECT m.nom_matiere AS matiere, n.note, m.coefficient
    FROM notes n
    JOIN matieres m ON n.matiere_id = m.id
    WHERE n.etudiant_id = ?
      AND m.option_etude = ?
");
$stmt->execute([$etudiant_id, $etudiant['option_etude']]);
$notes = $stmt->fetchAll();

if (empty($notes)) {
    echo "<div class='text-center text-gray-700'>Aucune note trouvée pour cet étudiant.</div>";
    exit();
}

// Calcul de la moyenne pondérée
$total = 0;
$sommeCoef = 0;
foreach ($notes as $note) {
    $total += $note['note'] * $note['coefficient'];
    $sommeCoef += $note['coefficient'];
}
$moyenne = $sommeCoef > 0 ? round($total / $sommeCoef, 2) : 0;

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

// Variables pour la vue
$etudiantData = $etudiant;
$notesData = $notes;
$moyenneData = $moyenne;
$mentionData = $mention;

// Assignation aux noms attendus dans la vue
$etudiant = $etudiantData;
$notes = $notesData;
$moyenne = $moyenneData;
$mention = $mentionData;

// Inclusion de la vue
include __DIR__ . '/bulletin_detail.php';
