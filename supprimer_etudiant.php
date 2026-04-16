<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: connexion.php");
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: gerer_etudiants.php");
    exit;
}

$id = (int) $_GET['id'];

// Suppression de l’étudiant
$stmt = $pdo->prepare("DELETE FROM etudiants WHERE id = ?");
$stmt->execute([$id]);

header("Location: gerer_etudiants.php");
exit;
