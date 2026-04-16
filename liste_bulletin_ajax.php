<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    http_response_code(403);
    exit('Accès refusé');
}

$sql = "SELECT b.id AS bulletin_id, b.etudiant_id, b.moyenne, b.mention,
               e.nom, e.prenom, e.sexe, e.date_naissance, e.id AS matricule
        FROM bulletins b
        JOIN etudiants e ON b.etudiant_id = e.id
        ORDER BY e.nom ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$bulletins = $stmt->fetchAll();

if (empty($bulletins)) {
    echo '<tr><td colspan="7" style="text-align:center;">Aucun bulletin trouvé.</td></tr>';
} else {
    foreach ($bulletins as $b) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($b['matricule'] ?? '') . '</td>';
        echo '<td>' . htmlspecialchars(($b['nom'] ?? '') . ' ' . ($b['prenom'] ?? '')) . '</td>';
        echo '<td>' . htmlspecialchars($b['sexe'] ?? 'Non défini') . '</td>';
        echo '<td>' . htmlspecialchars($b['date_naissance'] ?? 'Non définie') . '</td>';
        echo '<td>' . htmlspecialchars($b['moyenne'] ?? '') . '</td>';
        echo '<td>' . htmlspecialchars($b['mention'] ?? '') . '</td>';
        echo '<td>';
        echo '<a href="bulletin_admin.php?id=' . $b['etudiant_id'] . '" class="btn-view px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition">👁️ Voir</a> ';
        echo '<a href="modifier_bulletin.php?id=' . $b['bulletin_id'] . '" class="btn-view px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">✏️ Modifier</a> ';
        echo '<a href="supprimer_bulletin.php?id=' . $b['bulletin_id'] . '" class="btn-view px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition" onclick="return confirm(\'Supprimer ce bulletin ?\');">🗑️ Supprimer</a>';
        echo '</td>';
        echo '</tr>';
    }
}
