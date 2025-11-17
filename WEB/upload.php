<?php
require "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST["title"];

    // složka pro nahrávání
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // ===== MODEL =====
    $modelFile = $_FILES["model"];
    $modelName = time() . "_" . basename($modelFile["name"]);
    $modelPath = $targetDir . $modelName;
    move_uploaded_file($modelFile["tmp_name"], $modelPath);

    // ===== THUMBNAIL =====
    $thumbFile = $_FILES["thumbnail"];
    $thumbName = time() . "_" . basename($thumbFile["name"]);
    $thumbPath = $targetDir . $thumbName;
    move_uploaded_file($thumbFile["tmp_name"], $thumbPath);

    // ===== ULOŽENÍ DO DB =====
    $stmt = $mysqli->prepare("INSERT INTO cards (title, model_path, thumbnail_path) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $modelPath, $thumbPath);
    $stmt->execute();
    $stmt->close();

    // zpět na galerii
    header("Location: index.php");
    exit;
}
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
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=chevron_backward,upload" />

        <!-- title -->
        <title>Přidat 3D model</title>
    </head>
    <body>
        <section class="main main-form">
            <h1>Nahrát nový 3D model</h1>

            <form action="upload.php" method="POST" enctype="multipart/form-data">
                <input class="text_input" type="text" name="title" placeholder="Zadej název modelu" maxlength="20" required>

                <label class="button-label" for="upload3d">Soubor modelu (.glb, .gltf, .obj, .fbx):</label>
                <input id="upload3d"type="file" name="model" accept=".glb,.gltf,.obj,.fbx" required>

                <label class="button-label" for="upload">Náhledový obrázek (.jpg, .png):</label>
                <input id="upload" type="file" name="thumbnail" accept="image/*" required>


                <div class="actions">
                    <a href="index.php" class="button"><span class="material-symbols-outlined">chevron_backward</span>Zpět na galerii</a>
                    <button type="submit" class="button"><span class="material-symbols-outlined">upload</span>Nahrát</button>
                </div>
                
            </form>
        </section>
    </body>
</html>