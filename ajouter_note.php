<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: connexion.php");
    exit;
}

$message = '';

// Récupérer la liste des étudiants
$etudiants = $pdo->query("SELECT id, nom, prenom FROM etudiants ORDER BY nom")->fetchAll();

// Récupérer la liste des matières (correction ici)
$matieres = $pdo->query("SELECT id, nom_matiere FROM matieres ORDER BY nom_matiere")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $etudiant_id = (int) $_POST['etudiant_id'];
    $matiere_id = (int) $_POST['matiere_id'];
    $note = floatval($_POST['note']);

    if ($etudiant_id && $matiere_id && $note >= 0 && $note <= 20) {
        // Vérifier si la note existe déjà pour cet étudiant & matière
        $check = $pdo->prepare("SELECT COUNT(*) FROM notes WHERE etudiant_id = ? AND matiere_id = ?");
        $check->execute([$etudiant_id, $matiere_id]);
        if ($check->fetchColumn() > 0) {
            $message = "⚠️ Cette note existe déjà pour cet étudiant et cette matière.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO notes (etudiant_id, matiere_id, note) VALUES (?, ?, ?)");
            $success = $stmt->execute([$etudiant_id, $matiere_id, $note]);
            if ($success) {
                $message = "✅ Note ajoutée avec succès !";
            } else {
                $message = "❌ Erreur lors de l'ajout.";
            }
        }
    } else {
        $message = "⚠️ Veuillez remplir tous les champs correctement et saisir une note entre 0 et 20.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Ajouter une note</title>
    <link rel="stylesheet" href="../assets/css/style.css" />
</head>
<body class="bg-gray-100 p-6">

<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Ajouter une nouvelle note</h2>

    <?php if ($message): ?>
        <p class="<?= strpos($message, '✅') !== false ? 'text-green-600' : 'text-red-600' ?> mb-4">
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="ajouter_note.php">
        <label for="etudiant_id">Étudiant</label>
        <select id="etudiant_id" name="etudiant_id" required>
            <option value="">-- Sélectionnez un étudiant --</option>
            <?php foreach ($etudiants as $e): ?>
                <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['nom'].' '.$e['prenom']) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="matiere_id">Matière</label>
        <select id="matiere_id" name="matiere_id" required>
            <option value="">-- Sélectionnez une matière --</option>
            <?php foreach ($matieres as $m): ?>
                <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nom_matiere']) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="note">Note (0 à 20)</label>
        <input type="number" id="note" name="note" step="0.01" min="0" max="20" required>

        <button type="submit" class="mt-4">Ajouter</button>
    </form>

    <p class="mt-4"><a href="gerer_notes.php" class="text-blue-600 hover:underline">&larr; Retour à la liste des notes</a></p>
</div>

</body>
</html>
