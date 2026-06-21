<?php
session_start();
require 'db.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>SAÉ 23 - Consultation</title>
    <link rel="stylesheet" href="style/style.css">
    <meta http-equiv="refresh" content="60">
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="consultation.php" style="color:var(--text-main)">Consultation</a></li>
            <li><a href="gestion.php">Gestionnaire</a></li>
            <li><a href="administration.php">Administration</a></li>
            <li><a href="projet.php">Gestion de projet</a></li>
            <li><a href="grafana.php" style="color:#F05A28">Grafana (Live)</a></li>
        </ul>
        <?php if(isset($_SESSION['login'])): ?>
            <div>
                <span style="color:var(--text-muted); margin-right: 15px;">Connecté: <?= htmlspecialchars($_SESSION['login']) ?></span>
                <a href="logout.php" style="color:var(--danger)">Déconnexion</a>
            </div>
        <?php else: ?>
            <div>
                <a href="login.php">Connexion</a>
            </div>
        <?php endif; ?>
    </nav>

    <div class="container">
        <h1>Consultation des Capteurs</h1>
        <p style="color:var(--text-muted)">Affiche la dernière mesure enregistrée pour chaque capteur.</p>
        
        <div class="grid-cards">
            <?php
            $sql = "SELECT c.nom_capteur, c.type, c.unité, c.nom_salle, 
                           (SELECT valeur FROM mesure WHERE nom_capteur = c.nom_capteur ORDER BY date DESC, horaire DESC LIMIT 1) as last_val,
                           (SELECT horaire FROM mesure WHERE nom_capteur = c.nom_capteur ORDER BY date DESC, horaire DESC LIMIT 1) as last_time
                    FROM capteur c";
            $res = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_assoc($res)) {
                $val = $row['last_val'] !== null ? htmlspecialchars($row['last_val']) . " " . htmlspecialchars($row['unité']) : "Aucune donnée";
                $time = $row['last_time'] !== null ? htmlspecialchars($row['last_time']) : "-";
                
                echo "<div class='card'>";
                echo "<h3>" . htmlspecialchars($row['nom_salle']) . " - " . htmlspecialchars($row['type']) . "</h3>";
                echo "<div style='display: flex; justify-content: space-between; align-items: center; margin-top: 1rem;'>";
                echo "<span class='stat-value' style='font-weight: bold;'>" . $val . "</span>";
                echo "<span style='color:var(--text-muted); font-size: 0.9rem;'>Heure: " . $time . "</span>";
                echo "</div>";
                echo "</div>";
            }
            ?>
        </div>
    </div>
</body>
</html>

