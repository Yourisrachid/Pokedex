<?php
$title = "Home";
require_once __DIR__ . '../../partials/header.php';
?>

<?php

require './assets/dbconfig.php';

$limit = 15;

try {
    $pdo = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT Count(*) FROM Pokedex");
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


    <?php if ($total_pages > 0): ?>
        <ul class="pagination">
            <?php if ($page > 1): ?>
            <li class="prev"><a href="/?page=<?php echo $page-1 ?>">Prev</a></li>
            <?php endif; ?>

            <?php if ($page > 3): ?>
            <li class="start"><a href="/?page=1">1</a></li>
            <li class="dots">...</li>
            <?php endif; ?>

            <?php if ($page-2 > 0): ?><li class="page"><a href="/?page=<?php echo $page-2 ?>"><?php echo $page-2 ?></a></li><?php endif; ?>
            <?php if ($page-1 > 0): ?><li class="page"><a href="/?page=<?php echo $page-1 ?>"><?php echo $page-1 ?></a></li><?php endif; ?>

            <li class="currentpage"><a href="/?page=<?php echo $page ?>"><?php echo $page ?></a></li>

            <?php if ($page+1 < $total_pages+1): ?><li class="page"><a href="/?page=<?php echo $page+1 ?>"><?php echo $page+1 ?></a></li><?php endif; ?>
            <?php if ($page+2 < $total_pages+1): ?><li class="page"><a href="/?page=<?php echo $page+2 ?>"><?php echo $page+2 ?></a></li><?php endif; ?>

            <?php if ($page < $total_pages-2): ?>
            <li class="dots">...</li>
            <li class="end"><a href="/?page=<?php echo $total_pages ?>"><?php echo $total_pages ?></a></li>
            <?php endif; ?>

            <?php if ($page < $total_pages): ?>
            <li class="next"><a href="/?page=<?php echo $page+1 ?>">Next</a></li>
            <?php endif; ?>
        </ul>
    <?php endif; ?>

    <form action="/?page=" method="get">
        <input type="hidden" name="content" value="home" />
        <label>Go to page : <input type="number" name="page" /></label>
        <input class="goto" type="submit" value="Go" />
    </form>

</main>


<?php
require_once __DIR__ . '../../partials/footer.php';
?>