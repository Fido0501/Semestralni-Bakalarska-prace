<?php
    require "db.php";
    $id = (int)$_GET['id'];

    $stmt = $mysqli->prepare("SELECT * FROM cards WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $model = $result->fetch_assoc();
    $stmt->close();

    if (!isset($_GET['id'])) {
    die("Model nenalezen.");
    }

    $id = (int)$_GET['id'];
    $result = $mysqli->query("SELECT * FROM cards WHERE id = $id");
    if (!$result || $result->num_rows === 0) {
        die("Model nenalezen v databázi.");
    }

    $row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="cs">
    <head>
        <!-- meta -->
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- link -->
        <link rel="stylesheet" href="style/main.css">
        <link rel="stylesheet" href="style/style.css">
        <link rel="stylesheet" href="style/partials.css">

        <!-- title -->
        <title><?= htmlspecialchars($model['title']) ?></title>
    </head>
    <body>
        <header>
            <?php
                include "partials/header.php";
            ?>
        </header>
        
        <section class="main">
            <h1><?= htmlspecialchars($model['title']) ?></h1>
            <?php if (!empty($row['thumbnail_path'])): ?>
                <img class="thumb" src="<?= htmlspecialchars($row['thumbnail_path']) ?>" alt="Náhled">
            <?php else: ?>
                <p><em>Žádný obrázek</em></p>
            <?php endif; ?>

            <div class="mw">
                <model-viewer src="<?= $model['model_path'] ?>"
                            alt="3D model"
                            camera-controls
                            auto-rotate
                            environment-image="neutral">
                </model-viewer>
            </div>

            <div class="actions">
                <a href="edit.php?id=<?= $row['id'] ?>">Upravit</a>
            </div>

            <p><a href="index.php">← Zpět na galerii</a></p>
        </section>

        <footer>
            <?php
                include "partials/footer.php";
            ?>
        </footer>
    <!-- scripts -->
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.3.0/model-viewer.min.js"></script>
    </body>
</html>