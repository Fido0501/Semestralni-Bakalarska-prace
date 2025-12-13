<?php
    // načtení DB připojení
    require "db.php";

    // získat id z GET a převést na integer (bezpečnost)
    $id = (int)$_GET['id'];

    // připravený dotaz pro získání řádku s detailem modelu
    $stmt = $mysqli->prepare("SELECT * FROM cards WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $model = $result->fetch_assoc(); // data modelu
    $stmt->close();

    // jednoduchá kontrola, zda byl předán parametr id
    if (!isset($_GET['id'])) {
        die("Model nenalezen.");
    }

    // dodatkový dotaz (původní struktura) - kontrola existence v DB
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
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=chevron_backward,edit" />

        <!-- title: využívá PHP hodnotu z DB -->
        <title><?= htmlspecialchars($model['title']) ?></title>
    </head>
    <body>
        <section class="main">
            <!-- Nadpis z názvu modelu -->
            <h1><?= htmlspecialchars($model['title']) ?></h1>
            
            <div class="mw">
                <!-- model-viewer používá cestu k souboru z DB -->
                <model-viewer src="<?= $model['model_path'] ?>"
                            alt="3D model"
                            camera-controls
                            environment-image="neutral">
                </model-viewer>
            </div>

            <div class="actions">
                <a href="index.php" class="button"><span class="material-symbols-outlined">
                chevron_backward</span>Zpět na galerii</a>
                <!-- Odkaz na editaci používá id z dotazu -->
                <a href="edit.php?id=<?= $row['id'] ?>" class="button"><span class="material-symbols-outlined">
                edit</span>Upravit</a>
            </div>
        </section>

        <footer>
            <?php
                include "partials/footer.php"; // vložení patičky
            ?>
        </footer>
        
    <!-- scripts -->
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.3.0/model-viewer.min.js"></script>
    </body>
</html>