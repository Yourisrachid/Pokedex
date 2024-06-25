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
            $username = $_POST['username'];
            $password = $_POST['password'];
        
            try {
                $pdo = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASS);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
                $stmt = $pdo->prepare('SELECT * FROM user WHERE username = :username');
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


        // Default case: Handle all other paths by calling 'error()' function
    default:
        error();
        break;
}
