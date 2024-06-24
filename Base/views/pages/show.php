<?php
$title = $_GET['name'];
require_once __DIR__ . '../../partials/header.php';
?>

<main>
    <h1>
        <?php echo $_GET['name']; ?>
    </h1>
    <a href="/">Homepage</a>
</main>

<?php
require_once __DIR__ . '../../partials/footer.php';
?>