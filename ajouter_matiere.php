<?php
session_start();
require_once '../config/database.php';

// Vérification que l'admin est connecté
if (!isset($_SESSION['admin_id'])) {
    header("Location: connexion.php");
    exit();
}

$message = '';
$options_valides = [
   "Genie Logiciel et Administration Reseau (GLAR)",
   "Resau et Telecommunication (RT)",
   "Maintenance Industrielle (MII)",
   "Genie Civil (GC)",
   "Assurance Banque et Finance (ABF)",
   "CGF",
   "Delegue Medical (DM)",
   "Genie Electrique et Energie Renouvellable(GEER)",
   "Electronique et Maintenance Indistrielle (EMI)",
   "ASD",
   "Genie Chimique (GCH)"
];

$niveaux_valides = ["Licence 1", "Licence 2", "Licence 3", "Master 1", "Master 2"];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_matiere = trim($_POST['nom_matiere'] ?? '');
    $option_etude = trim($_POST['option_etude'] ?? '');
    $niveau = trim($_POST['niveau'] ?? '');
    $coefficient = (int) ($_POST['coefficient'] ?? 1);

    if (!$nom_matiere || !$option_etude || !$niveau) {
        $message = "Tous les champs sont obligatoires.";
    } elseif (!in_array($option_etude, $options_valides) || !in_array($niveau, $niveaux_valides)) {
        $message = "Option ou niveau invalide.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM matieres WHERE nom_matiere = ? AND option_etude = ? AND niveau = ?");
        $stmt->execute([$nom_matiere, $option_etude, $niveau]);
        if ($stmt->fetch()) {
            $message = "Cette matière existe déjà pour cette option et ce niveau.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO matieres (nom_matiere, option_etude, niveau, coefficient) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$nom_matiere, $option_etude, $niveau, $coefficient])) {
                $message = "✅ Matière ajoutée avec succès.";
            } else {
                $message = "Erreur lors de l'ajout de la matière.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Ajouter une matière - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #cbe9f7, #e0eafc);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }
        .form-box {
            max-width: 500px;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
            width: 100%;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input[type=text], input[type=number], select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        button {
            margin-top: 25px;
            padding: 12px;
            width: 100%;
            background: #007BFF;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
        }
        button:hover {
            background: #0056b3;
        }
        .message {
            margin-top: 15px;
            font-weight: bold;
            text-align: center;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
        .retour {
            text-align: center;
            margin-top: 20px;
        }
        .retour a {
            background: #218d6bff;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s;
        }
        .retour a:hover {
            background: #30a364ff;
        }
    </style>
</head>
<body>

<div class="form-box">
    <h2>Ajouter une matière</h2>

    <?php if ($message): ?>
        <p class="message <?= strpos($message, '✅') === 0 ? 'success' : 'error' ?>">
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="nom_matiere">Nom de la matière</label>
        <input type="text" id="nom_matiere" name="nom_matiere" required value="<?= htmlspecialchars($_POST['nom_matiere'] ?? '') ?>" />

        <label for="option_etude">Option d'étude</label>
        <select id="option_etude" name="option_etude" required>
            <option value="">-- Sélectionnez une option --</option>
            <?php foreach ($options_valides as $opt): ?>
                <option value="<?= htmlspecialchars($opt) ?>" <?= (isset($_POST['option_etude']) && $_POST['option_etude'] === $opt) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($opt) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="niveau">Niveau</label>
        <select id="niveau" name="niveau" required>
            <option value="">-- Sélectionnez un niveau --</option>
            <?php foreach ($niveaux_valides as $niv): ?>
                <option value="<?= htmlspecialchars($niv) ?>" <?= (isset($_POST['niveau']) && $_POST['niveau'] === $niv) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($niv) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="coefficient">Coefficient</label>
        <input type="number" id="coefficient" name="coefficient" min="1" value="<?= htmlspecialchars($_POST['coefficient'] ?? 1) ?>" />

        <button type="submit">Ajouter la matière</button>
    </form>

    <div class="retour">
        <a href="liste_bulletin.php">⬅️ Retour à la liste</a>
    </div>
</div>

</body>
</html>
