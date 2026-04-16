<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: connexion.php");
    exit();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';
    $confirmation = $_POST['confirmation'] ?? '';
    $matricule = trim($_POST['matricule'] ?? '');
    $option_etude = trim($_POST['option_etude'] ?? '');
    $niveau_etude = trim($_POST['niveau_etude'] ?? '');
    $sexe = trim($_POST['sexe'] ?? '');
    $date_naissance = $_POST['date_naissance'] ?? '';

    if (!$nom || !$prenom || !$email || !$mot_de_passe || !$confirmation || !$matricule || !$option_etude || !$niveau_etude || !$sexe || !$date_naissance) {
        $message = "Tous les champs sont obligatoires.";
    } elseif ($mot_de_passe !== $confirmation) {
        $message = "Les mots de passe ne correspondent pas.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM etudiants WHERE email = ? OR matricule = ?");
        $stmt->execute([$email, $matricule]);

        if ($stmt->fetch()) {
            $message = "Cet email ou ce matricule est déjà utilisé.";
        } else {
            $hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO etudiants (nom, prenom, email, mot_de_passe, matricule, option_etude, niveau_etude, sexe, date_naissance) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$nom, $prenom, $email, $hash, $matricule, $option_etude, $niveau_etude, $sexe, $date_naissance])) {
                $message = "✅ Étudiant ajouté avec succès.";
            } else {
                $message = "Erreur lors de l'insertion dans la base.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un étudiant - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #74ebd5, #9face6);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-box {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
            max-width: 500px;
            width: 100%;
            animation: fadeIn 1s ease-in-out;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            animation: slideDown 0.8s ease;
        }

        label {
            margin-top: 15px;
            display: block;
            color: #444;
            font-weight: 600;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 8px;
            transition: border-color 0.3s;
        }

        input:focus, select:focus {
            border-color: #007bff;
            outline: none;
        }

        button {
            margin-top: 25px;
            padding: 12px;
            width: 100%;
            background: #007bff;
            color: white;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }

        button:hover {
            background: #0056b3;
            transform: scale(1.03);
        }

        .message {
            margin-top: 15px;
            text-align: center;
            font-weight: bold;
        }

        .message.success {
            color: green;
        }

        .message.error {
            color: red;
        }

        @keyframes fadeIn {
            from {opacity: 0; transform: scale(0.95);}
            to {opacity: 1; transform: scale(1);}
        }

        @keyframes slideDown {
            from {opacity: 0; transform: translateY(-20px);}
            to {opacity: 1; transform: translateY(0);}
        }

        @media (max-width: 600px) {
            .form-box {
                padding: 20px;
                margin: 20px;
            }

            h2 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>

<div class="form-box">
    <h2>Ajouter un étudiant</h2>

    <?php if ($message): ?>
        <p class="message <?= str_starts_with($message, '✅') ? 'success' : 'error' ?>">
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="ajouter_etudiant.php">
        <label for="nom">Nom</label>
        <input type="text" id="nom" name="nom" required>

        <label for="prenom">Prénom</label>
        <input type="text" id="prenom" name="prenom" required>

        <label for="sexe">Sexe</label>
        <select id="sexe" name="sexe" required>
            <option value="">-- Choisir --</option>
            <option value="Masculin">Masculin</option>
            <option value="Féminin">Féminin</option>
        </select>

        <label for="date_naissance">Date de naissance</label>
        <input type="date" id="date_naissance" name="date_naissance" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="mot_de_passe">Mot de passe</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required>

        <label for="confirmation">Confirmer le mot de passe</label>
        <input type="password" id="confirmation" name="confirmation" required>

        <label for="matricule">Matricule</label>
        <input type="text" id="matricule" name="matricule" required>

        <label for="option_etude">Option d'étude</label>
        <select id="option_etude" name="option_etude" required>
            <option value="">-- Choisir --</option>
            <option value="GLAR">Genie logiciel et administration reseau (GLAR)</option>
            <option value="RT">Reseau et Telecommunication (RT)</option>
            <option value="MII">MII</option>
            <option value="GC">Genie Civil (GC)</option>
            <option value="ABF">Assurance Banque et Finance (ABF)</option>
            <option value="CGF">CGF</option>
            <option value="DM">Delegue Medical (DM)</option>
            <option value="GEER">GEER</option>
            <option value="EMI">Electronique et Maintenance Industrielle (EMI)</option>
            <option value="ASD">ASD</option>
            <option value="GCH">Genie Chimique (GCH)</option>
        </select>

        <label for="niveau_etude">Niveau d'étude</label>
        <select id="niveau_etude" name="niveau_etude" required>
            <option value="">-- Choisir --</option>
            <option value="L1">Licence 1</option>
            <option value="L2">Licence 2</option>
            <option value="L3">Licence 3</option>
            <option value="M1">Master 1</option>
            <option value="M2">Master 2</option>
        </select>

        <button type="submit">Ajouter l'étudiant</button>

        <div class="mt-6 text-center">
            <a href="liste_bulletin.php" class="inline-block bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 transition">
                ⬅️ Retour à la liste
            </a>
        </div>
    </form>
</div>

</body>
</html>
