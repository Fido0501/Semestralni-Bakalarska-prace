<?php
require "db.php";

if (!isset($_GET['id'])) {
    die("Model nenalezen.");
}

$id = (int)$_GET['id'];
$result = $mysqli->query("SELECT * FROM cards WHERE id = $id");
if (!$result || $result->num_rows === 0) {
    die("Model nenalezen.");
}

$row = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $mysqli->real_escape_string($_POST["title"]);

    // uchování původních cest
    $thumbPath = $row['thumbnail_path'];
    $modelPath = $row['model_path'];

    // nový thumbnail?
    if (!empty($_FILES["thumbnail"]["name"])) {
        $thumbPath = "uploads" . basename($_FILES["thumbnail"]["name"]);
        move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $thumbPath);
    }

    // nový 3D model?
    if (!empty($_FILES["model"]["name"])) {
        $modelPath = "uploads" . basename($_FILES["model"]["name"]);
        move_uploaded_file($_FILES["model"]["tmp_name"], $modelPath);
    }

    $update = $mysqli->prepare("UPDATE cards SET title=?, thumbnail_path=?, model_path=? WHERE id=?");
    $update->bind_param("sssi", $title, $thumbPath, $modelPath, $id);
    $update->execute();

    header("Location: detail.php?id=$id");
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

        <!-- link
        <link rel="stylesheet" href="style/main.css">
        <link rel="stylesheet" href="style/style.css">
        <link rel="stylesheet" href="style/partials.css">-->

        <!-- title -->
        <title>Editovat</title>
    </head>
    <body>
        <h1>Upravit model</h1>
        <form method="POST" enctype="multipart/form-data">
            <label>Název modelu:</label><br>
            <input type="text" name="title" value="<?= htmlspecialchars($row['title']) ?>" required><br><br>
            <label>Změnit 3D model (nepovinné):</label><br>
            <input type="file" name="model" accept=".glb,.gltf"><br><br>

            <label>Změnit obrázek (nepovinné):</label><br>
            <input type="file" name="thumbnail" accept="image/*"><br><br>

            <button type="submit">Uložit změny</button>
        </form>
        
        <a href="detail.php?id=<?= $id ?>">← Zpět na detail</a></p>
    </body>
</html>