<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokémon NavBar</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #fff;
            border-bottom: 1px solid #ddd;
            transition: background-color 0.3s;
        }
        .navbar.dark {
            background-color: #333;
            color: #fff;
        }
        .navbar img {
            height: 50px;
        }
        .navbar .left-section, .navbar .right-section {
            display: flex;
            align-items: center;
        }
        .navbar .toggle-darkmode {
            margin-right: 20px;
            cursor: pointer;
        }
        .navbar input[type="text"] {
            padding: 5px;
            margin-right: 20px;
        }
        .navbar .profile {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-image: url('../../public/img/man1.png');
            background-size: cover;
        }
        .navbar.dark .profile {
            background-color: #555;
        }
    </style>
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
