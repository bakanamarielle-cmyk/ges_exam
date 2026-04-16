<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>À propos de l'EAD</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Manrope&family=Montserrat&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Manrope', sans-serif;
      background: #f0f4ff;
      color: #1e293b;
    }
    .story-title {
      font-family: 'Montserrat', sans-serif;
    }
    #texte-supplementaire {
      display: none;
    }
  </style>
</head>
<body class="min-h-screen flex flex-col">

  <header class="flex justify-between items-center px-6 py-4 bg-white shadow">
    <div class="flex items-center space-x-3">
      <img src="../assets/images/chapeau.jpg" width="50" height="50" alt="Logo EAD" class="rounded-full" />
      <span class="text-xl font-bold text-blue-700">E.A.D</span>
    </div>
    <nav>
      <a href="../index.php" class="text-blue-600 font-medium hover:underline">Accueil</a>
    </nav>
  </header>

  <!-- CONTENU PRINCIPAL -->
  <main class="flex-grow container mx-auto px-4 py-10">
    <h1 class="story-title text-4xl text-blue-800 text-center font-bold mb-8">Notre Histoire</h1>

    <div class="bg-white rounded-xl shadow-lg p-8 max-w-4xl mx-auto">
      <p class="mb-4 leading-relaxed">
        Fondé en 2001 par M. <strong>MADZOU Vincent</strong>, le Groupe E.A.D est une institution pionnière de l'enseignement supérieur au Congo. 
        Notre ambition : rendre l'éducation <em>accessible, moderne</em> et <em>axée sur l'emploi</em>.
      </p>
      <p class="mb-4">
        Présents à Brazzaville et Pointe-Noire, nous avons déjà formé des milliers d’étudiants prêts à relever les défis du marché du travail.
      </p>

      <ul class="list-disc list-inside text-gray-700 space-y-2 mb-6">
        <li>🎓 Formations 100% en ligne et en présentiel</li>
        <li>📜 Diplômes reconnus à l'international</li>
        <li>👩‍🏫 Formateurs expérimentés & pédagogie innovante</li>
        <li>💸 Frais d’inscription abordables</li>
      </ul>

      <!-- TEXTE SUPPLEMENTAIRE -->
      <div id="texte-supplementaire" class="text-gray-700">
        <p class="mb-4">Notre approche repose sur l’innovation technologique et la rigueur académique. Les étudiants bénéficient d’un environnement d’apprentissage dynamique, où la pratique occupe une place centrale.</p>
        <p>Nous collaborons activement avec des entreprises partenaires pour adapter nos programmes aux besoins réels du marché.</p>
      </div>

      <!-- BOUTONS -->
      <div class="flex flex-col sm:flex-row justify-center gap-4 mt-6">
        <button id="btn-lire-plus" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-full transition">Voir plus</button>
        <a href="contact.php" class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-2 rounded-full transition text-center">Nous contacter</a>
      </div>
    </div>
  </main>

  <!-- PIED DE PAGE -->
  <footer class="bg-white border-t mt-10 py-4 flex justify-center space-x-6">
    <a href="../index.php">
      <img src="../assets/images/chapeau.jpg" width="40" height="40" alt="Logo EAD" />
    </a>
    <a href="https://www.linkedin.com" target="_blank">
      <img src="../assets/images/in logo.png" alt="LinkedIn" width="40" height="40" />
    </a>
    <a href="https://www.facebook.com" target="_blank">
      <img src="../assets/images/logo facebook.png" alt="Facebook" width="40" height="40" />
    </a>
  </footer>

  <script>
    const btn = document.getElementById('btn-lire-plus');
    const texte = document.getElementById('texte-supplementaire');
    let visible = false;

    btn.addEventListener('click', () => {
      visible = !visible;
      texte.style.display = visible ? 'block' : 'none';
      btn.textContent = visible ? 'Voir moins' : 'Voir plus';
    });
  </script>

</body>
</html>
