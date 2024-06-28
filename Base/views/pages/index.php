<?php
$title = "Home";
require_once __DIR__ . '../../partials/header.php';

$limit = 15;

$search = isset($_GET['search']) ? $_GET['search'] : '';
$min_hp = isset($_GET['min_hp']) ? intval($_GET['min_hp']) : 0;
$min_attack = isset($_GET['min_attack']) ? intval($_GET['min_attack']) : 0;
$min_defense = isset($_GET['min_defense']) ? intval($_GET['min_defense']) : 0;
$min_spatt = isset($_GET['min_spatt']) ? intval($_GET['min_spatt']) : 0;
$min_spdef = isset($_GET['min_spdef']) ? intval($_GET['min_spdef']) : 0;
$min_speed = isset($_GET['min_speed']) ? intval($_GET['min_speed']) : 0;
$type = isset($_GET['type']) ? $_GET['type'] : '';

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

try {
    $pdo = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $countQuery = "SELECT COUNT(DISTINCT pokemons.id) FROM pokemons 
                   LEFT JOIN pokemon_types ON pokemons.id = pokemon_types.pokemon_id 
                   LEFT JOIN types ON pokemon_types.type_id = types.id WHERE 1";
    $params = [];

    if ($search) {
        $countQuery .= " AND pokemons.name_english LIKE :search";
        $params[':search'] = '%' . $search . '%';
    }
    if ($min_hp > 0) {
        $countQuery .= " AND pokemons.hp >= :min_hp";
        $params[':min_hp'] = $min_hp;
    }
    if ($min_attack > 0) {
        $countQuery .= " AND pokemons.attack >= :min_attack";
        $params[':min_attack'] = $min_attack;
    }
    if ($min_defense > 0) {
        $countQuery .= " AND pokemons.defense >= :min_defense";
        $params[':min_defense'] = $min_defense;
    }
    if ($min_spatt > 0) {
        $countQuery .= " AND pokemons.special_attack >= :min_spatt";
        $params[':min_spatt'] = $min_spatt;
    }
    if ($min_spdef > 0) {
        $countQuery .= " AND pokemons.special_defense >= :min_spdef";
        $params[':min_spdef'] = $min_spdef;
    }
    if ($min_speed > 0) {
        $countQuery .= " AND pokemons.speed >= :min_speed";
        $params[':min_speed'] = $min_speed;
    }
    if ($type) {
        $countQuery .= " AND types.type = :type";
        $params[':type'] = $type;
    }

    $stmt = $pdo->prepare($countQuery);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();
    $pokemonCount = $stmt->fetchColumn();

    $total_pages = ceil($pokemonCount / $limit);

    $starting_limit = ($page - 1) * $limit;

    $dataQuery = "SELECT pokemons.*, GROUP_CONCAT(types.type SEPARATOR ', ') as types 
                  FROM pokemons 
                  LEFT JOIN pokemon_types ON pokemons.id = pokemon_types.pokemon_id 
                  LEFT JOIN types ON pokemon_types.type_id = types.id 
                  WHERE 1";
    
    if ($search) {
        $dataQuery .= " AND pokemons.name_english LIKE :search";
    }
    if ($min_hp > 0) {
        $dataQuery .= " AND pokemons.hp >= :min_hp";
    }
    if ($min_attack > 0) {
        $dataQuery .= " AND pokemons.attack >= :min_attack";
    }
    if ($min_defense > 0) {
        $dataQuery .= " AND pokemons.defense >= :min_defense";
    }
    if ($min_spatt > 0) {
        $dataQuery .= " AND pokemons.special_attack >= :min_spatt";
    }
    if ($min_spdef > 0) {
        $dataQuery .= " AND pokemons.special_defense >= :min_spdef";
    }
    if ($min_speed > 0) {
        $dataQuery .= " AND pokemons.speed >= :min_speed";
    }
    if ($type) {
        $dataQuery .= " AND types.type = :type";
    }
    $dataQuery .= " GROUP BY pokemons.id ORDER BY pokemons.id ASC LIMIT :start, :limit";

    $stmt = $pdo->prepare($dataQuery);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':start', $starting_limit, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

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
                    <label> Min. Hp : <input min="0" max="250" type="number" name="min_hp" value="<?php echo htmlspecialchars($min_hp); ?>"></label>
                    <label> Min. Attack : <input min="0" max="250" type="number" name="min_attack" value="<?php echo htmlspecialchars($min_attack); ?>"></label>
                    <label> Min. Defense : <input min="0" max="250" type="number" name="min_defense" value="<?php echo htmlspecialchars($min_defense); ?>"></label>
                    <label> Min. Sp. Att. : <input min="0" max="250" type="number" name="min_spatt" value="<?php echo htmlspecialchars($min_spatt); ?>"></label>
                    <label> Min. Sp. Def. : <input min="0" max="250" type="number" name="min_spdef" value="<?php echo htmlspecialchars($min_spdef); ?>"></label>
                    <label> Min. Speed: <input min="0" max="250" type="number" name="min_speed" value="<?php echo htmlspecialchars($min_speed); ?>"></label>
                    <label> Type : 
                        <select name="type">
                            <option value="" <?php if ($type === '') echo 'selected'; ?>>Any</option>
                            <option value="Grass" <?php if ($type === 'Grass') echo 'selected'; ?>>Grass</option>
                            <option value="Fire" <?php if ($type === 'Fire') echo 'selected'; ?>>Fire</option>
                            <option value="Water" <?php if ($type === 'Water') echo 'selected'; ?>>Water</option>
                            <option value="Electric" <?php if ($type === 'Electric') echo 'selected'; ?>>Electric</option>
                            <option value="Ground" <?php if ($type === 'Ground') echo 'selected'; ?>>Ground</option>
                            <option value="Rock" <?php if ($type === 'Rock') echo 'selected'; ?>>Rock</option>
                            <option value="Fairy" <?php if ($type === 'Fairy') echo 'selected'; ?>>Fairy</option>
                            <option value="Poison" <?php if ($type === 'Poison') echo 'selected'; ?>>Poison</option>
                            <option value="Fighting" <?php if ($type === 'Fighting') echo 'selected'; ?>>Fighting</option>
                            <option value="Psychic" <?php if ($type === 'Psychic') echo 'selected'; ?>>Psychic</option>
                            <option value="Bug" <?php if ($type === 'Bug') echo 'selected'; ?>>Bug</option>
                            <option value="Ghost" <?php if ($type === 'Ghost') echo 'selected'; ?>>Ghost</option>
                            <option value="Steel" <?php if ($type === 'Steel') echo 'selected'; ?>>Steel</option>
                            <option value="Dragon" <?php if ($type === 'Dragon') echo 'selected'; ?>>Dragon</option>
                            <option value="Dark" <?php if ($type === 'Dark') echo 'selected'; ?>>Dark</option>
                            <option value="Ice" <?php if ($type === 'Ice') echo 'selected'; ?>>Ice</option>
                            <option value="Flying" <?php if ($type === 'Flying') echo 'selected'; ?>>Flying</option>
                            <option value="Normal" <?php if ($type === 'Normal') echo 'selected'; ?>>Normal</option>
                        </select>
                    </label>
                </div>
                <button class ="searchbutton" type="submit">Search</button>
            </form>
        </div>
    </div>
</div>

<main>


    <div class="pagination">
        <?php 
        $url_params = http_build_query([
            'search' => $search,
            'min_hp' => $min_hp,
            'min_attack' => $min_attack,
            'min_defense' => $min_defense,
            'min_spatt' => $min_spatt,
            'min_spdef' => $min_spdef,
            'min_speed' => $min_speed,
            'type' => $type
        ]);

        ?>

    <?php if ($total_pages > 0): ?>
            <ul class="pages">
                <?php if ($page > 1): ?>
                    <li class="prev"><a href="/?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>">Prev</a></li>
                <?php endif; ?>

                <?php if ($page > 3): ?>
                    <li class="start"><a href="/?<?php echo http_build_query(array_merge($_GET, ['page' => 1])); ?>">1</a></li>
                    <li class="dots">...</li>
                <?php endif; ?>

                <?php if ($page - 2 > 0): ?>
                    <li class="page"><a href="/?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 2])); ?>"><?php echo $page - 2; ?></a></li>
                <?php endif; ?>
                <?php if ($page - 1 > 0): ?>
                    <li class="page"><a href="/?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>"><?php echo $page - 1; ?></a></li>
                <?php endif; ?>

                <li class="currentpage"><a href="/?<?php echo http_build_query(array_merge($_GET, ['page' => $page])); ?>"><?php echo $page; ?></a></li>

                <?php if ($page + 1 < $total_pages + 1): ?>
                    <li class="page"><a href="/?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>"><?php echo $page + 1; ?></a></li>
                <?php endif; ?>
                <?php if ($page + 2 < $total_pages + 1): ?>
                    <li class="page"><a href="/?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 2])); ?>"><?php echo $page + 2; ?></a></li>
                <?php endif; ?>

                <?php if ($page < $total_pages - 2): ?>
                    <li class="dots">...</li>
                    <li class="end"><a href="/?<?php echo http_build_query(array_merge($_GET, ['page' => $total_pages])); ?>"><?php echo $total_pages; ?></a></li>
                <?php endif; ?>

                <?php if ($page < $total_pages): ?>
                    <li class="next"><a href="/?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>">Next</a></li>
                <?php endif; ?>
            </ul>
        <?php endif; ?>

        <form action="/" method="get" class="goform">
            <input type="hidden" name="content" value="home" />
            <?php foreach ($_GET as $key => $value): if ($key != 'page' && $key != 'content'): ?>
                <input type="hidden" name="<?php echo htmlspecialchars($key); ?>" value="<?php echo htmlspecialchars($value); ?>" />
            <?php endif; endforeach; ?>
            <label><input placeholder="N°"type="number" name="page" min="1" max="<?php echo $total_pages; ?>"  class="input" required/></label>
            <input class="goto" type="submit" value="Go" />
        </form>

    </div>


    <div class="pokemon-list">
        <?php while ($pokemon = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="pokemoncard">

                <h2><a class="pokemonName" href="/pokemon?name=<?php echo urlencode(strtolower($pokemon['name_english'])); ?>"><?php echo htmlspecialchars($pokemon['name_english']); ?></a></h2>
                <div class="types">
                    <?php 
                    $types = explode(', ', $pokemon['types']); 
                    foreach ($types as $type): ?>
                        <span class="type <?php echo strtolower(htmlspecialchars($type)); ?>"><?php echo htmlspecialchars($type); ?></span>
                    <?php endforeach; ?>
                </div>
                <img src="./public/img/pokemon/<?php echo strtolower($pokemon['name_english']); ?>.png" alt="<?php echo htmlspecialchars($pokemon['name_english']); ?>">
            </div>
        <?php endwhile; ?>
    </div>
    
    <div class="pagination">
        <?php 
        $url_params = http_build_query([
            'search' => $search,
            'min_hp' => $min_hp,
            'min_attack' => $min_attack,
            'min_defense' => $min_defense,
            'min_spatt' => $min_spatt,
            'min_spdef' => $min_spdef,
            'min_speed' => $min_speed,
            'type' => $type
        ]);

        ?>

    <?php if ($total_pages > 0): ?>
            <ul class="pages">
                <?php if ($page > 1): ?>
                    <li class="prev"><a href="/?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>">Prev</a></li>
                <?php endif; ?>

                <?php if ($page > 3): ?>
                    <li class="start"><a href="/?<?php echo http_build_query(array_merge($_GET, ['page' => 1])); ?>">1</a></li>
                    <li class="dots">...</li>
                <?php endif; ?>

                <?php if ($page - 2 > 0): ?>
                    <li class="page"><a href="/?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 2])); ?>"><?php echo $page - 2; ?></a></li>
                <?php endif; ?>
                <?php if ($page - 1 > 0): ?>
                    <li class="page"><a href="/?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>"><?php echo $page - 1; ?></a></li>
                <?php endif; ?>

                <li class="currentpage"><a href="/?<?php echo http_build_query(array_merge($_GET, ['page' => $page])); ?>"><?php echo $page; ?></a></li>

                <?php if ($page + 1 < $total_pages + 1): ?>
                    <li class="page"><a href="/?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>"><?php echo $page + 1; ?></a></li>
                <?php endif; ?>
                <?php if ($page + 2 < $total_pages + 1): ?>
                    <li class="page"><a href="/?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 2])); ?>"><?php echo $page + 2; ?></a></li>
                <?php endif; ?>

                <?php if ($page < $total_pages - 2): ?>
                    <li class="dots">...</li>
                    <li class="end"><a href="/?<?php echo http_build_query(array_merge($_GET, ['page' => $total_pages])); ?>"><?php echo $total_pages; ?></a></li>
                <?php endif; ?>

                <?php if ($page < $total_pages): ?>
                    <li class="next"><a href="/?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>">Next</a></li>
                <?php endif; ?>
            </ul>
        <?php endif; ?>

        <form action="/" method="get" class="goform">
            <input type="hidden" name="content" value="home" />
            <?php foreach ($_GET as $key => $value): if ($key != 'page' && $key != 'content'): ?>
                <input type="hidden" name="<?php echo htmlspecialchars($key); ?>" value="<?php echo htmlspecialchars($value); ?>" />
            <?php endif; endforeach; ?>
            <label><input placeholder="N°"type="number" name="page" min="1" max="<?php echo $total_pages; ?>"  class="input" required/></label>
            <input class="goto" type="submit" value="Go" />
        </form>

    </div>
</main>

<?php require_once __DIR__ . '../../partials/footer.php'; ?>




