<?php
// Start session and connect to database
session_start();
require 'db.php';

// Check if user is logged in and has admin rights
if(!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Handle form submissions
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if($_POST['action'] == 'add_capteur') {
        $nom = $_POST['nom_capteur'];
        $type = $_POST['type'];
        $unite = $_POST['unite'];
        $salle = $_POST['nom_salle'];
        
        $stmt = mysqli_prepare($conn, "INSERT INTO capteur (nom_capteur, type, unité, nom_salle) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssss", $nom, $type, $unite, $salle);
        if(mysqli_stmt_execute($stmt)) {
            $msg = "<div class='alert alert-success'>Sensor added successfully.</div>";
        } else {
            $msg = "<div class='alert alert-error'>Error adding sensor.</div>";
        }
    } elseif($_POST['action'] == 'del_capteur') {
        $nom = $_POST['nom_capteur'];
        // Thanks to ON DELETE CASCADE in the DB schema, this will also delete the measures if the schema is updated.
        // For safety, we also explicitly delete the measures first in case the user hasn't updated the DB schema.
        $stmt_m = mysqli_prepare($conn, "DELETE FROM mesure WHERE nom_capteur = ?");
        mysqli_stmt_bind_param($stmt_m, "s", $nom);
        mysqli_stmt_execute($stmt_m);

        $stmt = mysqli_prepare($conn, "DELETE FROM capteur WHERE nom_capteur = ?");
        mysqli_stmt_bind_param($stmt, "s", $nom);
        mysqli_stmt_execute($stmt);
        $msg = "<div class='alert alert-success'>Sensor deleted successfully.</div>";
    } elseif($_POST['action'] == 'add_batiment') {
        $id = $_POST['id_bat'];
        $nom = $_POST['nom_bat'];
        $login = $_POST['login_bat'];
        $mdp = $_POST['mdp_bat'];
        
        $stmt = mysqli_prepare($conn, "INSERT INTO batiment (ID_bat, nom, login, mdp) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "isss", $id, $nom, $login, $mdp);
        if(mysqli_stmt_execute($stmt)) {
            $msg = "<div class='alert alert-success'>Building added successfully.</div>";
        } else {
            $msg = "<div class='alert alert-error'>Error adding building.</div>";
        }
    } elseif($_POST['action'] == 'add_salle') {
        $nom = $_POST['nom_salle'];
        $type = $_POST['type_salle'];
        $cap = $_POST['capacite'];
        $id_bat = $_POST['id_bat_salle'];
        
        $stmt = mysqli_prepare($conn, "INSERT INTO salle (nom_salle, type, capacité, ID) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssii", $nom, $type, $cap, $id_bat);
        if(mysqli_stmt_execute($stmt)) {
            $msg = "<div class='alert alert-success'>Room added successfully.</div>";
        } else {
            $msg = "<div class='alert alert-error'>Error adding room.</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>SAÉ 23 - Administration</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="consultation.php">Consultation</a></li>
            <li><a href="gestion.php">Gestionnaire</a></li>
            <li><a href="administration.php" style="color:var(--text-main)">Administration</a></li>
            <li><a href="projet.php">Gestion de projet</a></li>
            <li><a href="http://<?php echo $_SERVER['SERVER_NAME']; ?>:3000" target="_blank" style="color:#F05A28">Grafana (Live)</a></li>
        </ul>
        <div>
            <span style="color:var(--text-muted); margin-right: 15px;">Connecté: <?= htmlspecialchars($_SESSION['login']) ?></span>
            <a href="logout.php" style="color:var(--danger)">Déconnexion</a>
        </div>
    </nav>

    <div class="container">
        <h1>Panneau d'Administration</h1>
        <?php if(isset($msg)) echo $msg; ?>
        
        <div class="grid-cards">
            <!-- Capteurs -->
            <div class="card">
                <h2>Ajouter un capteur</h2>
                <form method="post">
                    <input type="hidden" name="action" value="add_capteur">
                    <div class="form-group"><label>Nom :</label><input type="text" name="nom_capteur" required></div>
                    <div class="form-group"><label>Type :</label><input type="text" name="type" required></div>
                    <div class="form-group"><label>Unité :</label><input type="text" name="unite" required></div>
                    <div class="form-group"><label>Salle :</label><select name="nom_salle" required>
                        <?php
                        $res = mysqli_query($conn, "SELECT nom_salle FROM salle");
                        while($row = mysqli_fetch_assoc($res)) { echo "<option value='" . htmlspecialchars($row['nom_salle']) . "'>" . htmlspecialchars($row['nom_salle']) . "</option>"; }
                        ?>
                    </select></div>
                    <button type="submit">Ajouter</button>
                </form>
            </div>

            <div class="card">
                <h2>Supprimer un capteur</h2>
                <form method="post">
                    <input type="hidden" name="action" value="del_capteur">
                    <div class="form-group"><label>Capteur :</label><select name="nom_capteur" required>
                        <?php
                        $res = mysqli_query($conn, "SELECT nom_capteur FROM capteur");
                        while($row = mysqli_fetch_assoc($res)) { echo "<option value='" . htmlspecialchars($row['nom_capteur']) . "'>" . htmlspecialchars($row['nom_capteur']) . "</option>"; }
                        ?>
                    </select></div>
                    <button type="submit" style="background:var(--danger)">Supprimer</button>
                </form>
            </div>

            <!-- Bâtiments -->
            <div class="card">
                <h2>Ajouter un Bâtiment</h2>
                <form method="post">
                    <input type="hidden" name="action" value="add_batiment">
                    <div class="form-group"><label>ID :</label><input type="number" name="id_bat" required></div>
                    <div class="form-group"><label>Nom :</label><input type="text" name="nom_bat" required></div>
                    <div class="form-group"><label>Login gestionnaire :</label><input type="text" name="login_bat" autocomplete="new-password" required></div>
                    <div class="form-group"><label>MDP gestionnaire :</label><input type="password" name="mdp_bat" autocomplete="new-password" required></div>
                    <button type="submit">Ajouter</button>
                </form>
            </div>

            <!-- Salles -->
            <div class="card">
                <h2>Ajouter une Salle</h2>
                <form method="post">
                    <input type="hidden" name="action" value="add_salle">
                    <div class="form-group"><label>Nom :</label><input type="text" name="nom_salle" required></div>
                    <div class="form-group"><label>Type :</label><input type="text" name="type_salle" required></div>
                    <div class="form-group"><label>Capacité :</label><input type="number" name="capacite" min="0" required></div>
                    <div class="form-group"><label>Bâtiment :</label><select name="id_bat_salle" required>
                        <?php
                        $res = mysqli_query($conn, "SELECT ID_bat, nom FROM batiment");
                        while($row = mysqli_fetch_assoc($res)) { echo "<option value='" . htmlspecialchars($row['ID_bat']) . "'>" . htmlspecialchars($row['nom']) . "</option>"; }
                        ?>
                    </select></div>
                    <button type="submit">Ajouter</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
