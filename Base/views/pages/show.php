<?php
$title = $_GET['name'];
require_once __DIR__ . '../../partials/header.php';


//require './assets/dbconfig.php';

try {
    $pdo = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $stmt = $pdo->prepare("SELECT * FROM Pokedex WHERE LOWER(`name.english`) = ?");
    $stmt->execute([strtolower($_GET['name'])]);
    $pokemon = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pokemon) {
        die("Pokemon not found.");
    }


    function fetchPokemonById($pdo, $id) {
        $stmt = $pdo->prepare("SELECT * FROM Pokedex WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    function getFullEvolutionCycle($pdo, $pokemon) {
        $evolutionCycle = [];
        $visited = [];
    

        function fetchEvolutions($pdo, $pokemon, &$evolutionCycle, &$visited, $direction) {
            $evolutionData = ($direction == 'prev') ? 'evolution.prev' : 'evolution.next';
            

            if (!empty($pokemon[$evolutionData])) {
                $evolutionIds = json_decode($pokemon[$evolutionData], true);
                

                foreach ($evolutionIds as $evolutionId) {
                    if (!in_array($evolutionId[0], $visited)) {
                        $nextPokemon = fetchPokemonById($pdo, $evolutionId[0]);
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
        <h1><?php echo htmlspecialchars($pokemon['name.english']); ?></h1>
        <img class="pokemon-image" src="<?php echo htmlspecialchars($pokemon['image.hires']); ?>" alt="<?php echo htmlspecialchars($pokemon['name.english']); ?>">
    </div>
    
    <div class="pokemon-details">
        <div class="types">
            <?php 
                $types = json_decode($pokemon['type'], true);
                foreach ($types as $type) {
                    echo '<span class="type ' . strtolower($type) . '">' . htmlspecialchars($type) . '</span>';
                }
            ?>
        </div>
        
        <div class="stats">
            <div class="stat">
                <span>HP</span>
                <div class="bar">
                    <div class="fill" style="width: <?php echo htmlspecialchars($pokemon['base.HP']); ?>%;"></div>
                </div>
                <span><?php echo htmlspecialchars($pokemon['base.HP']); ?></span>
            </div>
            <div class="stat">
                <span>Attack</span>
                <div class="bar">
                    <div class="fill" style="width: <?php echo htmlspecialchars($pokemon['base.Attack']); ?>%; ?>"></div>
                </div>
                <span><?php echo htmlspecialchars($pokemon['base.Attack']); ?></span>
            </div>
            <div class="stat">
                <span>Defense</span>
                <div class="bar">
                    <div class="fill" style="width: <?php echo htmlspecialchars($pokemon['base.Defense']); ?>%; ?>"></div>
                </div>
                <span><?php echo htmlspecialchars($pokemon['base.Defense']); ?></span>
            </div>
            <div class="stat">
                <span>Sp. Att.</span>
                <div class="bar">
                    <div class="fill" style="width: <?php echo htmlspecialchars($pokemon['base.Sp. Attack']); ?>%; ?>"></div>
                </div>
                <span><?php echo htmlspecialchars($pokemon['base.Sp. Attack']); ?></span>
            </div>
            <div class="stat">
                <span>Sp. Def.</span>
                <div class="bar">
                    <div class="fill" style="width: <?php echo htmlspecialchars($pokemon['base.Sp. Defense']); ?>%; ?>"></div>
                </div>
                <span><?php echo htmlspecialchars($pokemon['base.Sp. Defense']); ?></span>
            </div>
            <div class="stat">
                <span>Speed</span>
                <div class="bar">
                    <div class="fill" style="width: <?php echo htmlspecialchars($pokemon['base.Speed']); ?>%; ?>"></div>
                </div>
                <span><?php echo htmlspecialchars($pokemon['base.Speed']); ?></span>
            </div>
        </div>
    </div>

    <div class="evolution">
        <h2>Evolution</h2>
        <div class="evolution-chain">
            <?php foreach ($evolutionCycle as $evolutionStage): ?>
                <div class="evolution-stage">
                    <a class="pokemonEvolution" href="pokemon?name=<?php echo strtolower(htmlspecialchars($evolutionStage['name.english'])); ?>">
                        <img src="<?php echo htmlspecialchars($evolutionStage['image.hires']); ?>" alt="<?php echo htmlspecialchars($evolutionStage['name.english']); ?>">
                        <p><?php echo htmlspecialchars($evolutionStage['name.english']); ?></p>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <a class="pokemonName" href="/">Homepage</a>
</main>

<?php
require_once __DIR__ . '../../partials/footer.php';
?>
