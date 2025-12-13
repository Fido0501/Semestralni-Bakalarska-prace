<?php
// Načtení připojení k DB
require "db.php";

// Pokud je v GET id, převede ho na int a pokračuje
if (!empty($_GET["id"])) {
    $id = (int)$_GET["id"]; // bezpečné přiřazení id jako integer

    // nejdřív zjistit cesty k souborům v DB pro dané id
    $stmt = $mysqli->prepare("SELECT model_path, thumbnail_path FROM cards WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if ($row) {
        // pokud soubory existují, smazat je z filesystemu
        if (file_exists($row["model_path"])) unlink($row["model_path"]);
        if (file_exists($row["thumbnail_path"])) unlink($row["thumbnail_path"]);

        // smazat záznam z DB pro dané id
        $stmt = $mysqli->prepare("DELETE FROM cards WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}

// přesměrování zpět na index po dokončení
header("Location: index.php");
exit;
?>