<!DOCTYPE html>
<html lang="en">
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
        <title>3D modely</title>
    </head>
    <body>
        <!-- Header -->
        <header>
            <?php
                include "partials/header.php";
            ?>
        </header>

        <!-- Main -->
        <section class="main">
            <h1>Galerie 3D objektů</h1>
            <a href="upload.php" class="button">Přidat nový model</a>

            <div id="cards-container">
                <?php
                    require "db.php";
                    $result = $mysqli->query("SELECT * FROM cards ORDER BY id DESC");
                    if (!$result) {
                        die("Chyba v dotazu: " . $mysqli->error);
                    }

                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="card">';
                        echo '<a href="detail.php?id='.$row['id'].'">';
                        echo '<img src="'.$row['thumbnail_path'].'" alt="náhled">';
                        echo '<div class="card-name">'.htmlspecialchars($row['title']).'</div>';
                        echo '</a>';
                        echo '<div><a class="button delete" href="delete.php?id='.$row['id'].'" onclick="return confirm(\'Opravdu smazat?\')">🗑️ Odstranit</a></div>';
                        echo '</div>';
                    }
                    $result->free();

                /* Moznost zobrazení s model-viewer
                    require "db.php";
                    $result = $mysqli->query("SELECT * FROM cards ORDER BY id DESC");
                    if ($result) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="card">';
                            echo '<model-viewer src="'.$row['model_path'].'" alt="Preview" camera-controls auto-rotate></model-viewer>';
                            echo '<a href="detail.php?id='.$row['id'].'">';
                            echo '<div class="card-name">'.htmlspecialchars($row['title']).'</div>';
                            echo '</a>';
                            echo '<div><a class="button delete" href="delete.php?id='.$row['id'].'" onclick="return confirm(\'Opravdu smazat?\')">🗑️ Odstranit</a></div>';
                            echo '</div>';
                        }
                        $result->free();
                    }*/
                ?>
            </div>
        </section>


        <!-- Footer -->
        <footer>
            <?php
                include "partials/footer.php";
            ?>
        </footer>

        <!-- scripts -->
        <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.3.0/model-viewer.min.js"></script>
        <script src="./js/nav.js"></script>
        <script src="./js/loading.js"></script>
        <script src="./js/three.js" type="module"></script>
    </body>
</html>