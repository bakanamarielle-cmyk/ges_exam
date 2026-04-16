<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: connexion.php");
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: gerer_etudiants.php");
    exit;
}

$id = (int) $_GET['id'];
$message = '';

// Récupérer l’étudiant
$stmt = $pdo->prepare("SELECT * FROM etudiants WHERE id = ?");
$stmt->execute([$id]);
$etudiant = $stmt->fetch();

if (!$etudiant) {
    header("Location: gerer_etudiants.php");
    exit;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $matricule = trim($_POST['matricule']);
    $sexe = $_POST['sexe'];
    $date_naissance = $_POST['date_naissance'];
    $option_etude = $_POST['option_etude'];
    $niveau_etude = $_POST['niveau_etude'];

    if ($nom && $prenom && $matricule && $sexe && $date_naissance && $option_etude && $niveau_etude) {
        $stmt = $pdo->prepare("UPDATE etudiants SET nom = ?, prenom = ?, matricule = ?, sexe = ?, date_naissance = ?, option_etude = ?, niveau_etude = ? WHERE id = ?");
        $success = $stmt->execute([$nom, $prenom, $matricule, $sexe, $date_naissance, $option_etude, $niveau_etude, $id]);

        if ($success) {
            $message = "✅ Étudiant modifié avec succès !";
            $stmt = $pdo->prepare("SELECT * FROM etudiants WHERE id = ?");
            $stmt->execute([$id]);
            $etudiant = $stmt->fetch();
        } else {
            $message = "❌ Erreur lors de la modification.";
        }
    } else {
        $message = "⚠️ Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Étudiant</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 min-h-screen p-6 flex items-center justify-center">
    <div class="bg-white p-8 rounded-xl shadow-md w-full max-w-lg">
        <h2 class="text-2xl font-bold mb-6 text-center text-blue-800">✏️ Modifier l'étudiant</h2>

        <?php if ($message): ?>
            <div class="mb-4 text-center font-medium <?= strpos($message, '✅') !== false ? 'text-green-600' : 'text-red-600' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block mb-1 font-medium">Nom</label>
                <input type="text" name="nom" required value="<?= htmlspecialchars($etudiant['nom']) ?>" class="w-full border border-gray-300 rounded px-4 py-2" />
            </div>

            <div>
                <label class="block mb-1 font-medium">Prénom</label>
                <input type="text" name="prenom" required value="<?= htmlspecialchars($etudiant['prenom']) ?>" class="w-full border border-gray-300 rounded px-4 py-2" />
            </div>

            <div>
                <label class="block mb-1 font-medium">Matricule</label>
                <input type="text" name="matricule" required value="<?= htmlspecialchars($etudiant['matricule']) ?>" class="w-full border border-gray-300 rounded px-4 py-2" />
            </div>

            <div>
                <label class="block mb-1 font-medium">Sexe</label>
                <select name="sexe" required class="w-full border border-gray-300 rounded px-4 py-2">
                    <option value="Masculin" <?= $etudiant['sexe'] === 'Masculin' ? 'selected' : '' ?>>Masculin</option>
                    <option value="Féminin" <?= $etudiant['sexe'] === 'Féminin' ? 'selected' : '' ?>>Féminin</option>
                </select>
            </div>

            <div>
                <label class="block mb-1 font-medium">Date de naissance</label>
                <input type="date" name="date_naissance" required value="<?= htmlspecialchars($etudiant['date_naissance']) ?>" class="w-full border border-gray-300 rounded px-4 py-2" />
            </div>

            <div>
                <label class="block mb-1 font-medium">Option</label>
                <select name="option_etude" required class="w-full border border-gray-300 rounded px-4 py-2">
                    <?php
                    $options = ["Genie Logiciel et Administration Reseau (GLAR)","Resau et Telecommunication (RT)","Maintenance Industrielle (MII)","Genie Civil (GC)","Assurance Banque et Finance (ABF)","CGF","Delegue Medical (DM)","Genie Electrique et Energie Renouvellable(GEER)","Electronique et Maintenance Indistruelle (EMI)","ASD","Genie Chimique (GCH)"];
                    foreach ($options as $opt):
                    ?>
                        <option value="<?= $opt ?>" <?= $etudiant['option_etude'] === $opt ? 'selected' : '' ?>><?= $opt ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block mb-1 font-medium">Niveau</label>
                <select name="niveau_etude" required class="w-full border border-gray-300 rounded px-4 py-2">
                    <?php
                    $niveaux = ["Licence 1","Licence 2","Licence 3","Master 1","Master 2"];
                    foreach ($niveaux as $niv):
                    ?>
                        <option value="<?= $niv ?>" <?= $etudiant['niveau_etude'] === $niv ? 'selected' : '' ?>><?= $niv ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="text-center mt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded transition">💾 Enregistrer</button>
            </div>
        </form>

        <div class="mt-6 text-center">
            <a href="gerer_etudiants.php" class="text-blue-600 hover:underline">&larr; Retour à la liste</a>
        </div>
    </div>
</body>
</html>
