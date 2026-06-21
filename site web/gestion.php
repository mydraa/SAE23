<?php
session_start();
require 'db.php';

if(!isset($_SESSION['login']) || $_SESSION['role'] !== 'gestionnaire') {
    header("Location: login.php");
    exit;
}
$id_bat = $_SESSION['id_bat'];

$stmt = mysqli_prepare($conn, "SELECT nom FROM batiment WHERE ID_bat = ?");
mysqli_stmt_bind_param($stmt, "i", $id_bat);
mysqli_stmt_execute($stmt);
$bat_nom = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['nom'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>SAÉ 23 - Gestion</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="consultation.php">Consultation</a></li>
            <li><a href="gestion.php" style="color:var(--text-main)">Gestionnaire</a></li>
            <li><a href="administration.php">Administration</a></li>
            <li><a href="projet.php">Gestion de projet</a></li>
            <li><a href="grafana.php" style="color:#F05A28">Grafana (Live)</a></li>
        </ul>
        <div>
            <span style="color:var(--text-muted); margin-right: 15px;">Connecté: <?= htmlspecialchars($_SESSION['login']) ?></span>
            <a href="logout.php" style="color:var(--danger)">Déconnexion</a>
        </div>
    </nav>

    <div class="container">
        <h1>Gestion : Bâtiment <?= htmlspecialchars($bat_nom) ?></h1>
        <p style="color:var(--text-muted)">Statistiques des capteurs de votre bâtiment.</p>

        <?php
        $sql = "SELECT c.nom_capteur, c.type, c.unité, c.nom_salle 
                FROM capteur c 
                JOIN salle s ON c.nom_salle = s.nom_salle 
                WHERE s.ID = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id_bat);
        mysqli_stmt_execute($stmt);
        $capteurs = mysqli_stmt_get_result($stmt);

        while($cap = mysqli_fetch_assoc($capteurs)) {
            $nom_cap = $cap['nom_capteur'];
            
            $stat_sql = "SELECT MIN(valeur) as min_v, MAX(valeur) as max_v, AVG(valeur) as avg_v FROM mesure WHERE nom_capteur = ?";
            $stat_stmt = mysqli_prepare($conn, $stat_sql);
            mysqli_stmt_bind_param($stat_stmt, "s", $nom_cap);
            mysqli_stmt_execute($stat_stmt);
            $stats = mysqli_fetch_assoc(mysqli_stmt_get_result($stat_stmt));
            
            $min = $stats['min_v'] !== null ? round($stats['min_v'], 2) : "-";
            $max = $stats['max_v'] !== null ? round($stats['max_v'], 2) : "-";
            $avg = $stats['avg_v'] !== null ? round($stats['avg_v'], 2) : "-";

            echo "<div class='card'>";
            echo "<h2>" . htmlspecialchars($cap['nom_salle']) . " - Capteur " . htmlspecialchars($cap['type']) . "</h2>";
            echo "<div class='grid-cards' style='margin-bottom:1.5rem;'>";
            echo "<div class='card' style='background:rgba(255,255,255,0.05); border:none; padding:1rem;'>Moyenne<br><span class='stat-value'>$avg</span> " . htmlspecialchars($cap['unité']) . "</div>";
            echo "<div class='card' style='background:rgba(255,255,255,0.05); border:none; padding:1rem;'>Minimum<br><span class='stat-value'>$min</span> " . htmlspecialchars($cap['unité']) . "</div>";
            echo "<div class='card' style='background:rgba(255,255,255,0.05); border:none; padding:1rem;'>Maximum<br><span class='stat-value'>$max</span> " . htmlspecialchars($cap['unité']) . "</div>";
            echo "</div>";

            $hist_sql = "SELECT * FROM mesure WHERE nom_capteur = ? ORDER BY date DESC, horaire DESC LIMIT 10";
            $hist_stmt = mysqli_prepare($conn, $hist_sql);
            mysqli_stmt_bind_param($hist_stmt, "s", $nom_cap);
            mysqli_stmt_execute($hist_stmt);
            $hist = mysqli_stmt_get_result($hist_stmt);
            
            echo "<table>";
            echo "<thead><tr><th>Date</th><th>Heure</th><th>Valeur</th></tr></thead>";
            echo "<tbody>";
            while($m = mysqli_fetch_assoc($hist)) {
                echo "<tr><td>" . htmlspecialchars($m['date']) . "</td><td>" . htmlspecialchars($m['horaire']) . "</td><td>" . htmlspecialchars($m['valeur']) . "</td></tr>";
            }
            echo "</tbody></table>";
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>

