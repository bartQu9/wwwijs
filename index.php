<html>
<head>
    <meta charset="UTF-8">
    <meta title="galeria">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="lib/dropzone.css" crossorigin="anonymous">
    <link rel="stylesheet" href="moje.css" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.5.1.js"
            integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
            integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
            crossorigin="anonymous"></script>

    <script src="lib/dropzone.js" crossorigin="anonymous"></script>
    <script src="handle.js" crossorigin="anonymous"></script>

</head>
<body>


<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Galeria zdjęć</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
<!--            <li class="nav-item active">-->
<!--                <a class="nav-link" href="upload.php">Wyślij <span class="sr-only">(current)</span></a>-->
<!---->
<!--            </li>-->
        </ul>
    </div>
</nav>
<div class="container">

    <?php
    try {
        $dbh = new PDO('mysql:host=localhost;dbname=wwwijs;charset=utf8', 'wwwijs', 'wwwijs');
        $result = $dbh->query('SELECT `id`,`title`,`filename`, `description` FROM `photos`;')->fetchAll();
        $idx = 0;
        foreach ($result as $row) {
            echo <<<END
<div id="img-db-id-{$row["id"]}" class="modal fade img-show-{$idx}" tabindex="-1" role="dialog" aria-labelledby="{$row["title"]}" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">{$row["title"]}</h5>
        <button type="button" class="btn btn-sm btn-success" aria-label="Edytuj" onclick="on_click_rename({$row["id"]})">Edytuj</button>
        <button type="button" class="btn btn-sm btn-danger del-img-btn" aria-label="Usuń" onclick="delete_photo({$row["id"]})">Usuń</button>
<!--        <div id="img_categories_div" class="dropdown">-->
<!--            <button class="btn btn-secondary dropdown-toggle" type="button" id="img_categories" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
<!--            Dropdown button-->
<!--            </button>-->
<!--            <div class="dropdown-menu" aria-labelledby="img_categories">-->
<!--&lt;!&ndash;                <a class="dropdown-item" href="#">Action</a>&ndash;&gt;-->
<!--&lt;!&ndash;                <a class="dropdown-item" href="#">Another action</a>&ndash;&gt;-->
<!--&lt;!&ndash;                <a class="dropdown-item" href="#">Something else here</a>&ndash;&gt;-->
<!--            </div>-->
<!--        </div>-->
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body ">
        <img onContextMenu="return false;" src="static/{$row["filename"]}" class="img-fluid mx-auto d-block">
      </div>
      <div class="modal-footer">
      <p>{$row["description"]}</p>
           <button type="button" class="btn btn-sm btn-primary" aria-label="Edytuj" onclick="on_click_change_desc({$row["id"]})">Edytuj</button> 
      </div>
    </div>
  </div>
</div>
END;
            $idx++;
        }

        echo '<div class="row row-cols-4">';
        $idx2 = 0;
        foreach ($result as $row) {

            echo '<div class="col">';

            echo $row["title"];
            echo "<input type=\"image\" onContextMenu=\"return false;\" src=\"static/{$row["filename"]}\" class=\"img-thumbnail\" data-toggle=\"modal\" data-target=\".img-show-{$idx2}\">";
            echo '</div>';
            $idx2++;
        }
        echo '</div>';


        $dbh = null;
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
    ?>

    <form action="upload.php" class="dropzone" id="draganddrop">
        <div class="fallback">
            <input name="file" type="file"/>
        </div>
    </form>

    <div id="rename_modal" class="modal fade rename_modal" tabindex="-1" role="dialog" aria-labelledby=""
         aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Zmień nazwe</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body ">
                    <input id="newtitle_input" type="text">
                </div>
                <div class="modal-footer">
                    <button id="rename_save_btn" type="button" class="btn btn-sm btn-success" aria-label="Zapisz">
                        Zapisz
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="change_desc_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby=""
         aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Zmień opis</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body ">
                    <input id="new_desc_input" type="text">
                </div>
                <div class="modal-footer">
                    <button id="change_desc_save_btn" type="button" class="btn btn-sm btn-success" aria-label="Zapisz">
                        Zapisz
                    </button>
                </div>
            </div>
        </div>
    </div>




</div>
</body>
</html>