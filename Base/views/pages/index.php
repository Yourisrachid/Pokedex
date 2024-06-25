<?php
$title = "Home";
require_once __DIR__ . '../../partials/header.php';

?>

<?php

require './assets/dbconfig.php';

try {
    $pdo = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT * FROM Pokedex");
    $pokemonList = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

<main>
    <h1>Pokedex - Homepage</h1>
    <p>Hello <strong><?php echo $user['name'] ?></strong></p>
    
    <div class="pokemon-list">
        <?php foreach ($pokemonList as $pokemon): ?>
            <div class="pokemoncard">
            <h2><a href="/pokemon?name=<?php echo urlencode(strtolower($pokemon['name.english'])); ?>"><?php echo htmlspecialchars($pokemon['name.english']); ?></a></h2>
                <p>Type: <?php 
                    $types = json_decode($pokemon['type'], true);
                    echo htmlspecialchars(implode(', ', $types));
                ?></p>
                <img src="./public/img/pokemon/<?php echo strtolower($pokemon['name.english']); ?>.png" alt="<?php echo htmlspecialchars($pokemon['name.english']); ?>">
            </div>
        <?php endforeach; ?>
    </div>

</main>


<?php
require_once __DIR__ . '../../partials/footer.php';
?>