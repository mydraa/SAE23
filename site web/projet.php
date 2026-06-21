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
            <li><a href="projet.php" style="color:var(--text-main)">Gestion de projet</a></li>
            <li><a href="grafana.php" style="color:#F05A28">Grafana (Live)</a></li>
        </ul>
        <?php if(isset($_SESSION['login'])): ?>
            <div>
                <span style="color:#666; margin-right: 15px;">Connecté: <?= htmlspecialchars($_SESSION['login']) ?></span>
                <a href="logout.php" style="color:red">Déconnexion</a>
            </div>
        <?php else: ?>
            <div>
                <a href="login.php">Connexion</a>
            </div>
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
            <img src="images/drive.png" alt="Google Drive Collaboratif" style="max-width:100%; border:1px solid #ccc;">
        </div>
        <div class="card">
            <h2>Synthèse personnelle</h2>
            <ul>
                <li><strong>Alexandre M :</strong> S'est principalement occupé de la mise en place de l'infrastructure Docker (déploiement de Node-RED, InfluxDB et Grafana). Il a également participé à l'élaboration du code de récupération des données et a assuré la gestion continue des versions sur GitHub.</li>
                <li><strong>Lucas M :</strong> A pris en charge la création et la configuration de la machine virtuelle. Il a résolu plusieurs bugs complexes sur Node-RED, créé la base de données, configuré les tableaux de bord Grafana et activement contribué aux dépôts sur GitHub.</li>
                <li><strong>Morgan G :</strong> S'est concentré sur la partie développement avec la conception intégrale du site web dynamique en PHP, et a mis en place l'automatisation de la collecte des données via les tâches planifiées (<code>crontab</code>).</li>
                <li><strong>Alexandre C :</strong> A participé à la création de la base de données, au débogage compliqué du flux Node-RED, et a posé les bases du code source pour la récupération des informations MQTT.</li>
            </ul>
        </div>
        <div class="card">
            <h2>Problèmes rencontrés & Solutions proposées</h2>
            <p>L'un des défis majeurs de ce projet a été la prise en main et la configuration de Node-RED. Nous avons rencontré pas mal de difficultés pour extraire, filtrer et traiter correctement les trames de données brutes provenant du serveur MQTT de l'IUT. Après de nombreux tests et des séances de débogage en équipe, nous avons fini par trouver la bonne logique pour isoler les valeurs utiles et les envoyer proprement vers nos autres services.</p>
        </div>
        <div class="card">
            <h2>Conclusion</h2>
            <p>En conclusion, cette SAÉ 23 a été très formatrice. Elle nous a permis de relier différentes technologies vues en cours (Réseau, Base de données, Développement Web et Linux) pour construire un vrai système IoT de A à Z. Malgré quelques obstacles techniques assez prenants, la bonne répartition du travail en équipe nous a permis de remplir les exigences du cahier des charges et d'aboutir à une plateforme fonctionnelle.</p>
        </div>
    </div>
</body>
</html>

