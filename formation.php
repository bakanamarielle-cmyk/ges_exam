<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Nos Formations - EAD</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Manrope&family=Montserrat&display=swap" rel="stylesheet">
  <style>
    * {margin: 0; padding: 0; box-sizing: border-box;}
    body {
      font-family: 'Manrope', sans-serif;
      background: linear-gradient(-45deg, rgba(28, 63, 113, 0.42), rgb(109, 155, 215), rgb(123, 231, 255), rgb(138, 0, 224));
      background-size: 400% 400%;
      animation: gradientBG 15s ease infinite;
      scroll-behavior: smooth;
    }
    @keyframes gradientBG {
      0%, 100% {background-position: 0% 50%;}
      50% {background-position: 100% 50%;}
    }

    .navbar {
      background-color: rgba(0, 0, 0, 0.28);
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
    }

    .navbar a {
      color: white;
      text-decoration: none;
      font-weight: bold;
      margin-right: 20px;
      transition: color 0.3s ease;
    }

    .navbar a:hover {
      color: rgba(0, 242, 255, 0.6);
    }

    h1, h2, h3 {
      text-align: center;
      color: white;
      font-family: 'Montserrat', sans-serif;
    }

    h1 {
      margin-top: 2rem;
      font-size: 2.5rem;
    }

    h2 {
      margin-top: 2rem;
      font-size: 1.8rem;
    }

    .box-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 30px;
      margin: 2rem auto;
      max-width: 1200px;
    }

    .box {
      background: #ffffffdd;
      border-radius: 12px;
      padding: 20px;
      width: 280px;
      text-align: center;
      transition: transform 0.3s;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    .box:hover {
      transform: translateY(-8px);
    }

    .box img {
      width: 70px;
      height: 70px;
      margin-bottom: 10px;
    }

    .box p {
      font-size: 14px;
      color: #444;
    }

    .box h3, .box h2 {
      color: #003366;
      font-weight: bold;
    }

    .deb h3 {
      font-size: 1rem;
      margin-top: 1rem;
      color: #005b99;
    }

    .deb p {
      font-size: 13px;
      color: #333;
    }

    .forme {
      background-color: rgba(0, 0, 0, 0.2);
      padding: 1rem;
      text-align: center;
      color: white;
      font-size: 1.5rem;
      font-weight: bold;
      letter-spacing: 1px;
    }

    .bubble {
      display: inline-block;
      margin: 10px;
      padding: 10px 20px;
      background-color: #3498db;
      color: white;
      border-radius: 25px;
      text-decoration: none;
      font-weight: bold;
      transition: 0.3s;
    }

    .bubble:hover {
      background-color: #2980b9;
      transform: scale(1.05);
    }

  </style>
</head>
<body>

  <nav class="navbar">
    <div class="nav-left">
      <a href="../index.php">Accueil</a>
      <a href="about.php">À propos</a>
    </div>
    <div class="nav-right">
      <a href="../inscription.php">Inscription</a>
      <a href="formation.php">Formations</a>
    </div>
  </nav>

  <div class="forme">Nos Formations</div>
  <h1>Explorez Nos Formations et Leurs Débouchés</h1>

  <!-- Domaine Commercial -->
  <h2>Domaine Commercial ou Administratif</h2>
  <section class="box-container">
    <div class="box">
      <img src="../assets/images/abf.jpeg" alt="">
      <h3>Assurance Banque et Finance</h3>
      <p>Spécialisation en secteur bancaire et financier.</p>
      <div class="deb">
        <h3>Débouchés :</h3>
        <p><img src="../assets/images/sac de travailleur.png" width="20"> Conseiller en assurance</p>
        <p><img src="../assets/images/sac de travailleur.png" width="20"> Banquier d'affaires</p>
        <p><img src="../assets/images/sac de travailleur.png" width="20"> Gestionnaire de portefeuille</p>
      </div>
    </div>

    <div class="box">
      <img src="../assets/images/compta-gestionfin.png" alt="">
      <h3>Comptabilité et Gestion Financière</h3>
      <p>Gestion comptable et financière des entreprises.</p>
      <div class="deb">
        <h3>Débouchés :</h3>
        <p><img src="../assets/images/sac de travailleur.png" width="20"> Comptable</p>
        <p><img src="../assets/images/sac de travailleur.png" width="20"> Analyste financier</p>
        <p><img src="../assets/images/sac de travailleur.png" width="20"> Contrôleur de gestion</p>
      </div>
    </div>

    <div class="box">
      <img src="../assets/images/mac.jpeg" alt="">
      <h3>Marketing et Action Commerciale</h3>
      <p>Expertise marketing et stratégie commerciale.</p>
      <div class="deb">
        <h3>Débouchés :</h3>
        <p><img src="../assets/images/sac de travailleur.png" width="20"> Chef de produit</p>
        <p><img src="../assets/images/sac de travailleur.png" width="20"> Responsable marketing</p>
        <p><img src="../assets/images/sac de travailleur.png" width="20"> Directeur commercial</p>
      </div>
    </div>
  </section>

  <!-- Domaine Industriel -->
  <h2>Domaine Industriel</h2>
  <section class="box-container">
    <div class="box">
      <img src="../assets/images/MII.jpeg" alt="">
      <h3>Maintenance Industrielle et Instrumentation</h3>
      <p>Pour les techniciens et ingénieurs du secteur industriel.</p>
      <div class="deb">
        <h3>Débouchés :</h3>
        <p><img src="../assets/images/sac de travailleur.png" width="20"> Technicien de maintenance</p>
        <p><img src="../assets/images/sac de travailleur.png" width="20"> Ingénieur en maintenance</p>
        <p><img src="../assets/images/sac de travailleur.png" width="20"> Responsable maintenance</p>
      </div>
    </div>

    <div class="box">
      <img src="../assets/images/gc.jpeg" alt="">
      <h3>Génie Civil</h3>
      <p>Construction, infrastructure et chantiers.</p>
      <div class="deb">
        <h3>Débouchés :</h3>
        <p><img src="../assets/images/sac de travailleur.png" width="20"> Chef de projet</p>
        <p><img src="../assets/images/sac de travailleur.png" width="20"> Ingénieur civil</p>
        <p><img src="../assets/images/sac de travailleur.png" width="20"> Conducteur de travaux</p>
      </div>
    </div>
  </section>

  <!-- Domaine Technologique -->
  <h2>Domaine Technologique</h2>
  <section class="box-container">
    <div class="box">
      <img src="../assets/images/gl.jpeg" alt="">
      <h3>Génie Logiciel</h3>
      <p>Développement de logiciels et systèmes embarqués.</p>
      <div class="deb">
        <h3>Débouchés :</h3>
        <p><img src="../assets/images/sac de travailleur.png" width="20"> Développeur logiciel</p>
        <p><img src="../assets/images/sac de travailleur.png" width="20"> Ingénieur systèmes</p>
        <p><img src="../assets/images/sac de travailleur.png" width="20"> Architecte logiciel</p>
      </div>
    </div>
  </section>

  <footer class="text-center mt-5 text-white py-3" style="background-color: rgba(0, 0, 0, 0.36);">
    &copy; <?= date('Y') ?> Ecole Africaine de Développement - Tous droits réservés
  </footer>

</body>
</html>
