<?php
// Simple Router
// This router needs to be improved ...

// Include the helper file for handling requests

require_once __DIR__ . '/helpers/request.php';
require_once __DIR__ . '/helpers/core.php';
require './assets/dbconfig.php';



// Switch statement to handle different routes based on the path from the URL
switch ($url['path']) {
        // Case: Root path '/'
        case '/':
        case '/Base/':
        case '/Pokedex/Base/':
        // Check if the HTTP method is GET
        if ($method == 'GET') {
            // Include the 'views/index.php' file for the root path
            require 'controllers/HomeController.php';
            index();
        } else error(405);
        break;

        // Case: Handle '/pokemon' path
    case '/pokemon':
        // Check if the HTTP method is GET
        if ($method == 'GET') {
            // Check if the 'query' part of the URL is set, if not, call 'error()' function
            if (!isset($url['query'])) error();
            // Parse the query string of the URL and store the result in the 'result' array
            parse_str($url['query'], $result);
            // Sanitize the 'name' parameter using htmlspecialchars to prevent XSS attacks
            if (isset($result['name'])) $result['name'] = htmlspecialchars($result['name']);

            // Check if the 'name' parameter is set and not empty, if not, call 'error()' function
            if (!isset($result["name"]) || empty($result["name"])) error();

            // Include the 'views/pages/show.php' file to handle the display logic
            require 'controllers/HomeController.php';
            show();
            // Terminate the script to ensure no further code is executed
        } else error(405);
        break;
    
    case '/login':
        if ($method == 'GET') {
            require 'controllers/HomeController.php';
            login();
        } else error(405);
        break;
        
    case '/check_login':   
        if ($method == 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
            $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        
            try {
                $pdo = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASS);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
                $stmt = $pdo->prepare('SELECT username, password FROM user WHERE username = :username');
                $stmt->bindParam(':username', $username);
                $stmt->execute();
        
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
                //print_r($user);
                if ($user && password_verify($password, $user['password'])) {
                    session_start();
                    $_SESSION['user'] = $user;
                    header("Location: /");
                    exit();
                } else {
                    echo "Nom d'utilisateur ou mot de passe incorrect.";
                }
            } catch (PDOException $e) {
                echo 'Erreur : ' . $e->getMessage();
            }

        } else error(405);
        break;

        case '/logout':
            if ($method == 'POST') {
                session_start();
                unset($_SESSION['user']);
                session_destroy();
                header("Location: /login");
                exit();
            } else error(405);
        break;

        case '/register':
            if ($method == 'GET') {
                require 'controllers/HomeController.php';
                register();
            } else error(405);
            break;

        case '/addUser':
            session_start();
            if ($method == 'POST' && isset($_POST['username']) && isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['birthday']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password-verification'])) {
                $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
                $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING);
                $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);
                $birthday = filter_input(INPUT_POST, 'birthday', FILTER_SANITIZE_STRING);
                $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
                $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
                $password_verification = filter_input(INPUT_POST, 'password-verification', FILTER_SANITIZE_STRING);

                if ($password !== $password_verification) {
                    echo 'Passwords do not match';
                    break;
                }

                if (!$username || !$firstname || !$lastname || !$birthday || !$email || !$password || !$password_verification) {
                    echo 'Invalid input';
                    break;
                }

                $hashed_password = password_hash($password, PASSWORD_BCRYPT);

                try {
                    $pdo = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASS);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
                    // Vérifier si le nom d'utilisateur existe déjà
                    $stmt = $pdo->prepare('SELECT * FROM `user` WHERE username = :username');
                    $stmt->bindValue(':username', $username);
                    $stmt->execute();
        
                    if ($stmt->rowCount() > 0) {
                        echo 'Ce nom d\'utilisateur est déjà pris. Veuillez en choisir un autre.';
                        break;
                    }
        
                    $stmt = $pdo->prepare('INSERT INTO `user` (username, email, firstname, lastname, birthday, password) VALUES (:username, :email, :firstname, :lastname, :birthday, :password)');
                    $stmt->bindValue(':username', $username);
                    $stmt->bindValue(':email', $email);
                    $stmt->bindValue(':firstname', $firstname);
                    $stmt->bindValue(':lastname', $lastname);
                    $stmt->bindValue(':birthday', $birthday);
                    $stmt->bindValue(':password', $hashed_password);
        
                    if ($stmt->execute()) {
                        $userId = $pdo->lastInsertId();
        
                        $stmt = $pdo->prepare('SELECT * FROM `user` WHERE id = :id');
                        $stmt->bindValue(':id', $userId);
                        $stmt->execute();
        
                        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
                        if ($user) {
                            $_SESSION['user'] = $user;
                            header("Location: /");
                            exit();
                        } else {
                            echo 'Erreur lors de la récupération des informations utilisateur';
                        }
                    } else {
                        echo 'Erreur lors de l\'inscription';
                    }
                } catch (PDOException $e) {
                    echo 'Erreur lors de l\'inscription : ' . $e->getMessage();
                }
            } else {
                echo 'Invalid input';
            }
            break;

        case '/dashboard':
            if ($method == 'GET') {
                require 'controllers/HomeController.php';
                dashboard();
            } else error(405);
            break;

        // Default case: Handle all other paths by calling 'error()' function
    default:
        error();
        break;
}
