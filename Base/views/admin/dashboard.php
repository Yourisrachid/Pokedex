<?php


try {
    $pdo = new PDO("mysql:host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT * FROM `pokemons` INNER JOIN species ON pokemons.species_id = species.id;");
    $pokemonList = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = $pdo->query("SELECT COUNT(*) AS pokemon_count FROM `pokemons`;");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $count = $result['pokemon_count'];
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$speciesList = [];
foreach ($pokemonList as $pokemon) {
    $speciesList[$pokemon['species_id']] = $pokemon['species'];
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    print_r($_POST); // Debugging statement

    if (isset($_POST['name']) && isset($_POST['checkbox-item']) && isset($_POST['hp']) &&  
        isset($_POST['attack']) && isset($_POST['defense']) && isset($_POST['spe-attack']) &&  
        isset($_POST['spe-defense']) && isset($_POST['speed']) && isset($_POST['species']) &&  
        isset($_POST['description']) && isset($_POST['height']) && isset($_POST['weight'])) {
        
        $name = $_POST['name'];
        $checkbox_item = $_POST['checkbox-item'];
        $hp = $_POST['hp'];
        $attack = $_POST['attack'];
        $defense = $_POST['defense'];
        $spe_attack = $_POST['spe-attack'];
        $spe_defense = $_POST['spe-defense'];
        $speed = $_POST['speed'];
        $species = $_POST['species'];
        $description = $_POST['description'];
        $height = $_POST['height'];
        $weight = $_POST['weight'];

        $params = [
            ':name' => $name,
            ':checkbox_item'=> $checkbox_item,
            ':hp'=> $hp,
            ':attack'=> $attack,
            ':defense'=> $defense,
            ':spe_attack'=> $spe_attack,
            ':spe_defense'=> $spe_defense,
            ':speed'=> $speed,
            ':species'=> $species,
            ':description'=> $description,
            ':height'=> $height,
            ':weight'=> $weight
        ];

        echo '<pre>';
        print_r($params); // Debugging statement
        echo '</pre>';
        // Optional: Insert the data into the database
        $sql = "INSERT INTO pokedex (name, type, hp, attack, defense, special_attack, special_defense, speed, species, description, height_m, weight_kg) 
                VALUES (:name, :checkbox_item, :hp, :attack, :defense, :spe_attack, :spe_defense, :speed, :species, :description, :height, :weight)";
        $stmt = $pdo->prepare($sql);

        print_r($stmt);
        $stmt->execute($params);
        


    } else {
        echo "All fields are required.";
    }
    if (isset($_POST['pokemon-type']) && is_array($_POST['pokemon-type'])) {
        $selectedTypes = $_POST['pokemon-type'];
        if (count($selectedTypes) > 2) {
            echo "You can only select up to two types.";
        } else {
            // Process the selected types
            foreach ($selectedTypes as $type) {
                echo htmlspecialchars($type) . "<br>";
            }
        }
    } else {
        echo "No types selected.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <script defer type="module" src="../../assets/js/maindashboard.js"></script>
    <title>Document</title>
</head>

<body>
    <header>
        <div class="dashboard">
            <div class="left-panel">
                <div class="button">
                    <a href="/">Home</a>
                </div>
                <div>
                    <img class="redim-avatar-admin" src="../../public/img/man1.png" alt="Pokémon Logo" alt="">
                    <h2 class="admin-name">Nom de l'Admin</h2>
                    <p class="admin-grade">Grade Admin</p>
                </div>
                <div class="informations-dashboard">
                    <h2>Informations</h2>
                    <div>
                        <p>Le meilleur Développeur</p>
                        <p>Le meilleur Backend</p>
                        <p>Le meilleur Fronted</p>
                        <img class="github-logo" src="../../public/img/github-logo.png" alt="">
                    </div>
                </div>
            </div>
            <div class="right-panel">
                <div class="logo_pokemon">
                    <img src="../../public/img/pokemon-logo.png" alt="Pokémon Logo">
                </div>
                <div class="container-h2-right">
                    <h2>Dashboard - Admin</h2>
                </div>
                <div class="right-panel-button">
                    <div class="button" id="add-button">Ajouter un pokémon</div>
                    <div class="button" id="delete-button">Supprimer un pokémon</div>
                    <div class="info" id="total-pokemon">Nombre total de Pokémon : <?php echo $count; ?></div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>

                <h1>Formulaire Pokémon</h1>
                <form action="#" method="post">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" maxlength="10" required>
                    </div>
                    <div class="form-group">
                        <label for="pokemon-types">Choose Pokémon Types:</label>
                        <div id="pokemon-types" class="custom-checkbox-group">
                            <div class="checkbox-item">
                                <input type="checkbox" id="Normal" name="pokemon-type" value="Normal">
                                <label id="Normal" for="Normal"></label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="Fire" name="pokemon-type" value="Fire">
                                <label id="Fire" for="Fire"></label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="Water" name="pokemon-type" value="Water">
                                <label id="Water" for="Water"></label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="Electric" name="pokemon-type" value="Electric">
                                <label id="Electric" for="Electric"></label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="Grass" name="pokemon-type" value="Grass">
                                <label id="Grass" for="Grass"></label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="Ice" name="pokemon-type" value="Ice">
                                <label id="Ice" for="Ice"></label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="Fighting" name="pokemon-type" value="Fighting">
                                <label id="Fighting" for="Fighting"></label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="Poison" name="pokemon-type" value="Poison">
                                <label id="Poison" for="Poison"></label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="Ground" name="pokemon-type" value="Ground">
                                <label id="Ground" for="Ground"></label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="Flying" name="pokemon-type" value="Flying">
                                <label id="Flying" for="Flying"></label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="Psychic" name="pokemon-type" value="Psychic">
                                <label id="Psychic" for="Psychic"></label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="Bug" name="pokemon-type" value="Bug">
                                <label id="Bug" for="Bug"></label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="Rock" name="pokemon-type" value="Rock">
                                <label id="Rock" for="Rock"></label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="Ghost" name="pokemon-type" value="Ghost">
                                <label id="Ghost" for="Ghost"></label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="Dragon" name="pokemon-type" value="Dragon">
                                <label id="Dragon" for="Dragon"></label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="Dark" name="pokemon-type" value="Dark">
                                <label id="Dark" for="Dark"></label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="Steel" name="pokemon-type" value="Steel">
                                <label id="Steel" for="Steel"></label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="Fairy" name="pokemon-type" value="Fairy">
                                <label id="Fairy" for="Fairy"></label>
                            </div>
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="hp">HP:</label>
                        <input type="number" id="hp" name="hp" required>
                    </div>
                    <div class="form-group">
                        <label for="attack">Attack:</label>
                        <input type="number" id="attack" name="attack" required>
                    </div>
                    <div class="form-group">
                        <label for="defense">Defense:</label>
                        <input type="number" id="defense" name="defense" required>
                    </div>
                    <div class="form-group">
                        <label for="spe-attack">Special Attack:</label>
                        <input type="number" id="spe-attack" name="spe_attack" required>
                    </div>
                    <div class="form-group">
                        <label for="spe-defense">Special Defense:</label>
                        <input type="number" id="spe-defense" name="spe_defense" required>
                    </div>
                    <div class="form-group">
                        <label for="speed">Speed:</label>
                        <input type="number" id="speed" name="speed" required>
                    </div>
                    <div class="form-group" id="species-group">
                        <label for="species">Species:</label>
                        <div class="radio-species">
                            
                            <select name="specices" id="species">
                            <?php foreach ($speciesList as $speciesId => $speciesName): ?>
                                <option value="<?php echo htmlspecialchars($speciesId); ?>">
                                <?php echo htmlspecialchars($speciesName); ?>
                                </option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea id="description" name="description" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="height">Height:</label>
                        <input type="number" id="height" name="height" required>
                    </div>
                    <div class="form-group">
                        <label for="weight">Weight:</label>
                        <input type="number" id="weight" name="weight" required>
                    </div>
                    <button type="submit">Submit</button>
                </form>
            </div>
        </div>
    </header>
</body>

</html>