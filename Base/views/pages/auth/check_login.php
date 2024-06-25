<?php

require '../../assets/dbconfig.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $pdo = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASS);
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $bdd->prepare('SELECT * FROM user WHERE username = :username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        print_r($user);
        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user'] = $user;
            header("Location: ../php-training-mysql/read.php");
            exit();
        } else {
            echo "Nom d'utilisateur ou mot de passe incorrect.";
        }
    } catch (PDOException $e) {
        echo 'Erreur : ' . $e->getMessage();
    }
} else {
    echo "Veuillez remplir tous les champs.";
}