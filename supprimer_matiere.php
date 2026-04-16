<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: connexion.php");
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: gerer_matieres.php");
    exit;
}

$id = (int) $_GET['id'];

// Suppression de la matière
$stmt = $pdo->prepare("DELETE FROM matieres WHERE id = ?");
$stmt->execute([$id]);

header("Location: gerer_matieres.php");
exit;
