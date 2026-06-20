# Fonctionnement du Projet SAÉ 23 - Plateforme IoT

Ce document présente en détail l'architecture, le fonctionnement et les scripts réalisés pour la SAÉ 23. L'objectif de ce projet est de collecter, stocker et afficher les données des capteurs de l'IUT sur une plateforme web sécurisée.

---

## 1. La Base de Données (MySQL)

Le fichier `sae23.sql` contient la structure complète et finale de notre base de données relationnelle. Le schéma repose sur plusieurs tables interconnectées :

1. **`batiment`** : Stocke les informations des bâtiments gérés, ainsi que les identifiants de connexion (`login` / `mdp`) de leur gestionnaire respectif.
2. **`salle`** : Liée à un bâtiment via une clé étrangère. Elle recense les salles équipées (ex: E208, E210).
3. **`capteur`** : Liée à une salle. Elle liste tous les capteurs disponibles (nom, type, unité). Si on ajoute un capteur ici, il est instantanément pris en compte par tout le reste du système.
4. **`mesure`** : C'est la table centrale qui stocke l'historique complet. Chaque nouvelle valeur captée par le MQTT vient s'ajouter ici avec un `ID_mesure` auto-incrémenté, une `date`, un `horaire` et sa `valeur`.
5. **`Administration`** : Une table simple contenant le `login` et le `mdp` de l'Administrateur global du système.

---

## 2. La Chaîne de Collecte (Script Bash `mqtt_to_mysql.sh`)

Pour rapatrier les données de l'IUT vers notre base de données locale, nous avons conçu un script d'automatisation en Bash.

**Fonctionnement détaillé du script :**
- **Étape 1 - Lecture du périmètre :** Le script interroge la table `capteur` de MySQL pour récupérer la liste de tous les capteurs qui existent dans notre système.
- **Étape 2 - Reconstruction des Topics :** Pour chaque capteur trouvé (ex: Température dans la salle E208), le script recompose l'adresse du topic IUT correspondant (ex: `sandbox/student/iut/bate/etage2/E208/temperature`).
- **Étape 3 - Écoute MQTT :** Le script lance une commande `mosquitto_sub` paramétrée pour n'écouter qu'un seul message (`-C 1`) sur ce topic précis.
- **Étape 4 - Extraction JSON :** La valeur renvoyée par le bus MQTT est sous forme JSON (`{"value": 24.5}`). Le programme `jq` est utilisé pour "nettoyer" cette chaîne et extraire uniquement la valeur numérique.
- **Étape 5 - Insertion SQL :** Une fois la valeur extraite, le script génère l'heure exacte et envoie une commande `INSERT INTO mesure` vers la base MySQL.

**Automatisation :** Ce script tourne tout seul en tâche de fond. Nous l'avons déclaré dans le `crontab` de la machine virtuelle Linux avec la syntaxe `* * * * *`, ce qui signifie qu'il s'exécute silencieusement toutes les 60 secondes.

---

## 3. Le Site Web Dynamique (PHP)

L'interface web permet d'interagir avec les données. Elle a été développée en **PHP procédural** (via l'API `mysqli_`) sans utiliser de langage orienté objet, et avec un CSS volontairement simple, propre et clair.

### 3.1. Structure générale
* **`db.php`** : Ce fichier initialise la connexion à la base de données. Il est inclus au début de chaque autre page (`require 'db.php'`) pour factoriser le code.
* **`style/style.css`** : Gère l'apparence visuelle (menus, tableaux, boutons). 
* **`projet.php`** : Une page statique présentant notre démarche projet (Gantt, synthèse) avec des images chargées depuis le dossier `/images`.

### 3.2. Le système d'authentification et de "Sessions" (`login.php`)
Le cahier des charges impose une séparation stricte des droits. Cela est géré par la mécanique des Sessions PHP (`$_SESSION`).
- Sur la page de connexion, lorsque l'utilisateur entre ses identifiants, le code va vérifier s'il existe dans la table `Administration`. Si oui, il reçoit le rôle `admin` et est redirigé vers l'Administration.
- S'il n'est pas Admin, le code cherche dans la table `batiment`. S'il y est trouvé, il reçoit le rôle `gestionnaire` et on enregistre en session l'ID de son bâtiment. Il est redirigé vers sa page de Gestion.

### 3.3. Les pages réservées
* **La page de l'Administrateur (`administration.php`) :**
  - **Sécurité :** Si un visiteur n'a pas le rôle `admin` en session, le code bloque l'accès et le renvoie à l'accueil.
  - **Fonctionnement :** Elle contient des formulaires HTML liés au CRUD (Create, Read, Update, Delete). Lorsqu'un formulaire est soumis (ex: Ajouter un capteur), le PHP récupère les données via la méthode `$_POST`, prépare une requête SQL `INSERT INTO`, l'exécute, et le nouveau capteur est immédiatement créé en BDD.

* **La page du Gestionnaire (`gestion.php`) :**
  - **Sécurité :** Si le visiteur n'a pas le rôle `gestionnaire`, accès bloqué. S'il l'a, le PHP utilise l'ID de son bâtiment (mémorisé lors du login) pour personnaliser la page.
  - **Fonctionnement :** La page ne récupère **que** les capteurs liés au bâtiment du gestionnaire. Elle utilise des requêtes SQL avancées contenant les fonctions `MIN()`, `MAX()` et `AVG()` pour calculer en temps réel les statistiques des capteurs. Elle affiche aussi l'historique des 10 dernières valeurs grâce à la clause `ORDER BY date DESC, horaire DESC LIMIT 10`.

### 3.4. La page publique (`consultation.php`)
Cette page est accessible à tous. Elle interroge la table `capteur` et va chercher la valeur la plus récente dans la table `mesure` grâce à des sous-requêtes SQL (`SELECT ... (SELECT valeur FROM mesure ORDER BY date DESC LIMIT 1)`).
Elle inclut également une balise HTML `<meta http-equiv="refresh" content="60">`, forçant le navigateur du visiteur à se réactualiser tout seul toutes les minutes pour voir les nouvelles données arriver en direct.
