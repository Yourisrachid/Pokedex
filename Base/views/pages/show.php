<?php
$title = $_GET['name'];
require_once __DIR__ . '../../partials/header.php';

try {
    $pdo = new PDO("mysql:host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("
        SELECT pokemons.*, GROUP_CONCAT(types.type SEPARATOR ', ') as types
        FROM pokemons
        LEFT JOIN pokemon_types ON pokemons.id = pokemon_types.pokemon_id
        LEFT JOIN types ON pokemon_types.type_id = types.id
        WHERE LOWER(pokemons.name_english) = ?
        GROUP BY pokemons.id
    ");
    $stmt->execute([strtolower($_GET['name'])]);
    $pokemon = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pokemon) {
        die("Pokemon not found.");
    }

    function fetchPokemonById($pdo, $id) {
        $stmt = $pdo->prepare("
            SELECT pokemons.*, GROUP_CONCAT(types.type SEPARATOR ', ') as types
            FROM pokemons
            LEFT JOIN pokemon_types ON pokemons.id = pokemon_types.pokemon_id
            LEFT JOIN types ON pokemon_types.type_id = types.id
            WHERE pokemons.id = ?
            GROUP BY pokemons.id
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function getFullEvolutionCycle($pdo, $pokemon) {
        $evolutionCycle = [];
        $visited = [];

        function fetchEvolutions($pdo, $pokemon, &$evolutionCycle, &$visited, $direction) {
            $evolutionData = ($direction == 'prev') ? 'prev_evolutions' : 'next_evolutions';
            if (!empty($pokemon[$evolutionData])) {
                $evolutionIds = json_decode($pokemon[$evolutionData], true);
                foreach ($evolutionIds as $evolutionId) {
                    if (!in_array($evolutionId, $visited)) {
                        $nextPokemon = fetchPokemonById($pdo, $evolutionId);
                        if ($nextPokemon) {
                            $visited[] = $nextPokemon['id'];
                            fetchEvolutions($pdo, $nextPokemon, $evolutionCycle, $visited, $direction);
                            $evolutionCycle[] = $nextPokemon;
                        }
                    }
                }
            }
        }

        $evolutionCycle[] = $pokemon;
        $visited[] = $pokemon['id'];

        fetchEvolutions($pdo, $pokemon, $evolutionCycle, $visited, 'prev');
        fetchEvolutions($pdo, $pokemon, $evolutionCycle, $visited, 'next');

        usort($evolutionCycle, function($a, $b) {
            return $a['id'] - $b['id'];
        });

        return $evolutionCycle;
    }

    $evolutionCycle = getFullEvolutionCycle($pdo, $pokemon);

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

<main>
    <div class="pokemon-header">
        <h1><?php echo htmlspecialchars($pokemon['name_english']); ?></h1>
        <img class="pokemon-image" src="./public/img/pokemon/<?php echo strtolower($pokemon['name_english']); ?>.png" alt="<?php echo htmlspecialchars($pokemon['name_english']); ?>">
    </div>
    
    <div class="pokemon-details">
        <div class="types">
            <?php 
                $types = explode(', ', $pokemon['types']);
                foreach ($types as $type) {
                    echo '<span class="type ' . strtolower($type) . '">' . htmlspecialchars($type) . '</span>';
                }
            ?>
        </div>
        
        <div class="stats">
            <?php 
                $stats = ['hp' => 'HP', 'attack' => 'Attack', 'defense' => 'Defense', 'special_attack' => 'Sp. Att.', 'special_defense' => 'Sp. Def.', 'speed' => 'Speed'];
                foreach ($stats as $key => $label) {
                    echo '
                    <div class="stat">
                        <span>' . $label . '</span>
                        <div class="bar">
                            <div class="fill" style="width: ' . htmlspecialchars($pokemon[$key]) . '%;"></div>
                        </div>
                        <span>' . htmlspecialchars($pokemon[$key]) . '</span>
                    </div>';
                }
            ?>
        </div>
    </div>

    <div class="evolution">
        <h2>Evolution</h2>
        <div class="evolution-chain">
            <?php foreach ($evolutionCycle as $evolutionStage): ?>
                <div class="evolution-stage">
                    <a class="pokemonEvolution" href="pokemon?name=<?php echo strtolower(htmlspecialchars($evolutionStage['name_english'])); ?>">
                        <img src="./public/img/pokemon/<?php echo strtolower($evolutionStage['name_english']); ?>.png" alt="<?php echo htmlspecialchars($evolutionStage['name_english']); ?>">
                        <p><?php echo htmlspecialchars($evolutionStage['name_english']); ?></p>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <a class="pokemonName" href="/">Homepage</a>
</main>

<?php require_once __DIR__ . '../../partials/footer.php'; ?>
