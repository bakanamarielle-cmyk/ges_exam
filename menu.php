<?php
// menu.php
?>

<nav class="top-menu">
    <a href="bulletin.php">📋 Mon Bulletin</a>
    <a href="reclamation.php" aria-current="<?= basename($_SERVER['PHP_SELF']) === 'reclamation.php' ? 'page' : '' ?>">✉️ Réclamation</a>
    <a href="bulletin_pdf.php" target="_blank" class="text-blue-700 hover:text-blue-900">📄 Télécharger le PDF</a>
    <a href="../index.php" class="text-red-600 hover:text-red-700">🚪 Retour à l'Accueil</a>
</nav>

<style>
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
    nav.top-menu a[aria-current="page"] {
        font-weight: 700;
        text-decoration: underline;
        pointer-events: none;
    }
</style>
