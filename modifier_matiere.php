<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: connexion.php");
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: gerer_matieres.php");
    exit;
}

$id = (int) $_GET['id'];
$message = '';

// Fonction d’échappement sécurisée
function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

// Récupérer la matière
$stmt = $pdo->prepare("SELECT * FROM matieres WHERE id = ?");
$stmt->execute([$id]);
$matiere = $stmt->fetch();

if (!$matiere) {
    header("Location: gerer_matieres.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_matiere = trim($_POST['nom_matiere'] ?? '');
    $option_etude = trim($_POST['option_etude'] ?? '');
    $coefficient = (int)($_POST['coefficient'] ?? 1);

    if ($nom_matiere && $option_etude && $coefficient > 0) {
        $stmt = $pdo->prepare("UPDATE matieres SET nom_matiere = ?, option_etude = ?, coefficient = ? WHERE id = ?");
        $success = $stmt->execute([$nom_matiere, $option_etude, $coefficient, $id]);

        if ($success) {
            $message = "✅ Matière modifiée avec succès !";
            // Recharge les données modifiées
            $stmt = $pdo->prepare("SELECT * FROM matieres WHERE id = ?");
            $stmt->execute([$id]);
            $matiere = $stmt->fetch();
        } else {
            $message = "❌ Erreur lors de la modification.";
        }
    } else {
        $message = "⚠️ Veuillez remplir tous les champs correctement.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Modifier Matière</title>
    <link rel="stylesheet" href="../assets/css/style.css" />
</head>
<body class="bg-gray-100 p-6">

<div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Modifier la matière</h2>

    <?php if ($message): ?>
        <p class="<?= strpos($message, '✅') !== false ? 'text-green-600' : 'text-red-600' ?> mb-4">
            <?= e($message) ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="modifier_matiere.php?id=<?= e($id) ?>">
        <label for="nom_matiere">Nom de la matière</label>
        <input type="text" id="nom_matiere" name="nom_matiere" required value="<?= e($matiere['nom_matiere']) ?>">

        <label for="option_etude">Option d'étude</label>
        <input type="text" id="option_etude" name="option_etude" required value="<?= e($matiere['option_etude']) ?>">

        <label for="coefficient">Coefficient</label>
        <input type="number" id="coefficient" name="coefficient" min="1" value="<?= e($matiere['coefficient'] ?? 1) ?>" required>

        <button type="submit" class="mt-4">Modifier</button>
    </form>

    <p class="mt-4"><a href="gerer_matieres.php" class="text-blue-600 hover:underline">&larr; Retour à la liste des matières</a></p>
</div>

</body>
</html>
