<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <title>Document</title>
</head>
<body>
<header>
        <a href="/"><img src="../../public/img/pokemon-logo.png" alt="Pokemon Logo"></a>
        <nav>
            <ul>
                <li><a href="#">Pokemon</a></li>
                <li><a href="#" class="active">My Account</a></li>
                <li><a href="#">Register</a></li>
            </ul>
        </nav>
    </header>
    <main class="form-main">
        <section class="login-container">
            <h2>My Account</h2>
            <form>
                <label for="email">Email*</label>
                <input type="email" id="email" name="email" placeholder="email@yourservice.com" required>
                <label for="password">Password*</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Connexion</button>
                <br><br>
                <p>No account? <a class="Create_One" href="registerFrom.php">Create one!</a></p>
            </form>
        </section>
    </main>
</body>
</html>