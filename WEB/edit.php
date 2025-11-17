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

    // složka pro nahrávání
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // cesty k původním souborům
    $modelPath = $row['model_path'];
    $thumbPath = $row['thumbnail_path'];

    // ===== Pokud je nahrán nový model =====
    if (!empty($_FILES["model"]["name"])) {
        // smaž starý model, pokud existuje
        if (file_exists($modelPath)) {
            unlink($modelPath);
        }
        $modelName = basename($_FILES["model"]["name"]);
        $modelPath = $targetDir . $modelName;
        move_uploaded_file($_FILES["model"]["tmp_name"], $modelPath);
    }

    // ===== Pokud je nahrán nový thumbnail =====
    if (!empty($_FILES["thumbnail"]["name"])) {
        // smaž starý obrázek, pokud existuje
        if (file_exists($thumbPath)) {
            unlink($thumbPath);
        }
        $thumbName = basename($_FILES["thumbnail"]["name"]);
        $thumbPath = $targetDir . $thumbName;
        move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $thumbPath);
    }

    // aktualizace DB
    $update = $mysqli->prepare("UPDATE cards SET title=?, model_path=?, thumbnail_path=? WHERE id=?");
    $update->bind_param("sssi", $title, $modelPath, $thumbPath, $id);
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

        <!-- link -->
        <link rel="stylesheet" href="style/main.css">
        <link rel="stylesheet" href="style/style.css">
        <link rel="stylesheet" href="style/partials.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=chevron_backward,save" />

        <!-- title -->
        <title>Editovat</title>
    </head>
    <body>
        <section class="main main-form">
            <h1>Upravit model</h1>
            <form method="POST" enctype="multipart/form-data">
                <input class="text_input" type="text" name="title" value="<?= htmlspecialchars($row['title']) ?>" required>

                <label class="button-label" for="upload3d">Změnit 3D model (nepovinné):</label>
                <input type="file" id="upload3d" name="model" accept=".glb,.gltf,.obj,.fbx">

                <label class="button-label" for="upload">Změnit obrázek (nepovinné):</label>
                <input type="file" id="upload" name="thumbnail" accept="image/*">

                <div class="actions">
                    <a href="detail.php?id=<?= $id ?>" class="button"><span class="material-symbols-outlined">chevron_backward</span>Zpět na detail</a>
                    <button type="submit" class="button"><span class="material-symbols-outlined">save</span>Uložit změny</button>
                </div>
            </form>
        </section>
    </body>
</html>