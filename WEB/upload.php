<?php
require "db.php";
ini_set('display_errors', 1);
error_reporting(E_ALL);

// zkontroluj, jestli přišel soubor
if ($_SERVER["REQUEST_METHOD"] === "POST" && empty($_FILES)) {
    die("<b>❌ Soubor se nepodařilo nahrát.</b><br>
    Možné důvody:<br>
    - příliš velký soubor<br>
    - překročen post_max_size nebo upload_max_filesize<br>
    - selhal upload na straně serveru");
}
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
    $modelPath = "uploads/" . $modelName;
    move_uploaded_file($modelFile["tmp_name"], $modelPath);

    // ===== THUMBNAIL =====
    $thumbFile = $_FILES["thumbnail"];
    $thumbName = time() . "_" . basename($thumbFile["name"]);
    $thumbPath = "uploads/" . $thumbName;
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
        <meta charset="UTF-8">
        <title>Nahrát 3D model</title>
    </head>
    <body>
        <h1>Nahrát nový 3D model</h1>

        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <label>Název modelu:</label><br>
            <input type="text" name="title" required><br><br>

            <label>Soubor modelu (.glb, .gltf, .obj, .fbx):</label><br>
            <input type="file" name="model" accept=".glb,.gltf,.obj,.fbx" required><br><br>

            <label>Náhledový obrázek (.jpg, .png):</label><br>
            <input type="file" name="thumbnail" accept="image/*" required><br><br>

            <button type="submit">Nahrát</button>
        </form>

        <p><a href="index.php">← Zpět na galerii</a></p>
    </body>
</html>