<?php
// Start session for menu logic
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>SAÉ 23 - Gestion de projet</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="consultation.php">Consultation</a></li>
            <li><a href="gestion.php">Gestionnaire</a></li>
            <li><a href="administration.php">Administration</a></li>
            <li><a href="projet.php">Gestion de projet</a></li>
        </ul>
        <?php if(isset($_SESSION['login'])): ?>
            <div>
                <span style="color:#666; margin-right: 15px;">Connecté: <?= htmlspecialchars($_SESSION['login']) ?></span>
                <a href="logout.php" style="color:red">Déconnexion</a>
            </div>
        <?php else: ?>
            <a href="login.php">Connexion</a>
        <?php endif; ?>
    </nav>

    <div class="container">
        <h1>Gestion de projet</h1>
        <div class="card">
            <h2>GANTT final</h2>
            <img src="images/gantt.png" alt="GANTT Final" style="max-width:100%; border:1px solid #ccc;">
        </div>
        <div class="card">
            <h2>Outils collaboratifs</h2>
            <img src="images/github.png" alt="GitHub" style="max-width:100%; margin-bottom:10px; border:1px solid #ccc;"><br>
            <img src="images/capture.png" alt="Capture Collaborative" style="max-width:100%; border:1px solid #ccc;">
        </div>
        <div class="card">
            <h2>Synthèse personnelle</h2>
            <p>[Détaillez ici le travail de chaque membre du groupe]</p>
        </div>
        <div class="card">
            <h2>Problèmes rencontrés & Solutions proposées</h2>
            <p>[Décrivez ici vos problématiques (SSH, Docker, PHP) et comment vous les avez résolues]</p>
        </div>
        <div class="card">
            <h2>Conclusion</h2>
            <p>[Conclusion sur la satisfaction du cahier des charges]</p>
        </div>
    </div>
</body>
</html>
