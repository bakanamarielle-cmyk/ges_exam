<?php
session_start();
require_once '../config/database.php';

// Vérification que l'admin est connecté
if (!isset($_SESSION['admin_id'])) {
    header("Location: connexion.php");
    exit();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_utilisateur = trim($_POST['nom_utilisateur'] ?? '');
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';
    $confirmation = $_POST['confirmation'] ?? '';

    if (!$nom_utilisateur || !$mot_de_passe || !$confirmation) {
        $message = "❌ Tous les champs sont obligatoires.";
    } elseif ($mot_de_passe !== $confirmation) {
        $message = "❌ Les mots de passe ne correspondent pas.";
    } else {
        // Vérifier si le nom d'utilisateur existe déjà
        $stmt = $pdo->prepare("SELECT id FROM admins WHERE nom_utilisateur = ?");
        $stmt->execute([$nom_utilisateur]);

        if ($stmt->fetch()) {
            $message = "❌ Ce nom d'utilisateur est déjà utilisé.";
        } else {
            $hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

            // ✅ Correction ici : INSERT INTO admins
            $stmt = $pdo->prepare("INSERT INTO admins (nom_utilisateur, mot_de_passe) VALUES (?, ?)");
            if ($stmt->execute([$nom_utilisateur, $hash])) {
                $message = "✅ Administrateur ajouté avec succès.";
            } else {
                $message = "❌ Erreur lors de l'ajout de l'administrateur.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Ajouter un Administrateur</title>
    <link rel="stylesheet" href="../assets/css/style.css" />
    <style>
        body {
            background: #f4f7fc;
            font-family: Arial, sans-serif;
        }
        .form-box {
            max-width: 450px;
            margin: 60px auto;
            padding: 25px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 0 14px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #1a59e1ff;
        }
        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        input[type=text], input[type=password] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        button {
            margin-top: 25px;
            padding: 12px;
            width: 100%;
            background: #1a59e1ff;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
        }
        button:hover {
            background: #144fcfff;
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
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #1a59e1ff;
            font-weight: bold;
        }
        .back-link:hover {
            color: #0d3fd3;
        }
    </style>
</head>
<body>

<div class="form-box">
    <h2>Ajouter un Administrateur</h2>

    <?php if ($message): ?>
        <p class="message <?= strpos($message, '✅') === 0 ? 'success' : 'error' ?>">
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="ajouter_admin.php">
        <label for="nom_utilisateur">Nom d'utilisateur</label>
        <input type="text" id="nom_utilisateur" name="nom_utilisateur" required />

        <label for="mot_de_passe">Mot de passe</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required />

        <label for="confirmation">Confirmer le mot de passe</label>
        <input type="password" id="confirmation" name="confirmation" required />

        <button type="submit">Ajouter l'Administrateur</button>
    </form>

    <a class="back-link" href="gerer_admins.php">⬅️ Retour à la liste des administrateurs</a>
</div>

</body>
</html>
