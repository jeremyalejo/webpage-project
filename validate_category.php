<?php
    $name  = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_input(INPUT_POST, 'description');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Validation</title>
    <link rel="stylesheet" type="text/css" href="css/crudStyles.css" />
</head>
<body>
    <div id = "wrapper">
        <?php if(strlen($name) == 0 || strlen($description) == 0) : ?>
            <h1>The title or content of the users post should have at least 1 character.</h1>
            <a href="home.php">Return home</a>
        <?php else : ?>
            <?= header("Location: home.php") ?>
            <?= exit() ?>
        <?php endif ?>
    </div>
</body>
</html> 