<?php
    require "db.php";
    $id = (int)$_GET['id'];

    $stmt = $mysqli->prepare("SELECT * FROM cards WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $model = $result->fetch_assoc();
    $stmt->close();
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
        <h1><?= htmlspecialchars($model['title']) ?></h1>

        <model-viewer src="<?= $model['model_path'] ?>"
                        alt="3D model"
                        camera-controls
                        auto-rotate
                        environment-image="neutral">
        </model-viewer>

        <p><a href="index.php">← Zpět na galerii</a></p>


    <!-- scripts -->
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.3.0/model-viewer.min.js"></script>
    </body>
</html>