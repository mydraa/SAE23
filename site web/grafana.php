<?php
session_start();
require 'db.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>SAÉ 23 - Grafana</title>
    <link rel="stylesheet" href="style/style.css">
    <style>
        .grafana-section {
            margin-bottom: 40px;
        }
        .grafana-section h2 {
            margin-bottom: 15px;
            font-size: 1.5rem;
        }
        .grafana-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .grafana-panel {
            background: #1a1a1a;
            border-radius: 4px;
            overflow: hidden;
            border: 1px solid #333;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        iframe {
            width: 100%;
            height: 250px;
            border: none;
        }
        @media (max-width: 768px) {
            .grafana-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="consultation.php">Consultation</a></li>
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
        <p>Supervision en temps réel des salles via l'intégration InfluxDB & Grafana.</p>
        
        <div class="grafana-section">
            <h2>Salle E208</h2>
            <div class="grafana-grid">
                <div class="grafana-panel">
                    <!-- Remplacez l'URL src par le lien d'intégration (Embed) de votre panel Grafana -->
                    <iframe src="http://<?php echo $_SERVER['SERVER_NAME']; ?>:3000/d-solo/xxxx/dashboard?orgId=1&panelId=1&theme=dark" frameborder="0"></iframe>
                </div>
                <div class="grafana-panel">
                    <iframe src="http://<?php echo $_SERVER['SERVER_NAME']; ?>:3000/d-solo/xxxx/dashboard?orgId=1&panelId=2&theme=dark" frameborder="0"></iframe>
                </div>
            </div>
        </div>

        <div class="grafana-section">
            <h2>Salle E210</h2>
            <div class="grafana-grid">
                <div class="grafana-panel">
                    <iframe src="http://<?php echo $_SERVER['SERVER_NAME']; ?>:3000/d-solo/xxxx/dashboard?orgId=1&panelId=3&theme=dark" frameborder="0"></iframe>
                </div>
                <div class="grafana-panel">
                    <iframe src="http://<?php echo $_SERVER['SERVER_NAME']; ?>:3000/d-solo/xxxx/dashboard?orgId=1&panelId=4&theme=dark" frameborder="0"></iframe>
                </div>
            </div>
        </div>
        
        <div class="card" style="margin-top: 40px; background:rgba(255,255,255,0.05); padding:1.5rem; border:1px dashed #F05A28;">
            <h3 style="color:#F05A28; margin-top:0;">ℹ️ Comment afficher vos vrais graphiques ?</h3>
            <p>1. Allez sur votre interface Grafana sur le port 3000.</p>
            <p>2. Cliquez sur le titre d'un graphique (ex: Température E208) puis sur <strong>Share</strong>.</p>
            <p>3. Allez dans l'onglet <strong>Embed</strong> et copiez l'URL contenue dans le <code>src="..."</code>.</p>
            <p>4. Ouvrez le fichier <code>site web/grafana.php</code> et remplacez les <code>src</code> des 4 iframes par vos propres liens !</p>
        </div>
    </div>
</body>
</html>

