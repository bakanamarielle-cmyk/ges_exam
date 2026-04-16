<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: connexion.php");
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: gerer_notes.php");
    exit;
}

$id = (int) $_GET['id'];
$message = '';

// Récupérer la note
$stmt = $pdo->prepare("SELECT n.*, e.nom AS nom_etudiant, e.prenom AS prenom_etudiant, m.nom_matiere AS nom_matiere 
                       FROM notes n 
                       JOIN etudiants e ON n.etudiant_id = e.id 
                       JOIN matieres m ON n.matiere_id = m.id 
                       WHERE n.id = ?");
$stmt->execute([$id]);
$note = $stmt->fetch();

if (!$note) {
    header("Location: gerer_notes.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nouvelle_note = floatval($_POST['note']);
    if ($nouvelle_note >= 0 && $nouvelle_note <= 20) {
        $stmt = $pdo->prepare("UPDATE notes SET note = ? WHERE id = ?");
        $success = $stmt->execute([$nouvelle_note, $id]);
        if ($success) {
            $message = "✅ Note modifiée avec succès !";
            // Recharger la note mise à jour
            $stmt = $pdo->prepare("SELECT n.*, e.nom AS nom_etudiant, e.prenom AS prenom_etudiant, m.nom_matiere AS nom_matiere 
                                   FROM notes n 
                                   JOIN etudiants e ON n.etudiant_id = e.id 
                                   JOIN matieres m ON n.matiere_id = m.id 
                                   WHERE n.id = ?");
            $stmt->execute([$id]);
            $note = $stmt->fetch();
        } else {
            $message = "❌ Erreur lors de la modification.";
        }
    } else {
        $message = "⚠️ La note doit être entre 0 et 20.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Modifier une note</title>
    <link rel="stylesheet" href="../assets/css/style.css" />
</head>
<body class="bg-gray-100 p-6">

<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Modifier la note</h2>

    <p><strong>Étudiant :</strong> <?= htmlspecialchars($note['nom_etudiant'].' '.$note['prenom_etudiant']) ?></p>
    <p><strong>Matière :</strong> <?= htmlspecialchars($note['nom_matiere']) ?></p>

    <?php if ($message): ?>
        <p class="<?= strpos($message, '✅') !== false ? 'text-green-600' : 'text-red-600' ?> mb-4">
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="modifier_note.php?id=<?= $id ?>">
        <label for="note">Note (0 à 20)</label>
        <input type="number" id="note" name="note" step="0.01" min="0" max="20" required value="<?= htmlspecialchars($note['note']) ?>">

        <button type="submit" class="mt-4">Modifier</button>
    </form>

    <p class="mt-4"><a href="gerer_notes.php" class="text-blue-600 hover:underline">&larr; Retour à la liste des notes</a></p>
</div>

</body>
</html>
