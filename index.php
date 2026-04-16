<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Ecole informatique</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope&family=Montserrat&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    * {margin: 0; padding: 0; box-sizing: border-box;}
    body {
      background: linear-gradient(-45deg, rgba(28, 63, 113, 0.42), rgb(109, 155, 215), rgb(123, 231, 255), rgb(138, 0, 224));
      background-size: 400% 400%;
      animation: gradientBG 15s ease infinite;
      font-family: 'Manrope', sans-serif;
      scroll-behavior: smooth;
    }

    @keyframes gradientBG {
      0%, 100% {background-position: 0% 50%;}
      50% {background-position: 100% 50%;}
    }

    nav.navbar {
      background-color: rgba(0, 0, 0, 0.28);
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
    }

    nav a {
      color: white;
      font-weight: 600;
      margin-right: 20px;
      text-decoration: none;
      transition: 0.3s ease;
    }

    nav a:hover {
      color: rgba(0, 242, 255, 0.6);
    }

    .overlay {
      background-color: rgba(0, 0, 0, 0.1);
      height: 100vh;
      width: 100%;
      position: absolute;
      z-index: -1;
    }

    h1, .arg, .underline {
      text-align: center;
      color: white;
      margin-top: 2rem;
      font-family: 'Montserrat', sans-serif;
    }

    .arg {
      font-size: 2.5rem;
      color: #1e2238ff;
    }

    .underline {
      text-decoration: underline;
      color: white;
      text-align: center;
      margin-bottom: 25px;
      margin-top: 25px;
    }

    .text p {
      text-align: center;
      color: white;
      font-size: 1.2rem;
      margin-bottom: 2rem;
    }

    .contenu, .choixEAD, .vmo, .bas {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 2rem;
      margin: 2rem auto;
      max-width: 1200px;
    }

    .box {
      background: #6cbcacff;
      border-radius: 10px;
      padding: 20px;
      width: 250px;
      text-align: center;
      transition: transform 0.3s;
    }

    .box:hover {
      transform: translateY(-5px);
    }

    .box img {
      width: 45px;
      height: 45px;
      margin-bottom: 10px;
    }

    .box h3, .box h4 {
      color: #333;
    }

    .box p {
      font-size: 14px;
      color: #444;
    }

    em {
      color: rgba(33, 139, 210, 1);
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

    ul {
      color: white;
      margin: auto;
      max-width: 800px;
      padding-left: 20px;
    }

    .vmo .box p {
      font-size: 15px;
    }

    footer {
      padding: 2rem;
      background-color: rgba(0, 0, 0, 0.36);
      color: #0ef;
      font-weight: 600;
      font-family: 'Manrope', sans-serif;
      text-align: center;
    }
  </style>
</head>
<body>

  <div class="overlay"></div>

  <nav class="navbar">
    <div class="nav-left">
      <a href="#">Accueil</a>
      <a href="public/about.php">À propos</a>
    </div>
    <div class="nav-right">
      <a href="public/formation.php">Nos Formations</a>
      <a href="inscription.php">Inscription</a>
    </div>
  </nav>

  <main>
    <h1>Bienvenue à <em>Marielle</em></h1>
    <p class="overline text-center">
      Excellence Académique - Formation de Qualité - Insertion Professionnelle
    </p>

    <h2 class="overline text-center">24 ans d'expérience au service du savoir et de la compétence</h2>

    <div class="contenu">
      <div class="box">
        <img src="assets/images/diplome.png" alt="">
        <h3>Excellence Académique</h3>
        <p>Classée parmi les meilleures universités d'Afrique.</p>
      </div>
      <div class="box">
        <img src="assets/images/teacher.png" alt="">
        <h3>Insertion Professionnelle</h3>
        <p>87% de taux d'emploi dans les 6 mois.</p>
      </div>
      <div class="box">
        <img src="assets/images/globe terrestre.png" alt="">
        <h3>Ouverture Internationale</h3>
        <p>Plus de 150 universités partenaires à travers le monde.</p>
      </div>
    </div>

    <div class="text text-center">
      <a href="admin/connexion.php" class="bubble">Connexion Administrateur</a>
      <a href="etudiant/connexion.php" class="bubble">Résultats Scolaires</a>
    </div>

    <h2 class="arg">Pourquoi choisir l'<em>Marielle</em>?</h2>

    <div class="choixEAD">
      <div class="box"><img src="assets/images/teacher.png" alt=""><h3>Professeurs qualifiés</h3><p>Une équipe pédagogique expérimentée et passionnée.</p></div>
      <div class="box"><img src="assets/images/chap.jpg" alt=""><h3>Formation de Qualité</h3><p>Adaptée au marché de l’emploi et au développement personnel.</p></div>
      <div class="box"><img src="assets/images/diplome.png" alt=""><h3>Diplômes Reconnus</h3><p>Homologués par l’État et appréciés des entreprises.</p></div>
      <div class="box"><img src="assets/images/immeuble.jpeg" alt=""><h3>Présence à BZV et PNR</h3><p>Deux campus modernes à Brazzaville et Pointe-Noire.</p></div>
      <div class="box"><img src="assets/images/sac de travailleur.png" alt=""><h3>Insertion rapide</h3><p>Grâce à des stages et partenariats d'entreprises.</p></div>
      <div class="box"><img src="assets/images/immeuble.jpeg" alt=""><h3>Infrastructures modernes</h3><p>Des équipements au service de l’excellence.</p></div>
    </div>

    <h2 class="underline">Système de formation : <span>LMD</span></h2>

    <div class="lmd text">
      <p>Le système LMD met l'étudiant au cœur de sa formation, basé sur la recherche et la pratique.</p>
      <p>Notre approche repose sur :</p>
      <ul>
        <li>Le professionnalisme</li>
        <li>L’éthique et la morale</li>
        <li>La pratique</li>
        <li>La professionnalisation de l’enseignement</li>
        <li>La capitalisation des unités d’enseignement</li>
        <li>La semestrialisation de la formation</li>
      </ul>
    </div>

    <div class="vmo">
      <div class="box">
        <h4>Notre Mission</h4>
        <p>Offrir une éducation de qualité accessible à tous, en alliant excellence et employabilité.</p>
      </div>
      <div class="box">
        <h4>Notre Vision</h4>
        <p>Devenir leader en Afrique Centrale en innovation pédagogique et impact social.</p>
      </div>
      <div class="box">
        <h4>Nos Valeurs</h4>
        <p>Intégrité, Excellence, Innovation, Respect de chacun.</p>
      </div>
    </div>

    <div id="contact" class="bas">
      <div class="box">
        <h4>Contacts</h4>
        <p>Afrique centrale: Quartier Moi</p>
        <p>Afrique de l'ouest: Quartier me</p>
        <p>PNR : +242 04 041 80 18</p>
        <p>Email : administration@rhym-congo.org</p>
        <p>Email  : mariellecongo@gmail.com</p>
      </div>

      <div class="box">
        <h4>Liens utiles</h4>
        <p><a href="public/formation.php">Nos Formations</a></p>
        <p><a href="inscription.php">Inscription</a></p>
        <p><a href="public/about.php">À propos</a></p>
        <p><a href="public/contact.php">Contact</a></p>
      </div>

      <div class="box">
        <h4>Programmes populaires</h4>
        <p>Commerce & Administration</p>
        <p>Ingénierie</p>
        <p>Sciences & Technologie</p>
        <p>Droit & Sciences sociales</p>
      </div>

      <div class="box">
        <h4>Suivez-nous</h4>
        <a href="https://www.facebook.com/ecoleafricainededeveloppement"><img src="assets/images/logo facebook.png" width="20" height="20" alt="Facebook"></a>
        <a href="https://www.youtube.com/watch?v=Keaa1XuezTo"><img src="assets/images/youtube.png" width="20" height="20" alt="YouTube"></a>
      </div>
    </div>
  </main>

  <footer>
    &copy; <?= date('Y') ?> Marielle - Tous droits réservés
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
