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
    $sql = "UPDATE pokemons SET pokemon_evol_id = (SELECT id FROM pokemons WHERE name_english = :evol_name LIMIT 1) WHERE name_english = :name";
    
    $stmt = $pdo->prepare($sql);

    // Handle evolutions
    foreach ($pokemonData as $pokemon) {
        $name = $pokemon['name']['english'];
        echo "name : $name";
        $evolArray = $pokemon['evolution']['next'][0] ?? null;
        // print_r($evolArray);
        if ($evolArray !== null) {
            
            $evolId = $evolArray[0]; // Accessing the ID directly

            $evolName = null;
            // Find the evolution name using the ID
            foreach ($pokemonData as $poke) {
                if (strval($poke['id']) == $evolId) {
                    $evolName = $poke['name']['english'];
                    break;
                }
            }

            if ($evolName !== null) {
                $stmt->bindValue(":evol_name", $evolName, PDO::PARAM_STR);
                $stmt->bindValue(":name", $name, PDO::PARAM_STR);
                try {
                    $stmt->execute();
                    if ($stmt->rowCount() > 0) {
                        echo "Updated evolution for pokemon: $name -> $evolName\n";
                    } else {
                        echo "No evolution updated for pokemon: $name\n";
                    }
                } catch (PDOException $e) {
                    echo "Error updating evolution for pokemon: " . $e->getMessage() . "\n";
                }
            } else {
                echo "Evolution name not found for pokemon: $name\n";
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