<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Contact - Groupe EAD</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Manrope&family=Montserrat&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Manrope', sans-serif;
      background-color: #f0f4ff;
    }
    h1, h2 {
      font-family: 'Montserrat', sans-serif;
    }
  </style>
</head>
<body class="min-h-screen flex flex-col">

  <!-- En-tête -->
  <header class="bg-white shadow p-4 flex justify-between items-center">
    <div class="flex items-center gap-3">
      <img src="../assets/images/chapeau.jpg" width="50" height="50" alt="Logo EAD" class="rounded-full">
      <h1 class="text-xl text-blue-700 font-bold">Groupe E.A.D</h1>
    </div>
    <a href="../index.php" class="text-blue-600 font-medium hover:underline">Accueil</a>
  </header>

  <!-- Section Contact -->
  <main class="flex-grow container mx-auto px-4 py-10">
    <h1 class="text-4xl text-center text-indigo-800 font-bold mb-10">Contactez-nous</h1>

    <div class="grid md:grid-cols-2 gap-8 max-w-6xl mx-auto">

      <!-- Infos de contact -->
      <div class="bg-white p-8 rounded-xl shadow space-y-6 text-gray-700">
        <h2 class="text-2xl text-blue-700 font-semibold">Nos Adresses</h2>
        <p>📍 <strong>Brazzaville :</strong> Quartier Moungali</p>
        <p>📍 <strong>Pointe-Noire :</strong> Quartier Mpita</p>

        <h2 class="text-2xl text-blue-700 font-semibold mt-6">Téléphone</h2>
        <p>📞 <strong>PNR :</strong> +242 04 041 80 18</p>

        <h2 class="text-2xl text-blue-700 font-semibold mt-6">Emails</h2>
        <p>📧 <strong>Administration :</strong> <a href="mailto:administration@ead-congo.org" class="text-indigo-600 hover:underline">administration@ead-congo.org</a></p>
        <p>📧 <strong>BZV :</strong> <a href="mailto:eadbzvcongo@gmail.com" class="text-indigo-600 hover:underline">eadbzvcongo@gmail.com</a></p>
      </div>

      <!-- Formulaire -->
      <div class="bg-white p-8 rounded-xl shadow space-y-6">
        <h2 class="text-2xl text-blue-700 font-semibold">Envoyer un message</h2>
        <form method="post" action="#">
          <div class="space-y-4">
            <div>
              <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
              <input type="text" name="nom" id="nom" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
              <input type="email" name="email" id="email" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
              <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
              <textarea name="message" id="message" rows="5" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>
            <div class="text-right">
              <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">Envoyer</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="text-center mt-12">
      <a href="../index.php" class="inline-block bg-indigo-600 text-white px-6 py-2 rounded-full hover:bg-indigo-700 transition">Retour à l'accueil</a>
    </div>
  </main>

  <!-- Pied de page -->
  <footer class="bg-white py-4 flex justify-center gap-6 border-t">
    <img src="../assets/images/chapeau.jpg" alt="Logo" width="40" height="40">
    <a href="https://www.linkedin.com" target="_blank"><img src="../assets/images/in logo.png" width="40" height="40" alt="LinkedIn"></a>
    <a href="https://www.facebook.com" target="_blank"><img src="../assets/images/logo facebook.png" width="40" height="40" alt="Facebook"></a>
  </footer>

</body>
</html>
