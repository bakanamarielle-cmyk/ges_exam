<?php
session_start();
require_once '../config/database.php';

// Vérifier que l'admin est connecté
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../connexion.php");
    exit();
}

// Vérifier que l'ID de l'administrateur à supprimer est passé en GET
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID administrateur manquant.";
    exit();
}

$admin_id = (int) $_GET['id'];

// Préparer et exécuter la suppression
$stmt = $pdo->prepare("DELETE FROM admins WHERE id = ?");
$success = $stmt->execute([$admin_id]);

if ($success) {
    // Redirection vers la liste des admins après suppression
    header("Location: gerer_admins.php?msg=admin_supprime");
    exit();
} else {
    echo "Erreur lors de la suppression de l'administrateur.";
}
