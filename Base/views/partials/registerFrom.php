<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokemon Register</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <header>
    <a href="/"><img src="../../public/img/pokemon-logo.png" alt="Pokemon Logo"></a>
        <nav>
            <ul>
                <li><a href="#">Pokemon</a></li>
                <li><a href="#">My Account</a></li>
                <li><a href="#" class="active">Register</a></li>
            </ul>
        </nav>
    </header>
    <main class="form-main">
        <section class="register-container">
            <h2>Register</h2>
            <form>
            <label for="text">Lastname</label>
            <input type="lastname" id="lastname" name="lastname"  required>
            <label for="text">Firstname</label>
            <input type="firstname" id="firstname" name="firstname" required>
                <label for="email">Email*</label>
                <input type="email" id="email" name="email" placeholder="email@yourservice.com" required>
                <label for="password">Password*</label>
                <input type="password" id="password" name="password" required>
                <label for="password-verification">Password Verification*</label>
                <input type="password" id="password-verification" name="password-verification" required>
                <button type="submit">New account</button>
                <?php ?>
            </form>
        </section>
    </main>
</body>
</html>
