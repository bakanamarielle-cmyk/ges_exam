<?php
session_start();
require_once 'config/database.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = trim($_POST["nom"] ?? '');
    $prenom = trim($_POST["prenom"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $matricule = trim($_POST["matricule"] ?? '');
    $option_etude = trim($_POST["option_etude"] ?? '');
    $niveau_etude = trim($_POST["niveau_etude"] ?? '');
    $mot_de_passe = $_POST["mot_de_passe"] ?? '';
    $confirmation = $_POST["confirmation"] ?? '';
    $sexe = trim($_POST["sexe"] ?? '');
    $date_naissance = $_POST["date_naissance"] ?? '';

    if ($mot_de_passe !== $confirmation) {
        $message = "Les mots de passe ne correspondent pas.";
    } elseif (empty($nom) || empty($prenom) || empty($email) || empty($matricule) || empty($option_etude) || empty($niveau_etude) || empty($mot_de_passe) || empty($sexe) || empty($date_naissance)) {
        $message = "Tous les champs sont obligatoires.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM etudiants WHERE email = ? OR matricule = ?");
        $stmt->execute([$email, $matricule]);

        if ($stmt->fetch()) {
            $message = "Cet email ou ce matricule est déjà utilisé.";
        } else {
            $hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO etudiants (nom, prenom, email, mot_de_passe, matricule, option_etude, niveau_etude, sexe, date_naissance)
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nom, $prenom, $email, $hash, $matricule, $option_etude, $niveau_etude, $sexe, $date_naissance]);

            $message = "✅ Inscription réussie ! Vous pouvez maintenant vous connecter.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Inscription - ges_exam</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link rel="stylesheet" href="assets/css/style.css" />
  <link rel="stylesheet" href="assets/css/form.css" />
  <link rel="stylesheet" href="assets/css/animations.css" />
  <link rel="stylesheet" href="assets/css/responsive.css" />

  <style>
    .inscription-box {
      max-width: 500px;
      margin: 80px auto;
      padding: 30px;
      border-radius: 15px;
      background: white;
      box-shadow: 0 0 10px rgba(0,0,0,0.15);
      animation: pop-in 0.6s ease;
    }
    .inscription-box h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    .message {
      font-weight: bold;
      text-align: center;
      margin-bottom: 15px;
    }
    .message.success {
      color: green;
    }
    .message.error {
      color: red;
    }
    label {
      display: block;
      margin-top: 15px;
    }
    input[type="text"],
    input[type="email"],
    input[type="password"],
    input[type="date"],
    select {
      width: 100%;
      padding: 8px 10px;
      margin-top: 5px;
      border-radius: 5px;
      border: 1px solid #ccc;
      box-sizing: border-box;
    }
    button.login-btn {
      margin-top: 20px;
      width: 100%;
      padding: 12px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
    }
    button.login-btn:hover {
      background-color: #0056b3;
    }
    .register-link {
      text-align: center;
      margin-top: 15px;
    }
    .register-link a {
      color: #007bff;
      text-decoration: none;
    }
    .register-link a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="inscription-box">
    <h2>S'inscrire à l'EAD</h2>

    <?php if (!empty($message)): ?>
      <div class="message <?= str_contains($message, '✅') ? 'success' : 'error' ?>">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>

    <form action="inscription.php" method="POST">
      <label for="nom">Nom</label>
      <input type="text" name="nom" id="nom" required>

      <label for="prenom">Prénom</label>
      <input type="text" name="prenom" id="prenom" required>

      <label for="sexe">Sexe</label>
      <select name="sexe" id="sexe" required>
        <option value="" disabled selected>Choisissez le sexe</option>
        <option value="Masculin">Masculin</option>
        <option value="Féminin">Féminin</option>
      </select>

      <label for="date_naissance">Date de naissance</label>
      <input type="date" name="date_naissance" id="date_naissance" required>

      <label for="email">Email</label>
      <input type="email" name="email" id="email" required>

      <label for="matricule">Matricule</label>
      <input type="text" name="matricule" id="matricule" required placeholder="Exemple : MAT1234">

      <label for="option_etude">Option d'étude</label>
        <select id="option_etude" name="option_etude" required>
            <option value="">-- Choisir --</option>
            <option value="GLAR">Genie logiciel et administration reseau (GLAR)</option>
            <option value="RT">Reseau et Telecommunication (RT) </option>
            <option value="MII">Maintenace Industrielle (MII)</option>
            <option value="GC">Genie Civil (GC)</option>
            <option value="ABF">Assurance Banque et Finance (ABF)</option>
            <option value="CGF">Comptabilite Gestion Financiere (CGF)</option>
            <option value="DM">Delegue Medical (DM)</option>
            <option value="GEER">Genie Electrique et Energie Renouvellable (GEER)</option>
            <option value="EMI">Electronique et Maintenance Industrielle (EMI)</option>
            <option value="ASD">ASD</option>
            <option value="GCH">Genie Chimique (GCH)</option>
        </select>

      <label for="niveau_etude">Niveau d'étude</label>
      <select name="niveau_etude" id="niveau_etude" required>
        <option value="" disabled selected>Choisissez un niveau</option>
        <option value="L1">Licence 1</option>
        <option value="L2">Licence 2</option>
        <option value="L3">Licence 3</option>
        <option value="M1">Master 1</option>
        <option value="M2">Master 2</option>
      </select>

      <label for="mot_de_passe">Mot de passe</label>
      <input type="password" name="mot_de_passe" id="mot_de_passe" required>

      <label for="confirmation">Confirmer le mot de passe</label>
      <input type="password" name="confirmation" id="confirmation" required>

      <button type="submit" class="login-btn">Valider</button>

      <div class="register-link">
        <p>Déjà un compte ? <a href="/ges_examen/etudiant/connexion.php">Se connecter</a></p>
      </div> 
    </form>
        <a class="submit" href="index.php">⬅️ Retour à l'Acceuil</a>
  </div>
</body>
</html>
