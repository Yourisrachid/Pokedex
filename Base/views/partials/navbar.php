<nav class="navbar">
        <div class="left-section">
            <a href="/"><img src="../../public/img/pokemon-logo.png" alt="Pokémon Logo"></a>
        </div>

        <div class="links">
            <ul>
                <li><a href="/">Pokemon</a></li>
                <?php if (isset($_SESSION['user'])) {
                ?>
                <li><a href="/dashboard" class="active">Dashboard</a></li>
                <?php
                } else {
                ?>
                <li><a href="/login" class="active">My Account</a></li>
                <?php
                } 
                ?>
                <?php if (isset($_SESSION['user'])) {
                ?>
                <form action="/logout" method="post">
                    <button type="submit" name="logout">Log Out</button>
                </form>
            <?php } else { ?>
                <li><?php echoLink('/register', 'Register'); ?></li>
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
$current_page = basename($_SERVER['REQUEST_URI']);

function echoLink($url, $text) {
    global $current_page;
    $class = (basename($url) == $current_page) ? 'class="active"' : '';
    echo "<a href=\"$url\" $class>$text</a>";
}
?>
