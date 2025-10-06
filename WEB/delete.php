<?php
require "db.php";

if (!empty($_GET["id"])) {
    $id = (int)$_GET["id"];

    // nejdřív zjistit cesty k souborům
    $stmt = $mysqli->prepare("SELECT model_path, thumbnail_path FROM cards WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if ($row) {
        // smazat soubory
        if (file_exists($row["model_path"])) unlink($row["model_path"]);
        if (file_exists($row["thumbnail_path"])) unlink($row["thumbnail_path"]);

        // smazat záznam z DB
        $stmt = $mysqli->prepare("DELETE FROM cards WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}

header("Location: index.php");
exit;