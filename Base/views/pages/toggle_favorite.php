<?php
session_start();
require_once __DIR__ . '/../../assets/dbconfig.php';

if (!isset($_SESSION['user'])) {
    header('Location: /../../partials/loginForm.php');
    exit();
}

$user_id = $_SESSION['user'];
$pokemon_id = intval($_POST['pokemon_id']);

try {
    $pdo = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $stmt = $pdo->prepare("SELECT favorite FROM user WHERE id = :user_id");
    $stmt->execute([':user_id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $favorites = json_decode($user['favorite'], true) ?? [];
        if (in_array($pokemon_id, $favorites)) {

            $favorites = array_diff($favorites, [$pokemon_id]);
        } else {

            $favorites[] = $pokemon_id;
        }
        $favorites = array_values($favorites);

        $stmt = $pdo->prepare("UPDATE user SET favorite = :favorite WHERE id = :user_id");
        $stmt->execute([':favorite' => json_encode($favorites), ':user_id' => $user_id]);
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

header('Location: /');
exit();