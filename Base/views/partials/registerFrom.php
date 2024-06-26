<?php

$title = "Register";
require_once __DIR__ . '../../partials/header.php';

?>
    <header>
    <a href="/"><img src="../../public/img/pokemon-logo.png" alt="Pokemon Logo"></a>
        <nav>
            <ul>
                <li><a href="/">Pokemon</a></li>
                <li><a href="/login">My Account</a></li>
                <li><a href="#" class="active">Register</a></li>
            </ul>
        </nav>
    </header>
    <main class="form-main">
        <section class="register-container">
            <h2>Register</h2>
            <form action="/addUser" method="post">
                <label for="username">Username*</label>
                <input type="text" id="username" name="username" required>
                
                <label for="lastname">Lastname*</label>
                <input type="text" id="lastname" name="lastname" required>
                
                <label for="firstname">Firstname*</label>
                <input type="text" id="firstname" name="firstname" required>
                
                <label for="birthday">Birthday*</label>
                <input type="date" id="birthday" name="birthday" required>
                
                <label for="email">Email*</label>
                <input type="email" id="email" name="email" placeholder="email@yourservice.com" required>
                
                <label for="password">Password*</label>
                <input type="password" id="password" name="password" required>
                
                <label for="password-verification">Password Verification*</label>
                <input type="password" id="password-verification" name="password-verification" required>
                
                <button type="submit">New account</button>
                
            </form>
        </section>
    </main>

<?php
require_once __DIR__ . '../../partials/footer.php';
?>