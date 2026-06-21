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
        
        <div class="grafana-section">
            <h2>Salle E208</h2>
            <div class="grafana-grid">
                <div class="grafana-panel">
                    <iframe src="http://<?php echo $_SERVER['SERVER_NAME']; ?>:3000/d-solo/7872e7b2-942f-466a-b1cb-acd58f68aec4/sae23-e28094-supervision-des-salles-iot?orgId=1&panelId=3&theme=dark" frameborder="0"></iframe>
                </div>
                <div class="grafana-panel">
                    <iframe src="http://<?php echo $_SERVER['SERVER_NAME']; ?>:3000/d-solo/7872e7b2-942f-466a-b1cb-acd58f68aec4/sae23-e28094-supervision-des-salles-iot?orgId=1&panelId=4&theme=dark" frameborder="0"></iframe>
                </div>
            </div>
        </div>

        <div class="grafana-section">
            <h2>Salle E210</h2>
            <div class="grafana-grid">
                <div class="grafana-panel">
                    <iframe src="http://<?php echo $_SERVER['SERVER_NAME']; ?>:3000/d-solo/7872e7b2-942f-466a-b1cb-acd58f68aec4/sae23-e28094-supervision-des-salles-iot?orgId=1&panelId=7&theme=dark" frameborder="0"></iframe>
                </div>
                <div class="grafana-panel">
                    <iframe src="http://<?php echo $_SERVER['SERVER_NAME']; ?>:3000/d-solo/7872e7b2-942f-466a-b1cb-acd58f68aec4/sae23-e28094-supervision-des-salles-iot?orgId=1&panelId=8&theme=dark" frameborder="0"></iframe>
                </div>
            </div>
        </div>
        
    </div>
</body>
</html>

