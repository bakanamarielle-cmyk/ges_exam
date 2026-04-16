<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: connexion.php");
    exit();
}

$message = '';
$recherche = trim($_GET['recherche'] ?? '');

function majBulletin($pdo, $etudiant_id) {
    $stmt = $pdo->prepare("SELECT AVG(note) as moyenne FROM notes WHERE etudiant_id = ?");
    $stmt->execute([$etudiant_id]);
    $moyenne = $stmt->fetchColumn() ?: 0;
    $moyenne = round($moyenne, 2);

    $mention = match (true) {
        $moyenne >= 16 => 'Très bien',
        $moyenne >= 14 => 'Bien',
        $moyenne >= 12 => 'Assez bien',
        $moyenne >= 10 => 'Passable',
        default => 'Échec',
    };

    $stmt = $pdo->prepare("SELECT id FROM bulletins WHERE etudiant_id = ?");
    $stmt->execute([$etudiant_id]);
    $bulletin_id = $stmt->fetchColumn();

    if ($bulletin_id) {
        $stmt = $pdo->prepare("UPDATE bulletins SET moyenne = ?, mention = ? WHERE id = ?");
        $stmt->execute([$moyenne, $mention, $bulletin_id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO bulletins (etudiant_id, moyenne, mention) VALUES (?, ?, ?)");
        $stmt->execute([$etudiant_id, $moyenne, $mention]);
    }
}

if (isset($_GET['supprimer'])) {
    $id = (int)$_GET['supprimer'];
    $stmt = $pdo->prepare("DELETE FROM notes WHERE id = ?");
    $stmt->execute([$id]);
    $message = "✅ Note supprimée avec succès.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_note = $_POST['id_note'] ?? null;
    $etudiant_id = $_POST['etudiant_id'] ?? '';
    $matiere_id = $_POST['matiere_id'] ?? '';
    $note = $_POST['note'] ?? '';

    if (!$etudiant_id || !$matiere_id || $note === '' || !is_numeric($note) || $note < 0 || $note > 20) {
        $message = "❌ Remplis tous les champs correctement (note entre 0 et 20).";
    } else {
        if ($id_note) {
            $stmt = $pdo->prepare("UPDATE notes SET etudiant_id = ?, matiere_id = ?, note = ? WHERE id = ?");
            $stmt->execute([$etudiant_id, $matiere_id, $note, $id_note]);
            $message = "✅ Note modifiée avec succès.";
        } else {
            $stmt = $pdo->prepare("SELECT id FROM notes WHERE etudiant_id = ? AND matiere_id = ?");
            $stmt->execute([$etudiant_id, $matiere_id]);
            if ($stmt->fetch()) {
                $message = "⚠️ Cette note existe déjà.";
            } else {
                $stmt = $pdo->prepare("INSERT INTO notes (etudiant_id, matiere_id, note) VALUES (?, ?, ?)");
                $stmt->execute([$etudiant_id, $matiere_id, $note]);
                $message = "✅ Note ajoutée.";
            }
        }
        majBulletin($pdo, $etudiant_id);
    }
}

$etudiants = $pdo->query("SELECT id, nom, prenom, matricule FROM etudiants ORDER BY nom")->fetchAll();
$matieres = $pdo->query("SELECT id, nom_matiere FROM matieres ORDER BY nom_matiere")->fetchAll();

$notes = [];
$params = [];
$where = "";

if ($recherche) {
    $where = "WHERE e.nom LIKE ? OR e.prenom LIKE ? OR e.matricule LIKE ?";
    $params = array_fill(0, 3, "%$recherche%");
}

$sql = "SELECT n.id, e.nom AS etudiant_nom, e.prenom AS etudiant_prenom, e.matricule, m.nom_matiere AS matiere_nom, n.note
        FROM notes n
        JOIN etudiants e ON n.etudiant_id = e.id
        JOIN matieres m ON n.matiere_id = m.id
        $where
        ORDER BY e.nom, m.nom_matiere";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$notes = $stmt->fetchAll();

$edit_note = null;
if (isset($_GET['modifier'])) {
    $stmt = $pdo->prepare("SELECT * FROM notes WHERE id = ?");
    $stmt->execute([$_GET['modifier']]);
    $edit_note = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Gestion des Notes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 min-h-screen flex">

<?php include 'menu_admin.php';?>

<main class="ml-64 p-8 w-full space-y-10">

    <h2 class="text-3xl font-bold text-blue-900 mb-6">📝 Gestion des Notes</h2>

    <?php if ($message): ?>
        <div class="p-4 rounded border shadow <?= str_starts_with($message, '✅') ? 'bg-green-100 text-green-800 border-green-300' : (str_starts_with($message, '❌') ? 'bg-red-100 text-red-800 border-red-300' : 'bg-yellow-100 text-yellow-800 border-yellow-300') ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <section class="bg-white p-6 rounded-xl shadow-md">
            <form method="POST" class="space-y-4">
                <h3 class="text-xl font-bold text-blue-800"><?= $edit_note ? "✏️ Modifier une note" : "➕ Ajouter une note" ?></h3>
                <input type="hidden" name="id_note" value="<?= $edit_note['id'] ?? '' ?>">

                <div>
                    <label class="block font-medium">Étudiant :</label>
                    <select name="etudiant_id" required class="w-full border px-3 py-2 rounded">
                        <option value="">-- Choisir --</option>
                        <?php foreach ($etudiants as $e): ?>
                            <option value="<?= $e['id'] ?>" <?= ($edit_note && $edit_note['etudiant_id'] == $e['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($e['nom'] . ' ' . $e['prenom'] . ' (' . $e['matricule'] . ')') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block font-medium">Matière :</label>
                    <select name="matiere_id" required class="w-full border px-3 py-2 rounded">
                        <option value="">-- Choisir --</option>
                        <?php foreach ($matieres as $m): ?>
                            <option value="<?= $m['id'] ?>" <?= ($edit_note && $edit_note['matiere_id'] == $m['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($m['nom_matiere']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block font-medium">Note (0 à 20) :</label>
                    <input type="number" name="note" step="0.01" min="0" max="20"
                           value="<?= $edit_note['note'] ?? '' ?>" required
                           class="w-full border px-3 py-2 rounded" />
                </div>

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    <?= $edit_note ? "Modifier" : "Ajouter" ?>
                </button>
            </form>
        </section>

        <section class="bg-white p-6 rounded-xl shadow-md">
            <form method="GET">
                <label class="block font-semibold text-blue-800 mb-2">🔍 Rechercher un étudiant</label>
                <input type="text" name="recherche" placeholder="Nom, prénom ou matricule"
                       value="<?= htmlspecialchars($recherche) ?>"
                       class="w-full px-4 py-2 border rounded mb-4" />
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Rechercher</button>
                <?php if ($recherche): ?>
                    <a href="gerer_notes.php" class="ml-4 text-blue-600 hover:underline">Réinitialiser</a>
                <?php endif; ?>
            </form>
        </section>
    </div>

    <section class="bg-white p-6 rounded-xl shadow-md overflow-x-auto">
        <h3 class="text-xl font-semibold text-blue-800 mb-4">📋 Notes enregistrées</h3>
        <table class="min-w-full text-sm table-auto border border-gray-300">
            <thead class="bg-blue-100">
                <tr>
                    <th class="px-4 py-2 border">Étudiant</th>
                    <th class="px-4 py-2 border">Matière</th>
                    <th class="px-4 py-2 border">Note</th>
                    <th class="px-4 py-2 border text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($notes)): ?>
                    <tr><td colspan="4" class="text-center py-6 text-gray-500">Aucune note trouvée.</td></tr>
                <?php else: ?>
                    <?php foreach ($notes as $n): ?>
                        <tr class="hover:bg-blue-50 transition">
                            <td class="border px-4 py-2"><?= htmlspecialchars($n['etudiant_nom'] . ' ' . $n['etudiant_prenom'] . ' (' . $n['matricule'] . ')') ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($n['matiere_nom']) ?></td>
                            <td class="border px-4 py-2 font-semibold text-center"><?= htmlspecialchars($n['note']) ?></td>
                            <td class="border px-4 py-2 text-center space-x-2">
                                <a href="?modifier=<?= $n['id'] ?>" class="bg-blue-400 px-3 py-1 rounded hover:bg-blue-500">✏️</a>
                                <a href="?supprimer=<?= $n['id'] ?>" onclick="return confirm('Supprimer ?')" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-red-600">🗑️</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </section>

</main>
</body>
</html>
