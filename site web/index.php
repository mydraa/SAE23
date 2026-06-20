<?php
session_start();
require 'db.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>SAÉ 23 - Accueil</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="consultation.php">Consultation</a></li>
            <li><a href="gestion.php">Gestionnaire</a></li>
            <li><a href="administration.php">Administration</a></li>
        </ul>
        <?php if(isset($_SESSION['login'])): ?>
            <div>
                <span style="color:var(--text-muted); margin-right: 15px;">Connecté: <?= htmlspecialchars($_SESSION['login']) ?></span>
                <a href="logout.php" style="color:var(--danger)">Déconnexion</a>
            </div>
        <?php else: ?>
            <a href="login.php">Connexion</a>
        <?php endif; ?>
    </nav>
    <div class="container">
        <div class="card">
            <h1>Projet SAÉ 23 — Supervision IoT</h1>
            <p>Bienvenue sur l'application de supervision et de gestion des données environnementales des salles de l'IUT.</p>
            
            <h2>Bâtiments Gérés</h2>
            <div class="grid-cards">
                <?php
                $res = mysqli_query($conn, "SELECT * FROM batiment");
                while($row = mysqli_fetch_assoc($res)) {
                    echo "<div class='card' style='background:rgba(255,255,255,0.05); padding: 1.5rem; border:none;'>";
                    echo "<h3>Bâtiment: " . htmlspecialchars($row['nom']) . "</h3>";
                    
                    $salles = mysqli_prepare($conn, "SELECT nom_salle, type FROM salle WHERE ID = ?");
                    mysqli_stmt_bind_param($salles, "i", $row['ID_bat']);
                    mysqli_stmt_execute($salles);
                    $salles_res = mysqli_stmt_get_result($salles);
                    echo "<ul>";
                    while($s = mysqli_fetch_assoc($salles_res)) {
                        echo "<li>Salle " . htmlspecialchars($s['nom_salle']) . " (" . htmlspecialchars($s['type']) . ")</li>";
                    }
                    echo "</ul>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>
        <div class="card">
            <h2>Mentions Légales</h2>
            <p style="color:var(--text-muted)">Projet réalisé dans le cadre du BUT Réseaux et Télécommunications. © 2026</p>
        </div>
    </div>
</body>
</html>
