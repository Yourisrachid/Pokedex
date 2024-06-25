<?php

$title = "Home";
require_once __DIR__ . '/../../partials/header.php';
if (isset($_SESSION['user'])) {
    print_r($_SESSION['user']); 
} 

?>

<main>
    <form action="/check_login" method="post">
      <div>
        <label for="username">Identifiant</label>
        <input type="text" name="username">
      </div>
      <div>
        <label for="password">Mot de passe </label>
        <input type="password" name="password">
      </div>
      <div>
        <button type="submit" name="button">Se connecter</button>
      </div>
    </form>
</main>

<?php
require_once __DIR__ . '/../../partials/footer.php';
?>
