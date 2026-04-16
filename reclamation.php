<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['etudiant_id'])) {
    header("Location: ../connexion.php");
    exit();
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $contenu = trim($_POST['message'] ?? '');

    if (!empty($contenu)) {
        $stmt = $pdo->prepare("INSERT INTO reclamations (etudiant_id, message) VALUES (?, ?)");
        $stmt->execute([$_SESSION['etudiant_id'], $contenu]);
        $message = "Réclamation envoyée avec succès.";
    } else {
        $message = "Le champ message est requis.";
    }
}

// Récupérer l'historique des réclamations de l'étudiant
$stmt = $pdo->prepare("SELECT message, statut, date_envoi FROM reclamations WHERE etudiant_id = ? ORDER BY date_envoi DESC");
$stmt->execute([$_SESSION['etudiant_id']]);
$reclamations = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <title>Réclamation Étudiant</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Menu horizontal en haut - discret */
        nav.top-menu {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: rgba(26, 35, 126, 0.15);
            color: white;
            display: flex;
            justify-content: center;
            gap: 2rem;
            padding: 0.75rem 1rem;
            backdrop-filter: blur(5px);
            z-index: 1000;
        }
        nav.top-menu a {
            color: #1a237e;
            font-weight: 600;
            text-decoration: none;
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            transition: background-color 0.3s ease;
            font-size: 1rem;
        }
        nav.top-menu a:hover {
            background-color: #3949ab;
            color: white;
        }
        main.content {
            padding-top: 4.5rem; /* espace pour menu fixe */
        }
        .animate-fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-blue-100 to-blue-200 min-h-screen flex flex-col">

    <?php include 'menu.php'; ?>

    <main class="content flex-grow p-8 max-w-xl w-full mx-auto animate-fade-in">

        <h1 class="text-3xl font-extrabold text-blue-900 mb-8 text-center">Envoyer une réclamation</h1>

        <?php if ($message): ?>
            <div class="mb-6 p-4 rounded bg-blue-100 text-blue-800 text-center text-sm font-semibold">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6 bg-white rounded-xl shadow-md p-6 mb-10">
            <textarea 
                name="message" 
                rows="6" 
                placeholder="Votre message ici..." 
                class="w-full p-4 border border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none text-gray-800"
                required
            ></textarea>

            <button 
                type="submit" 
                class="w-full bg-blue-700 hover:bg-blue-800 text-white font-semibold py-3 rounded-lg transition duration-300 ease-in-out"
            >
                Envoyer
            </button>
        </form>

        <?php if (!empty($reclamations)): ?>
            <h2 class="text-2xl font-bold text-blue-800 mb-4 text-center">📜 Historique des réclamations</h2>
            <div class="space-y-4">
                <?php foreach ($reclamations as $rec): ?>
                    <div class="bg-white rounded-lg shadow p-4 border-l-4 
                        <?= $rec['statut'] === 'traitée' ? 'border-green-500' : 'border-yellow-500' ?>">
                        <p class="text-gray-800 mb-2 whitespace-pre-line"><?= htmlspecialchars($rec['message']) ?></p>
                        <div class="text-sm text-gray-600 flex justify-between">
                            <span>Envoyée le : <?= date('d/m/Y H:i', strtotime($rec['date_envoi'])) ?></span>
                            <span class="font-semibold 
                                <?= $rec['statut'] === 'traitée' ? 'text-green-700' : 'text-yellow-700' ?>">
                                <?= ucfirst($rec['statut']) ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </main>

</body>
</html>
