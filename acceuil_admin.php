<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: connexion.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Accueil Admin - Mon École</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;700&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to right, #e3f2fd, #ffffff);
      color: #333;
      display: flex;
      min-height: 100vh;
    }

    .sidebar {
      background-color: #033f9aff;
      color: white;
      width: 240px;
      padding: 30px 20px;
      display: flex;
      flex-direction: column;
    }

    .sidebar h2 {
      font-size: 1.8em;
      margin-bottom: 2rem;
      text-align: center;
    }

    .sidebar a {
      color: white;
      text-decoration: none;
      margin: 12px 0;
      font-size: 1.05em;
      padding: 10px 15px;
      border-radius: 5px;
    }

    .sidebar a:hover {
      background-color: #0958b9ff;
    }

    .content {
      flex-grow: 1;
      padding: 50px 40px;
    }

    .welcome {
      background-color: white;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      text-align: center;
      animation: fadeIn 0.6s ease-in-out;
    }

    .welcome h1 {
      color: #0b58b0ff;
      margin-bottom: 20px;
      font-size: 2.2em;
    }

    .welcome p {
      font-size: 1.1em;
      line-height: 1.6;
      max-width: 600px;
      margin: auto;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 768px) {
      body {
        flex-direction: column;
      }

      .sidebar {
        width: 100%;
        flex-direction: row;
        justify-content: space-around;
        padding: 20px;
      }

      .content {
        padding: 20px;
      }

      .welcome {
        padding: 20px;
      }
    }
  </style>
</head>
<body>
  
   <?php include 'menu_admin.php';?>

  <main class="content">
    <div class="welcome">
      <h1>Bienvenue, Administrateur 🎓</h1>
      <p>
        Cette plateforme vous permet de gérer facilement les étudiants, les matières, les notes, les réclamations et les bulletins scolaires. 
        Utilisez le menu de gauche pour naviguer dans les différentes sections. <br><br>
        Merci de faire vivre ce système 💙.
      </p>
    </div>
  </main>
</body>
</html>
