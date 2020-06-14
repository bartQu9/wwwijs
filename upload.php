<html>
<head>
    <meta charset="UTF-8">
    <meta title="wyślij zdjęcie">
</head>
<body>
<?php
if($_FILES["file"]["error"] == 0 && $_FILES["file"]["size"] > 0) {
    isset($_POST["title"]) ? $filename=preg_replace("/[^A-Za-z0-9]/", '', $_POST["title"]) : $filename=$_FILES["file"]["name"];
    isset($_POST["title"]) ? $title = $_POST["title"] : $title=$filename;
    isset($_POST["description"]) ? $description = $_POST["description"] : $description = "Brak opisu";


    $rand = random_int(1, PHP_INT_MAX);
    $filename = $filename . $rand;
    $filepath = "static/".$filename;
    //zapis pliku z losowym suffixem
    if (!move_uploaded_file($_FILES['file']['tmp_name'], $filepath)) {
        echo "<br>błąd zapisu do pliku!<br>";
        die();
    }

    try {
        $dbh = new PDO('mysql:host=localhost;dbname=wwwijs;charset=utf8', 'wwwijs', 'wwwijs');
        $sql = "INSERT INTO photos (title, description , filename) VALUES (?,?,?)";
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$title, $description, $filename]);

        $dbh = null;
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();


    }
    header('Location: https://rudnicki.szczecin.pl/wwwijs');
}
?>
<div class="form_send">
    <form action="/wwwijs/upload.php" method="post" enctype="multipart/form-data">

        <label for="title">Tytuł:</label><br>
        <input type="text" id="title" name="title" value="John"><br>
        <label for="description">Opis</label><br>
        <input type="text" id="description" name="description" value=""><br><br>
        <label for="file">Select a file:</label>
        <input type="file" id="file" name="file"><br><br>
        <input type="submit" value="Wyślij">

    </form>
</div>
</body>


</html>