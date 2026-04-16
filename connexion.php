<?php
session_start();
require_once '../config/database.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifiant = trim($_POST['identifiant']);
    $mot_de_passe = $_POST['mot_de_passe'];

    if ($identifiant && $mot_de_passe) {
        $stmt = $pdo->prepare("SELECT * FROM etudiants WHERE matricule = ?");
        $stmt->execute([$identifiant]);
        $etudiant = $stmt->fetch();

        if ($etudiant && password_verify($mot_de_passe, $etudiant['mot_de_passe'])) {
            $_SESSION['etudiant_id'] = $etudiant['id'];
            $_SESSION['etudiant_nom'] = $etudiant['nom'];
            $_SESSION['etudiant_prenom'] = $etudiant['prenom'];
            $_SESSION['etudiant_option'] = $etudiant['option_etude'];
            $_SESSION['etudiant_niveau'] = $etudiant['niveau_etude'];
            $_SESSION['etudiant_sexe'] = $etudiant['sexe'];
            $_SESSION['etudiant_naissance'] = $etudiant['date_naissance'];

            header("Location: bulletin.php");
            exit;
        } else {
            $message = "Identifiant ou mot de passe incorrect.";
        }
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Connexion Étudiant</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/css2?family=Manrope&family=Montserrat&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0; padding: 0;
      box-sizing: border-box;
    }

    body {
      background: linear-gradient(-45deg, #1c3f71, #6d9bd7, #7be7ff, #8a00e0);
      background-size: 400% 400%;
      animation: gradientBG 15s ease infinite;
      font-family: 'Manrope', sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    @keyframes gradientBG {
      0%, 100% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
    }

    .login-container {
      background-color: rgba(255, 255, 255, 0.15);
      padding: 2rem;
      border-radius: 20px;
      backdrop-filter: blur(10px);
      box-shadow: 0 0 20px rgba(0,0,0,0.4);
      width: 90%;
      max-width: 400px;
      color: #fff;
    }

    .login-container h2 {
      text-align: center;
      margin-bottom: 1.5rem;
      font-family: 'Montserrat', sans-serif;
    }

    .login-container label {
      font-weight: bold;
    }

    .login-container input[type="text"],
    .login-container input[type="password"] {
      width: 100%;
      padding: 10px;
      border: none;
      border-radius: 8px;
      margin-top: 5px;
      margin-bottom: 15px;
      background: rgba(255, 255, 255, 0.9);
      color: #000;
    }

    .login-container input[type="submit"] {
      width: 100%;
      padding: 10px;
      background-color: #007BFF;
      border: none;
      border-radius: 8px;
      color: white;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s;
    }

    .login-container input[type="submit"]:hover {
      background-color: #0056b3;
    }

    .message {
      text-align: center;
      margin-top: 1rem;
      font-weight: bold;
      color: yellow;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>Connexion Étudiant</h2>
    <form method="POST" action="">
      <label for="identifiant">Matricule :</label>
      <input type="text" id="identifiant" name="identifiant" required>

      <label for="mot_de_passe">Mot de passe :</label>
      <input type="password" id="mot_de_passe" name="mot_de_passe" required>

      <input type="submit" value="Se connecter">
    </form>

    <?php if ($message): ?>
      <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
  </div>
</body>
</html>
