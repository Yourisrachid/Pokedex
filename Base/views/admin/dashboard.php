<?php 

require '../../assets/dbconfig.php';



try {
    $pdo = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT * FROM Pokedex");
    $pokemonList = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <!-- <script defer type="module"src="../../assets/js/select.js"></script> -->
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
                    <img  src="../../public/img/pokemon-logo.png" alt="Pokémon Logo">
                </div>
                <div class="container-h2-right">
                    <h2>Dashboard - Admin</h2>
                </div>
                <div class="right-panel-button">
                    <div class="button" id="add-button">Ajouter un pokémon</div>
                    <div class="button" id="delete-button">Supprimer un pokémon</div>
                    <div class="info" id="total-pokemon">Nombre total de Pokémon : 150</div>
                </div>

            </div>
        </div>

        <!-- Modal -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <p>Formulaire pour ajouter un élément.</p>
            </div>
        </div>

        <script src="scripts.js"></script>

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