<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: connexion.php");
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: gerer_notes.php");
    exit;
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("DELETE FROM notes WHERE id = ?");
$stmt->execute([$id]);

header("Location: gerer_notes.php");
exit;
