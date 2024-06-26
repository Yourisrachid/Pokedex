<?php

$title = "Login";
require_once __DIR__ . '../../partials/header.php';

?>



<header>
        <a href="/"><img src="../../public/img/pokemon-logo.png" alt="Pokemon Logo"></a>
        <nav>
            <ul>
                <li><a href="/">Pokemon</a></li>
                <li><a href="#" class="active">My Account</a></li>
                <li><a href="/register">Register</a></li>
            </ul>
        </nav>
    </header>
    <main class="form-main">
        <section class="login-container">
            <h2>My Account</h2>
            <form action="/check_login" method="post">
                <label for="username">Username*</label>
                <input type="username" id="username" name="username" placeholder="your username" required>
                <label for="password">Password*</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Connexion</button>
                <br><br>
                <p>No account? <a class="Create_One" href="registerFrom.php">Create one!</a></p>
            </form>
        </section>
    </main>

<?php
require_once __DIR__ . '../../partials/footer.php';
?>