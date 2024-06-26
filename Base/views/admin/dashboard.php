<?php

require '../../assets/dbconfig.php';



try {
    $pdo = new PDO("mysql:host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT * FROM Pokedex");
    $pokemonList = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if(isset($_POST["name"]) && isset($_POST["checkbox-item"]) && isset($_POST["hp"]) &&  isset($_POST["attack"]) &&  isset($_POST["defense"]) &&  isset($_POST["spe-attack"]) &&  
isset($_POST["spe-defense"]) &&  isset($_POST["speed"])  &&  isset($_POST["species"])  &&  isset($_POST["description"]) 
 &&  isset($_POST["height"])  &&  isset($_POST["weight"])) {
    $name = $_POST["name"];
    $hp = $_POST["hp"];
    $attack = $_POST["attack"];
    $defense = $_POST["defense"];
    $spe_attack = $_POST["spe-attack"];
    $spe_defense = $_POST["spe-defense"];
    $speed = $_POST["speed"];
    $species = $_POST["species"];
    $description = $_POST["description"];
    $height = $_POST["height"];
    $weight = $_POST["weight"];


   
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <script defer type="module" src="../../assets/js/main.js"></script>
    <title>Document</title>
</head>

<body>
    <header>

        <div class="dashboard">
            <div class="left-panel">
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
                    <div class="info" id="total-pokemon">Nombre total de Pokémon : 150</div>
                    <?php  echo $name ;?>
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
                            <label for="male">Male</label><br>
                            <input type="radio" id="male" name="gender" value="male" required>
                            <label for="female">Female</label>
                            <input type="radio" id="female" name="gender" value="female" required>
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



        <!-- 
        <main class="background-dashboard">
            <section class="container-dashboard">
                <div class="admin">
                    <div>
                        <img class="redim-avatar-admin" src="../../public/img/man1.png" alt="Pokémon Logo" alt="">
                        <p>Nom admin</p>
                        <p>Grade admin</p>
                    </div>
                </div>
                <div class="info-user-admin">
                    <h2>Dasbord Admin</h2>
                    <div>
                        <div>
                           <p>Ajouter un Pokémon</p>
                        </div>
                        <div>
                           <p>retirer un Pokémon</p>
                        </div>
                        <div>
                           <p>Nombre pokémon : </p>
                        </div>
                    </div>
                </div>
            </section>
        </main> -->
    </header>
</body>

</html>