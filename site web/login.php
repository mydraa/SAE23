<?php
session_start();
require 'db.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $mdp = $_POST['mdp'];
    
    // Check Admin
    $stmt = mysqli_prepare($conn, "SELECT * FROM Administration WHERE login = ? AND mdp = ?");
    mysqli_stmt_bind_param($stmt, "ss", $login, $mdp);
    mysqli_stmt_execute($stmt);
    if(mysqli_num_rows(mysqli_stmt_get_result($stmt)) > 0) {
        $_SESSION['login'] = $login;
        $_SESSION['role'] = 'admin';
        header("Location: administration.php");
        exit;
    }
    
    // Check Gestionnaire
    $stmt = mysqli_prepare($conn, "SELECT * FROM batiment WHERE login = ? AND mdp = ?");
    mysqli_stmt_bind_param($stmt, "ss", $login, $mdp);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if(mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $_SESSION['login'] = $login;
        $_SESSION['role'] = 'gestionnaire';
        $_SESSION['id_bat'] = $row['ID_bat'];
        header("Location: gestion.php");
        exit;
    }
    
    $error = "Identifiants incorrects.";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>SAÉ 23 - Connexion</title>
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
            <li><a href="grafana.php" style="color:#F05A28">Grafana (Live)</a></li>
        </ul>
    </nav>
    <div class="container" style="max-width: 400px; margin-top: 10vh;">
        <div class="card">
            <h2>Connexion</h2>
            <?php if(isset($error)) echo "<div class='alert alert-error'>$error</div>"; ?>
            <form method="post">
                <div class="form-group">
                    <label>Identifiant</label>
                    <input type="text" name="login" required>
                </div>
                <div class="form-group">
                    <label>Mot de passe</label>
                    <input type="password" name="mdp" required>
                </div>
                <button type="submit">Se connecter</button>
            </form>
        </div>
    </div>
</body>
</html>

