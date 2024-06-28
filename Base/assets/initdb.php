<?php
// Database connection parameters
require 'dbconfig.php';

try {
    $pdo = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Path to JSON file
    $jsonFilePath = './pokedex.json';
    // Read JSON file
    $jsonData = file_get_contents($jsonFilePath);
    if ($jsonData === false) {
        throw new Exception("Could not read JSON file.");
    }
    $pokemonData = json_decode($jsonData, true);
    if ($pokemonData === null) {
        throw new Exception("JSON decoding failed.");
    }

    // Prepare SQL statements
    $sqlpokemon = "INSERT INTO pokemons (name_english, name_japanese, name_chinese, name_french, hp, attack, defense, speed, special_attack, special_defense, description, height_m, weight_kg, species_id) 
            VALUES (:name_english, :name_japanese, :name_chinese, :name_french, :hp, :attack, :defense, :speed, :special_attack, :special_defense, :description, :height_m, :weight_kg, (SELECT id FROM species WHERE species = :species))";

    $sqlgetspecies = "SELECT id FROM species WHERE species = :species";
    $sqlspecies = "INSERT INTO species (species) VALUES (:species)";
    $sqlpoketype = "INSERT INTO pokemon_types (pokemon_id, type_id) VALUES ((SELECT id FROM pokemons WHERE name_english = :name_english), (SELECT id FROM types WHERE type = :type))";
    $sqlgettype = "SELECT id FROM types WHERE type = :type";
    $sqltype = "INSERT INTO types (type) VALUES (:type)";
    

    $stmtPoke = $pdo->prepare($sqlpokemon);
    $stmtGetspec = $pdo->prepare($sqlgetspecies);
    $stmtSpec = $pdo->prepare($sqlspecies);
    $stmtPokeType = $pdo->prepare($sqlpoketype);
    $stmtGettype = $pdo->prepare($sqlgettype);
    $stmtType = $pdo->prepare($sqltype);

    foreach ($pokemonData as $pokemon) {
        $name_english = $pokemon['name']['english'];
        $name_japanese = $pokemon['name']['japanese'];
        $name_chinese = $pokemon['name']['chinese'];
        $name_french = $pokemon['name']['french'];

        // Use null coalescing operator to handle missing keys
        $hp = $pokemon['base']['HP'] ?? null;
        $attack = $pokemon['base']['Attack'] ?? null;
        $defense = $pokemon['base']['Defense'] ?? null;
        $speed = $pokemon['base']['Speed'] ?? null;
        $special_attack = $pokemon['base']['Sp. Attack'] ?? null;
        $special_defense = $pokemon['base']['Sp. Defense'] ?? null;
        $description = $pokemon['description'] ?? null;
        $height = floatval($pokemon['profile']['height']) ?? null;
        $weight = floatval($pokemon['profile']['weight']) ?? null;
        $species = $pokemon['species'];

        // Check and insert species if not exists
        $stmtGetspec->bindValue(":species", $species, PDO::PARAM_STR);
        $stmtGetspec->execute();
        if ($stmtGetspec->rowCount() == 0) {
            $stmtSpec->bindValue(":species", $species, PDO::PARAM_STR);
            $stmtSpec->execute();
        }

        // Insert pokemon
        $stmtPoke->bindValue(":name_english", $name_english, PDO::PARAM_STR);
        $stmtPoke->bindValue(":name_japanese", $name_japanese, PDO::PARAM_STR);
        $stmtPoke->bindValue(":name_chinese", $name_chinese, PDO::PARAM_STR);
        $stmtPoke->bindValue(":name_french", $name_french, PDO::PARAM_STR);
        $stmtPoke->bindValue(":hp", $hp, PDO::PARAM_INT);
        $stmtPoke->bindValue(":attack", $attack, PDO::PARAM_INT);
        $stmtPoke->bindValue(":defense", $defense, PDO::PARAM_INT);
        $stmtPoke->bindValue(":speed", $speed, PDO::PARAM_INT);
        $stmtPoke->bindValue(":special_attack", $special_attack, PDO::PARAM_INT);
        $stmtPoke->bindValue(":special_defense", $special_defense, PDO::PARAM_INT);
        $stmtPoke->bindValue(":description", $description, PDO::PARAM_STR);
        $stmtPoke->bindValue(":height_m", $height, PDO::PARAM_STR);
        $stmtPoke->bindValue(":weight_kg", $weight, PDO::PARAM_STR);
        $stmtPoke->bindValue(":species", $species, PDO::PARAM_STR);

        try {
            $stmtPoke->execute();
            echo "Inserted pokemon: $name_english\n";
        } catch (PDOException $e) {
            echo "Error inserting pokemon: " . $e->getMessage() . "\n";
        }

        // Insert types
        $types = $pokemon['type'] ?? [];
        foreach ($types as $type) {
            $stmtGettype->bindValue(":type", $type, PDO::PARAM_STR);
            $stmtGettype->execute();
            if ($stmtGettype->rowCount() == 0) {
                $stmtType->bindValue(":type", $type, PDO::PARAM_STR);
                $stmtType->execute();
            }

            $stmtPokeType->bindValue(":name_english", $name_english, PDO::PARAM_STR);
            $stmtPokeType->bindValue(":type", $type, PDO::PARAM_STR);
            try {
                $stmtPokeType->execute();
                echo "Inserted type for pokemon: $name_english - $type\n";
            } catch (PDOException $e) {
                echo "Error inserting type for pokemon: " . $e->getMessage() . "\n";
            }
        }
    }
    
    echo "Data inserted successfully!";

} catch (PDOException $e) {
    echo "Database connection error: " . $e->getMessage();
} catch (Exception $e) {
    echo "General error: " . $e->getMessage();
}