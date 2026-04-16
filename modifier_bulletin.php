<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: connexion.php");
    exit();
}

$bulletin_id = $_GET['id'] ?? null;
if (!$bulletin_id) {
    header("Location: dashboard.php");
    exit();
}

// Récupérer le bulletin + étudiant
$sql = "SELECT b.id AS bulletin_id, b.etudiant_id, b.moyenne, b.mention,
               e.nom, e.prenom, e.sexe, e.date_naissance, e.option_etude, e.niveau_etude
        FROM bulletins b
        JOIN etudiants e ON b.etudiant_id = e.id
        WHERE b.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$bulletin_id]);
$record = $stmt->fetch();

if (!$record) {
    echo "Bulletin non trouvé.";
    exit();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Données étudiant
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $sexe = trim($_POST['sexe'] ?? '');
    $date_naissance = trim($_POST['date_naissance'] ?? '');
    $option_etude = trim($_POST['option_etude'] ?? '');
    $niveau_etude = trim($_POST['niveau_etude'] ?? '');

    // Données bulletin
    $moyenne = trim($_POST['moyenne'] ?? '');
    $mention = trim($_POST['mention'] ?? '');

    // Validation simple
    if ($nom === '' || $prenom === '' || $sexe === '' || $date_naissance === '' || $moyenne === '' || !is_numeric($moyenne)) {
        $message = "Veuillez remplir correctement tous les champs obligatoires (Nom, Prénom, Sexe, Date de naissance, Moyenne).";
    } else {
        // Mettre à jour table etudiants
        $sqlUpdateEtudiant = "UPDATE etudiants 
                              SET nom = ?, prenom = ?, sexe = ?, date_naissance = ?, option_etude = ?, niveau_etude = ?
                              WHERE id = ?";
        $stmt = $pdo->prepare($sqlUpdateEtudiant);
        $stmt->execute([$nom, $prenom, $sexe, $date_naissance, $option_etude, $niveau_etude, $record['etudiant_id']]);

        // Mettre à jour table bulletins
        $sqlUpdateBulletin = "UPDATE bulletins 
                              SET moyenne = ?, mention = ?, date_generation = CURRENT_TIMESTAMP 
                              WHERE id = ?";
        $stmt = $pdo->prepare($sqlUpdateBulletin);
        $stmt->execute([$moyenne, $mention, $bulletin_id]);

        header("Location: dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<title>Modifier Bulletin et Étudiant</title>
<style>
  body { font-family: Arial, sans-serif; margin: 30px; background:#f0f4ff; }
  form { max-width: 500px; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);}
  label { display: block; margin-top: 15px; font-weight: bold;}
  input[type="text"], input[type="date"], select { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;}
  button { margin-top: 20px; background-color: #3949ab; color: white; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;}
  button:hover { background-color: #1a237e;}
  .message { color: red; margin-bottom: 15px; }
  a { text-decoration: none; color: #3949ab; display: inline-block; margin-top: 20px; }
</style>
</head>
<body>
  <h1>Modifier le bulletin et les informations de l'étudiant</h1>

  <?php if ($message): ?>
    <div class="message"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <form method="POST">
    <fieldset>
      <legend><strong>Informations Étudiant</strong></legend>

      <label for="nom">Nom *</label>
      <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($record['nom'] ?? '') ?>" required />

      <label for="prenom">Prénom *</label>
      <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($record['prenom'] ?? '') ?>" required />

      <label for="sexe">Sexe *</label>
      <select id="sexe" name="sexe" required>
        <?php
          $sexes = ['Masculin', 'Féminin', 'Autre'];
          $currentSexe = $record['sexe'] ?? '';
          foreach ($sexes as $s) {
              $selected = ($s === $currentSexe) ? 'selected' : '';
              echo "<option value=\"" . htmlspecialchars($s) . "\" $selected>" . htmlspecialchars($s) . "</option>";
          }
        ?>
      </select>

      <label for="date_naissance">Date de naissance *</label>
      <input type="date" id="date_naissance" name="date_naissance" value="<?= htmlspecialchars($record['date_naissance'] ?? '') ?>" required />

      <label for="option_etude">Option</label>
      <input type="text" id="option_etude" name="option_etude" value="<?= htmlspecialchars($record['option_etude'] ?? '') ?>" />

      <label for="niveau_etude">Niveau</label>
      <input type="text" id="niveau_etude" name="niveau_etude" value="<?= htmlspecialchars($record['niveau_etude'] ?? '') ?>" />
    </fieldset>

    <fieldset>
      <legend><strong>Informations Bulletin</strong></legend>

      <label for="moyenne">Moyenne *</label>
      <input type="text" id="moyenne" name="moyenne" value="<?= htmlspecialchars($record['moyenne'] ?? '') ?>" required />

      <label for="mention">Mention</label>
      <select id="mention" name="mention">
        <?php
          $mentions = ['Très bien', 'Bien', 'Assez bien', 'Passable', 'Insuffisant'];
          $currentMention = $record['mention'] ?? '';
          foreach ($mentions as $m) {
              $selected = ($m === $currentMention) ? 'selected' : '';
              echo "<option value=\"" . htmlspecialchars($m) . "\" $selected>" . htmlspecialchars($m) . "</option>";
          }
        ?>
      </select>
    </fieldset>

    <button type="submit">Enregistrer les modifications</button>
  </form>

  <p><a href="liste_bulletin.php">← Retour à la liste des bulletins</a></p>
</body>
</html>
