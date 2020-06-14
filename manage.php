<?php
function db_init()
{
    try {
        $dbh = new PDO('mysql:host=localhost;dbname=wwwijs;charset=utf8', 'wwwijs', 'wwwijs');
        return $dbh;
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        error_log(print_r($e, TRUE));
        http_response_code(200);
    }
}

if (isset($_POST["action"]) && $_POST["action"] == "rename") {
    isset($_POST["id"], $_POST["newname"]) ?: die();
    $dbh = db_init();
    $sql = "UPDATE photos SET title = ? WHERE id=?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$_POST["newname"], $_POST["id"]]);
    http_response_code(200);
}

if (isset($_POST["action"]) && $_POST["action"] == "change_desc") {
    isset($_POST["id"], $_POST["newdesc"]) ?: die();
    $dbh = db_init();
    $sql = "UPDATE photos SET description = ? WHERE id=?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$_POST["newdesc"], $_POST["id"]]);
    http_response_code(200);
}

if (isset($_POST["action"]) && $_POST["action"] == "delete") {
    //error_log(print_r($_POST["id"], TRUE));
    isset($_POST["id"]) ?: die();
    $dbh = db_init();
    $sql = "DELETE FROM photos WHERE id=?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$_POST["id"]]);
    http_response_code(200);
}

if (isset($_POST["action"]) && $_POST["action"] == "add_category") {
    isset($_POST["category_name"]) ?: die();
    $dbh = db_init();
    $sql = "INSERT INTO categories (name) VALUES (?);";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$_POST["category_name"]]);
    http_response_code(200);
}

if(isset($_POST["action"]) && $_POST["action"] == "del_category"){
    isset($_POST["category_id"]) ? : die();
    $dbh = db_init();
    $sql = "DELETE FROM categories WHERE id=?;";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$_POST["category_id"]]);
    http_response_code(200);
}

if(isset($_POST["action"]) && $_POST["action"] == "add_photo_to_category"){
    isset($_POST["category_id"], $_POST["photo_id"]) ? : die();
    $dbh = db_init();
    $sql = "INSERT INTO photos_to_categories (photo_id, category_id) VALUES (?,?);";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$_POST["photo_id"], $_POST["category_id"]]);
    http_response_code(200);
}

if(isset($_POST["action"]) && $_POST["action"] == "del_photo_from_category"){
    isset($_POST["category_id"], $_POST["photo_id"]) ? : die();
    $dbh = db_init();
    $sql = "DELETE FROM photos_to_categories WHERE photo_id=? AND category_id=?;";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$_POST["photo_id"], $_POST["category_id"]]);
    http_response_code(200);
}

if(isset($_POST["action"]) && $_POST["action"] == "get_available_categories"){
    $dbh = db_init();
    $result = $dbh->query('SELECT `title` FROM `categories`;')->fetchAll();
    $categories = [];
    foreach ($result as $row){
        array_push($categories, $row);
    }
    $jsoned = json_encode($categories);
    echo $jsoned;

    http_response_code(200);
}
