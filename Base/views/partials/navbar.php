<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css">
    <title>Pokémon NavBar</title>
</head>
<body>
    <nav class="navbar">
        <div class="left-section">
            <a href="#"><img src="../../public/img/pokemon-logo.png" alt="Pokémon Logo"></a>
        </div>
        <div class="right-section">
            <div class="toggle-darkmode">🌙</div>
            <input type="text" placeholder="Search...">
            <div class="profile"></div>
        </div>
    </nav>

    <script>
        const toggleDarkMode = document.querySelector('.toggle-darkmode');
        const navbar = document.querySelector('.navbar');
        let darkMode = false;

        toggleDarkMode.addEventListener('click', () => {
            darkMode = !darkMode;
            if (darkMode) {
                navbar.classList.add('dark');
                toggleDarkMode.textContent = '☀️';
            } else {
                navbar.classList.remove('dark');
                toggleDarkMode.textContent = '🌙';
            }
        });
    </script>
</body>
</html>
