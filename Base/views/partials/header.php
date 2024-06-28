<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?> - Pokedex</title>
    <link href="../../assets/css/style.css" type="text/css" rel="stylesheet" >
    <script type="module" async src="../../assets/js/main.js"></script>

</head>

<?php 
session_start();
require_once __DIR__ .  '/navbar.php';

?>

<body>