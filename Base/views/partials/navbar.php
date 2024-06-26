
    <nav class="navbar">
        <div class="left-section">
            <a href="/"><img src="../../public/img/pokemon-logo.png" alt="Pokémon Logo"></a>
        </div>

        <div class="links">
            <ul>
                <li><?php echoLink('/', 'Pokemon'); ?></li>
                <li><?php echoLink('login', 'My account'); ?></li>
                <?php if (isset($_SESSION['user'])) {
                ?>
                <form action="/logout" method="post">
                <button type="submit" name="logout">Log Out</button>
                </form>
                <?php
                } else {
                ?>
                <li><a href="/register">Register</a></li>
                <?php } ?>

            </ul>
        </div>


        <div class="right-section">
            <div class="toggle-darkmode">🌙</div>
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

<?php

$current_page = basename($_SERVER['REQUEST_URL']);

function echoLink($url, $text) {
    global $current_page;
    $class = (basename($url) == $current_page) ? "class='active'" : '';
    echo "<a href=\"$url\" \"$class\">$text</a>";
}
?>