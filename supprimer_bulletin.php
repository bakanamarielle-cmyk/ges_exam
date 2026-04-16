<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: connexion.php");
    exit();
}

$bulletin_id = $_GET['id'] ?? null;
if (!$bulletin_id) {
    header("Location: liste_bulletin.php");
    exit();
}

// Suppression sécurisée
$stmt = $pdo->prepare("DELETE FROM bulletins WHERE id = ?");
$stmt->execute([$bulletin_id]);

header("Location: liste_bulletin.php");
exit();
