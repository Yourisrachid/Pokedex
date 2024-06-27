<?php

$title = "Home";
require_once __DIR__ . '../../partials/header.php';

$limit = 15;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$min_hp = isset($_GET['min_hp']) ? intval($_GET['min_hp']) : 0;
$min_attack = isset($_GET['min_attack']) ? intval($_GET['min_attack']) : 0;
$min_defense = isset($_GET['min_defense']) ? intval($_GET['min_defense']) : 0;
$min_spatt = isset($_GET['min_spatt']) ? intval($_GET['min_spatt']) : 0;
$min_spdef = isset($_GET['min_spdef']) ? intval($_GET['min_spdef']) : 0;
$min_speed = isset($_GET['min_speed']) ? intval($_GET['min_speed']) : 0;
$type = isset($_GET['type']) ? trim($_GET['type']) : '';

try {
    $pdo = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT Count(*) FROM Pokedex WHERE 1";
    if ($search) {
        $query .= " AND `name.english` LIKE :search";
    }
    if ($min_hp > 0) {
        $query .= " AND `base.HP` >= :min_hp";
    }
    if ($min_attack > 0) {
        $query .= " AND `base.Attack` >= :min_attack";
    }
    if ($min_defense > 0) {
        $query .= " AND `base.Defense` >= :min_defense";
    }
    if ($min_spatt > 0) {
        $query .= " AND `base.Sp. Attack` >= :min_spatt";
    }
    if ($min_spdef > 0) {
        $query .= " AND `base.Sp. Defense` >= :min_spdef";
    }
    if ($min_speed > 0) {
        $query .= " AND `base.Speed` >= :min_speed";
    }
    if ($type) {
        $query .= " AND JSON_CONTAINS(`type`, :type)";
    }
    $stmt = $pdo->prepare($query);
    if ($search) {
        $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
    }
    if ($min_hp > 0) {
        $stmt->bindValue(':min_hp', $min_hp, PDO::PARAM_INT);
    }
    if ($min_attack > 0) {
        $stmt->bindValue(':min_attack', $min_attack, PDO::PARAM_INT);
    }
    if ($min_defense > 0) {
        $stmt->bindValue(':min_defense', $min_defense, PDO::PARAM_INT);
    }
    if ($min_spatt > 0) {
        $stmt->bindValue(':min_spatt', $min_spatt, PDO::PARAM_INT);
    }
    if ($min_spdef > 0) {
        $stmt->bindValue(':min_spdef', $min_spdef, PDO::PARAM_INT);
    }
    if ($min_speed > 0) {
        $stmt->bindValue(':min_speed', $min_speed, PDO::PARAM_INT);
    }
    if ($type) {
        $stmt->bindValue(':type', json_encode([$type]), PDO::PARAM_STR);
    }
    $stmt->execute();
    $pokemonList = $stmt->fetchColumn();

    $total_pages = ceil($pokemonList/$limit);

    if (!isset($_GET['page'])) {
        $page = 1;
    } else{
        $page = $_GET['page'];
    }

    $starting_limit = ($page-1)*$limit;

    $query = "SELECT * FROM Pokedex WHERE 1";
    if ($search) {
        $query .= " AND `name.english` LIKE :search";
    }
    if ($min_hp > 0) {
        $query .= " AND `base.HP` >= :min_hp";
    }
    if ($min_attack > 0) {
        $query .= " AND `base.Attack` >= :min_attack";
    }
    if ($min_defense > 0) {
        $query .= " AND `base.Defense` >= :min_defense";
    }
    if ($min_spatt > 0) {
        $query .= " AND `base.Sp. Attack` >= :min_spatt";
    }
    if ($min_spdef > 0) {
        $query .= " AND `base.Sp. Defense` >= :min_spdef";
        
    }
    if ($min_speed > 0) {
        $query .= " AND `base.Speed` >= :min_speed";
    }
    if ($type) {
        $query .= " AND JSON_CONTAINS(`type`, :type)";
    }
    $query .= " ORDER BY id ASC LIMIT :start, :limit";
    $r = $pdo->prepare($query);
    if ($search) {
        $r->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
    }
    if ($min_hp > 0) {
        $r->bindValue(':min_hp', $min_hp, PDO::PARAM_INT);
    }
    if ($min_attack > 0) {
        $r->bindValue(':min_attack', $min_attack, PDO::PARAM_INT);
    }
    if ($min_defense > 0) {
        $r->bindValue(':min_defense', $min_defense, PDO::PARAM_INT);
    }
    if ($min_spatt > 0) {
        $r->bindValue(':min_spatt', $min_spatt, PDO::PARAM_INT);
    }
    if ($min_spdef > 0) {
        $r->bindValue(':min_spdef', $min_spdef, PDO::PARAM_INT);
    }
    if ($min_speed > 0) {
        $r->bindValue(':min_speed', $min_speed, PDO::PARAM_INT);
    }
    if ($type) {
        $r->bindValue(':type', json_encode([$type]), PDO::PARAM_STR);
    }
    $r->bindParam(':start', $starting_limit, PDO::PARAM_INT);
    $r->bindParam(':limit', $limit, PDO::PARAM_INT);
    $r->execute();

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

?>

<div>
    <div class="search-container">
        <div id="search-block" class="search-block">
            <form action="/" method="get">
                <input type="text" name="search" placeholder="Search Pokémon" value="<?php echo htmlspecialchars($search); ?>">
                <button type="button" id="toggle-advanced-search">Advanced search options</button>
                <div id="advanced-search" class="advanced-search hidden">
                    <label>Minimum Hp: <input type="number" name="min_hp" value="<?php echo htmlspecialchars($min_hp); ?>"></label>
                    <label>Minimum Attack: <input type="number" name="min_attack" value="<?php echo htmlspecialchars($min_attack); ?>"></label>
                    <label>Minimum Defense: <input type="number" name="min_defense" value="<?php echo htmlspecialchars($min_defense); ?>"></label>
                    <label>Minimum Sp. Att.: <input type="number" name="min_spatt" value="<?php echo htmlspecialchars($min_spatt); ?>"></label>
                    <label>Minimum Sp. Def.: <input type="number" name="min_spdef" value="<?php echo htmlspecialchars($min_spdef); ?>"></label>
                    <label>Minimum Speed: <input type="number" name="min_speed" value="<?php echo htmlspecialchars($min_speed); ?>"></label>
                    <label>Type: 
                        <select name="type">
                            <option value="">Any</option>
                            <option value="Grass" <?php if ($type === 'Grass') echo 'selected'; ?>>Grass</option>
                            <option value="Fire" <?php if ($type === 'Fire') echo 'selected'; ?>>Fire</option>
                            <option value="Water" <?php if ($type === 'Water') echo 'selected'; ?>>Water</option>
                            <option value="Electric" <?php if ($type === 'Electric') echo 'selected'; ?>>Electric</option>
                            <option value="Poison" <?php if ($type === 'Poison') echo 'selected'; ?>>Poison</option>
                            <option value="Ice" <?php if ($type === 'Ice') echo 'selected'; ?>>Ice</option>
                            <option value="Fighting" <?php if ($type === 'Fighting') echo 'selected'; ?>>Fighting</option>
                            <option value="Ground" <?php if ($type === 'Ground') echo 'selected'; ?>>Ground</option>
                            <option value="Flying" <?php if ($type === 'Flying') echo 'selected'; ?>>Flying</option>
                            <option value="Psychic" <?php if ($type === 'Psychic') echo 'selected'; ?>>Psychic</option>
                            <option value="Bug" <?php if ($type === 'Bug') echo 'selected'; ?>>Bug</option>
                            <option value="Rock" <?php if ($type === 'Rock') echo 'selected'; ?>>Rock</option>
                            <option value="Ghost" <?php if ($type === 'Ghost') echo 'selected'; ?>>Ghost</option>
                            <option value="Dragon" <?php if ($type === 'Dragon') echo 'selected'; ?>>Dragon</option>
                            <option value="Dark" <?php if ($type === 'Dark') echo 'selected'; ?>>Dark</option>
                            <option value="Steel" <?php if ($type === 'Steel') echo 'selected'; ?>>Steel</option>
                            <option value="Fairy" <?php if ($type === 'Fairy') echo 'selected'; ?>>Fairy</option>
                            <option value="Normal" <?php if ($type === 'Normal') echo 'selected'; ?>>Normal</option>
                        </select>
                    </label>
                </div>
                <input type="submit" value="Search">
            </form>
        </div>
    </div>
</div>

<main>

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
            <li class="prev"><a href="/?page=<?php echo $page-1 ?>&search=<?php echo urlencode($search); ?>&min_speed=<?php echo urlencode($min_speed); ?>&type=<?php echo urlencode($type); ?>">Prev</a></li>
            <?php endif; ?>

            <?php if ($page > 3): ?>
            <li class="start"><a href="/?page=1&search=<?php echo urlencode($search); ?>&min_speed=<?php echo urlencode($min_speed); ?>&type=<?php echo urlencode($type); ?>">1</a></li>
            <li class="dots">...</li>
            <?php endif; ?>

            <?php if ($page-2 > 0): ?><li class="page"><a href="/?page=<?php echo $page-2 ?>&search=<?php echo urlencode($search); ?>&min_speed=<?php echo urlencode($min_speed); ?>&type=<?php echo urlencode($type); ?>"><?php echo $page-2 ?></a></li><?php endif; ?>
            <?php if ($page-1 > 0): ?><li class="page"><a href="/?page=<?php echo $page-1 ?>&search=<?php echo urlencode($search); ?>&min_speed=<?php echo urlencode($min_speed); ?>&type=<?php echo urlencode($type); ?>"><?php echo $page-1 ?></a></li><?php endif; ?>

            <li class="currentpage"><a href="/?page=<?php echo $page ?>&search=<?php echo urlencode($search); ?>&min_speed=<?php echo urlencode($min_speed); ?>&type=<?php echo urlencode($type); ?>"><?php echo $page ?></a></li>

            <?php if ($page+1 < $total_pages+1): ?><li class="page"><a href="/?page=<?php echo $page+1 ?>&search=<?php echo urlencode($search); ?>&min_speed=<?php echo urlencode($min_speed); ?>&type=<?php echo urlencode($type); ?>"><?php echo $page+1 ?></a></li><?php endif; ?>
            <?php if ($page+2 < $total_pages+1): ?><li class="page"><a href="/?page=<?php echo $page+2 ?>&search=<?php echo urlencode($search); ?>&min_speed=<?php echo urlencode($min_speed); ?>&type=<?php echo urlencode($type); ?>"><?php echo $page+2 ?></a></li><?php endif; ?>

            <?php if ($page < $total_pages-2): ?>
            <li class="dots">...</li>
            <li class="end"><a href="/?page=<?php echo $total_pages ?>&search=<?php echo urlencode($search); ?>&min_speed=<?php echo urlencode($min_speed); ?>&type=<?php echo urlencode($type); ?>"><?php echo $total_pages ?></a></li>
            <?php endif; ?>

            <?php if ($page < $total_pages): ?>
            <li class="next"><a href="/?page=<?php echo $page+1 ?>&search=<?php echo urlencode($search); ?>&min_speed=<?php echo urlencode($min_speed); ?>&type=<?php echo urlencode($type); ?>">Next</a></li>
            <?php endif; ?>
        </ul>
    <?php endif; ?>

    <form action="/" method="get">
        <input type="hidden" name="content" value="home" />
        <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>" />
        <input type="hidden" name="min_speed" value="<?php echo htmlspecialchars($min_speed); ?>" />
        <input type="hidden" name="type" value="<?php echo htmlspecialchars($type); ?>" />
        <label>Go to page: <input type="number" name="page" /></label>
        <input class="goto" type="submit" value="Go" />
    </form>
</main>

<?php
require_once __DIR__ . '../../partials/footer.php';
?>
