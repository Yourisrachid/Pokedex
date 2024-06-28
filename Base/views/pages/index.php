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

    // Building the count query
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

    // Building the data query
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
                    <label>Minimum Hp: <input type="number" name="min_hp" value="<?php echo htmlspecialchars($min_hp); ?>"></label>
                    <label>Minimum Attack: <input type="number" name="min_attack" value="<?php echo htmlspecialchars($min_attack); ?>"></label>
                    <label>Minimum Defense: <input type="number" name="min_defense" value="<?php echo htmlspecialchars($min_defense); ?>"></label>
                    <label>Minimum Sp. Att.: <input type="number" name="min_spatt" value="<?php echo htmlspecialchars($min_spatt); ?>"></label>
                    <label>Minimum Sp. Def.: <input type="number" name="min_spdef" value="<?php echo htmlspecialchars($min_spdef); ?>"></label>
                    <label>Minimum Speed: <input type="number" name="min_speed" value="<?php echo htmlspecialchars($min_speed); ?>"></label>
                    <label>Type: 
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
                <button type="submit">Search</button>
            </form>
        </div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Types</th>
                    <th>HP</th>
                    <th>Attack</th>
                    <th>Defense</th>
                    <th>Sp. Att.</th>
                    <th>Sp. Def.</th>
                    <th>Speed</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>
                        <td>" . htmlspecialchars($result['name_english']) . "</td>
                        <td>" . htmlspecialchars($result['types']) . "</td>
                        <td>" . htmlspecialchars($result['hp']) . "</td>
                        <td>" . htmlspecialchars($result['attack']) . "</td>
                        <td>" . htmlspecialchars($result['defense']) . "</td>
                        <td>" . htmlspecialchars($result['special_attack']) . "</td>
                        <td>" . htmlspecialchars($result['special_defense']) . "</td>
                        <td>" . htmlspecialchars($result['speed']) . "</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
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

        for ($page = 1; $page <= $total_pages; $page++) {
            echo '<a href="?page=' . $page . '&' . $url_params . '">' . $page . '</a>';
        }
        ?>
    </div>
</div>


<?php require_once __DIR__ . '../../partials/footer.php'; ?>






<?php // ------------------------------------------------------------------------------------------------------ ?>


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

    // Building the count query
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

    // Building the data query
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
                    <label>Minimum Hp: <input type="number" name="min_hp" value="<?php echo htmlspecialchars($min_hp); ?>"></label>
                    <label>Minimum Attack: <input type="number" name="min_attack" value="<?php echo htmlspecialchars($min_attack); ?>"></label>
                    <label>Minimum Defense: <input type="number" name="min_defense" value="<?php echo htmlspecialchars($min_defense); ?>"></label>
                    <label>Minimum Sp. Att.: <input type="number" name="min_spatt" value="<?php echo htmlspecialchars($min_spatt); ?>"></label>
                    <label>Minimum Sp. Def.: <input type="number" name="min_spdef" value="<?php echo htmlspecialchars($min_spdef); ?>"></label>
                    <label>Minimum Speed: <input type="number" name="min_speed" value="<?php echo htmlspecialchars($min_speed); ?>"></label>
                    <label>Type: 
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
                <button type="submit">Search</button>
            </form>
        </div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Types</th>
                    <th>HP</th>
                    <th>Attack</th>
                    <th>Defense</th>
                    <th>Sp. Att.</th>
                    <th>Sp. Def.</th>
                    <th>Speed</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>
                        <td>" . htmlspecialchars($result['name_english']) . "</td>
                        <td>" . htmlspecialchars($result['types']) . "</td>
                        <td>" . htmlspecialchars($result['hp']) . "</td>
                        <td>" . htmlspecialchars($result['attack']) . "</td>
                        <td>" . htmlspecialchars($result['defense']) . "</td>
                        <td>" . htmlspecialchars($result['special_attack']) . "</td>
                        <td>" . htmlspecialchars($result['special_defense']) . "</td>
                        <td>" . htmlspecialchars($result['speed']) . "</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
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

        for ($page = 1; $page <= $total_pages; $page++) {
            echo '<a href="?page=' . $page . '&' . $url_params . '">' . $page . '</a>';
        }
        ?>
    </div>
</div>

<script src="search.js"></script>

<?php require_once __DIR__ . '../../partials/footer.php'; ?>
