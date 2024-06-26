<?php
$title = "Home";
require_once __DIR__ . '../../partials/header.php';
?>

<?php

require './assets/dbconfig.php';

$limit = 10;

try {
    $pdo = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT * FROM Pokedex");
    $pokemonList = $stmt->fetchColumn();


    $total_pages = ceil($pokemonList/$limit);

    if (!isset($_GET['page'])) {
        $page = 1;
    } else{
        $page = $_GET['page'];
    }

    $starting_limit = ($page-1)*$limit;

    $r = $pdo->prepare("SELECT * FROM Pokedex ORDER BY id ASC LIMIT :start, :limit");
    $r->bindParam(':start', $starting_limit, PDO::PARAM_INT);
    $r->bindParam(':limit', $limit, PDO::PARAM_INT);
    $r->execute();
    

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

?>

<main>
    <h1>Pokedex - Homepage</h1>
    <p>Hello <strong><?php echo $user['name'] ?></strong></p>
    
    <div class="pokemon-list">
        <?php while ($pokemon = $r->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="pokemoncard">
            <h2><a class="pokemonName" href="/pokemon?name=<?php echo urlencode(strtolower($pokemon['name.english'])); ?>"><?php echo htmlspecialchars($pokemon['name.english']); ?></a></h2>
                <div class="types">
                    <?php 
                        $types = json_decode($pokemon['type'], true);
                        foreach ($types as $type) {
                            echo '<span class="type ' . strtolower($type) . '">' . htmlspecialchars($type) . '</span>';
                        }
                    ?>
                </div>
                <img src="./public/img/pokemon/<?php echo strtolower($pokemon['name.english']); ?>.png" alt="<?php echo htmlspecialchars($pokemon['name.english']); ?>">
            </div>
        <?php endwhile; ?>
    </div>



    <?php for ($page=1; $page <= $total_pages ; $page++):?>

    <a href='<?php echo "?page=$page"; ?>' class="links"><?php  echo $page; ?>
    </a>

    <?php endfor; ?>

</main>


<?php
require_once __DIR__ . '../../partials/footer.php';
?>