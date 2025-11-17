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
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=add,delete,edit" />

        <!-- title -->
        <title>3D modely</title>
    </head>
    <body>
        <!-- Header
        <header>
            <?php
              /*  include "partials/header.php";*/
            ?>
        </header>-->

        <!-- Main -->
        <section class="main">
            <h1>Galerie 3D objektů</h1>
            <a href="upload.php" class="button"><span class="material-symbols-outlined">add</span>Přidat nový model</a>

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
                        echo '<div class=actions><a class="delete" href="delete.php?id='.$row['id'].'" onclick="return confirm(\'Opravdu smazat?\')"><span class="material-symbols-outlined">delete</span></a>';
                        echo '<a class="edit" href="edit.php?id='.$row['id'].'"><span class="material-symbols-outlined">edit</span></a></div>';
                        echo '</div>';
                    }
                    $result->free();
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