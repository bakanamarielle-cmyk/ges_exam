<!-- menu_admin.php -->

<style>
    /* Styles du menu latéral */
    .sidebar {
        background-color: #2563eb; /* Bleu Tailwind 600 */
        color: white;
        height: 100vh;
        width: 16rem; /* 64 */
        position: fixed;
        top: 0;
        left: 0;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .sidebar h1 {
        padding: 1.5rem;
        font-weight: 700;
        font-size: 1.5rem;
        border-bottom: 2px solid #1e40af; /* Bleu plus foncé */
        text-align: center;
    }
    .sidebar ul {
        list-style: none;
        margin: 0;
        padding: 0 0 2rem 0;
        flex-grow: 1;
    }
    .sidebar li {
        margin: 0;
    }
    .sidebar a {
        display: block;
        padding: 0.75rem 1rem;
        color: white;
        text-decoration: none;
        font-weight: 600;
        transition: background-color 0.3s ease;
    }
    .sidebar a:hover {
        background-color: #1d4ed8; /* Bleu Tailwind 700 */
    }
    .sidebar a.active {
        background-color: #1e40af; /* Bleu Tailwind 800 */
        font-weight: 700;
        box-shadow: inset 4px 0 0 0 #facc15; /* barre jaune à gauche */
    }
    .sidebar a.logout {
        margin-top: auto;
        background-color: #dc2626; /* Rouge Tailwind 600 */
        color: #fee2e2;
        font-weight: 700;
        text-align: center;
    }
    .sidebar a.logout:hover {
        background-color: #b91c1c; /* Rouge Tailwind 700 */
        color: white;
    }
    /* Scrollbar */
    .sidebar::-webkit-scrollbar {
        width: 6px;
    }
    .sidebar::-webkit-scrollbar-thumb {
        background-color: #3b82f6;
        border-radius: 10px;
    }
</style>

<nav class="sidebar">
    <h1>GESTION DES EXAMENS</h1>
    <ul>
        <li><a href="acceuil_admin.php" class="<?= ($_SERVER['PHP_SELF'] ?? '') === '/acceuil_admin.php' ? 'active' : '' ?>">🏠 Accueil</a></li>
        <li><a href="gerer_etudiants.php" class="<?= ($_SERVER['PHP_SELF'] ?? '') === '/gerer_etudiants.php' ? 'active' : '' ?>">👨‍🎓 Gérer Étudiants</a></li>
        <li><a href="gerer_admins.php" class="<?= ($_SERVER['PHP_SELF'] ?? '') === '/gerer_admins.php' ? 'active' : '' ?>">👨‍🎓 Liste Administrateurs</a></li>
        <li><a href="gerer_matieres.php" class="<?= ($_SERVER['PHP_SELF'] ?? '') === '/gerer_matieres.php' ? 'active' : '' ?>">📚 Gérer Matières</a></li>
        <li><a href="gerer_notes.php" class="<?= ($_SERVER['PHP_SELF'] ?? '') === '/gerer_notes.php' ? 'active' : '' ?>">📝 Gérer Notes</a></li>
        <li><a href="liste_bulletin.php" class="<?= ($_SERVER['PHP_SELF'] ?? '') === '/liste_bulletin.php' ? 'active' : '' ?>">📋 Gérer Bulletins</a></li>
        <li><a href="reclamations.php" class="<?= ($_SERVER['PHP_SELF'] ?? '') === '/reclamations.php' ? 'active' : '' ?>">📩 Réclamations</a></li>
        <li><a href="../index.php" class="logout">🚪 Retour à l'Accueil</a></li>
    </ul>
</nav>
