<?php
// Database connection parameters
require 'dbconfig.php';

try {
    $pdo = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Path to JSON file
    $jsonFilePath = 'pokedex.json';
    // Read JSON file
    $jsonData = file_get_contents($jsonFilePath);
    if ($jsonData === false) {
        throw new Exception("Could not read JSON file.");
    }
    $pokemonData = json_decode($jsonData, true);
    if ($pokemonData === null) {
        throw new Exception("JSON decoding failed.");
    }

    $sql0 = "CREATE TABLE IF NOT EXISTS `evolution` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `pokemon_id` INT UNSIGNED,
    `prev_id` INT UNSIGNED,
    `next_id` INT UNSIGNED, 
    FOREIGN KEY (`pokemon_id`) REFERENCES `pokemons`(`id`),
    FOREIGN KEY (`prev_id`) REFERENCES `pokemons`(`id`),
    FOREIGN KEY (`next_id`) REFERENCES `pokemons`(`id`)
) ENGINE=InnoDB;";
    $sql = "INSERT INTO evolution (pokemon_id, prev_id, next_id)
SELECT 
    (SELECT id FROM pokemons WHERE name_english = :name LIMIT 1),
    (SELECT id FROM pokemons WHERE name_english = :prevName LIMIT 1),
    (SELECT id FROM pokemons WHERE name_english = :nextName LIMIT 1);";
    $sqlP = "INSERT INTO evolution (pokemon_id, prev_id)
       SELECT 
        (SELECT id FROM pokemons WHERE name_english = :name LIMIT 1),
        (SELECT id FROM pokemons WHERE name_english = :prevName LIMIT 1);";
    $sqlN = "INSERT INTO evolution (pokemon_id, next_id)
        SELECT 
    (SELECT id FROM pokemons WHERE name_english = :name LIMIT 1),
    (SELECT id FROM pokemons WHERE name_english = :nextName LIMIT 1);";
    
    $stmt0 = $pdo->prepare($sql0);
    $stmt0->execute();
    $stmt = $pdo->prepare($sql);
    $stmtP = $pdo->prepare($sqlP);
    $stmtN = $pdo->prepare($sqlN);

    // Handle evolutions
    foreach ($pokemonData as $pokemon) {
        $name = $pokemon['name']['english'];
        // echo "name : $name";
        $prev = $pokemon['evolution']['prev'][0] ?? null;
        $evolArray = $pokemon['evolution']['next'] ?? null;
        $prevName = null;
        $nextName = null;
        
        if ($prev !== null){
            foreach ($pokemonData as $poke) {
                if (strval($poke['id']) == $prev) {
                    $prevName = $poke['name']['english'];
                    echo $name . " " . $prevName;
                    break;
                }
            }
            if($prevName !== null && $evolArray == null){
                $stmtP->bindValue(":name", $name, PDO::PARAM_STR);
                $stmtP->bindValue(":prevName", $prevName, PDO::PARAM_STR);
                try {
                    $stmtP->execute();
                } catch (PDOException $e) {
                    echo "Error updating evolution for pokemon: " . $e->getMessage() . "\n";
                }
            }
        }
        
        if ($evolArray !== null) { 
        
            foreach ($evolArray as $evol){
                $evolId = $evol[0];
                foreach ($pokemonData as $poke) {
                    if (strval($poke['id']) == $evolId) {
                        $nextName = $poke['name']['english'];
                        break;
                    }
                }
                if ($nextName !== null && $prevName !== null){
                    $stmt->bindValue(":name", $name, PDO::PARAM_STR);
                    $stmt->bindValue(":prevName", $prevName, PDO::PARAM_STR);
                    $stmt->bindValue(":nextName", $nextName, PDO::PARAM_STR);
                    try {
                        $stmt->execute();
                    } catch (PDOException $e) {
                        echo "Error updating evolution for pokemon: " . $e->getMessage() . "\n";
                    }
                }elseif ($nextName !== null && $prevName == null){
                    $stmtN->bindValue(":name", $name, PDO::PARAM_STR);
                    $stmtN->bindValue(":nextName", $nextName, PDO::PARAM_STR);
                    try {
                        $stmtN->execute();
                    } catch (PDOException $e) {
                        echo "Error updating evolution for pokemon: " . $e->getMessage() . "\n";
                    }
                } 

            }
        }
        

    }
    echo "Data inserted successfully!";

} catch (PDOException $e) {
    echo "Database connection error: " . $e->getMessage();
} catch (Exception $e) {
    echo "General error: " . $e->getMessage();
}

?>