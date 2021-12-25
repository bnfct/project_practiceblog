<?php
    require_once("system/session.php");
    require_once("system/checks/check_login.php");

    $get_categories = $conn->prepare("SELECT * FROM pb_categories");
    $get_categories->execute();
    $categories_result = $get_categories->get_result();

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        //we want matching id's to the article cover picture, so we get the latest id from the DB and adding +1 to it
        $row_id_sql = $conn->prepare("SELECT id+1 AS id FROM pb_articles ORDER BY id DESC LIMIT 1");
        $row_id_sql->execute();
        $result = $row_id_sql->get_result();
        $row_id = $result->fetch_assoc();
        //this "if" is usually used once, when the table is empty, it's automatically returns 1
        if ($row_id["id"] == "" || $row_id["id"] == null) {
            $row_id["id"] = 1;
        }
        //we are storing all article covers in one folder
        //any other pictures in the articles are stored separately in the user's directory
        $target_dir = "img/articles/";

        //uploading a picture is not required, so we are checking here is there's an image present at the upload or not
        if ($_FILES["picture"]["name"] == NULL) {
            $sqlfilename = NULL;
        } else {
            //we are doing a file check here
            //we are checking if the file is a jpg or png or gif
            $is_image = in_array(exif_imagetype($_FILES["picture"]["tmp_name"]), array(IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF));
            $is_small = false;
            //checking for 2200kb (~2mb) size, we let the user have a little overhead on the file sizes
            //why 2megs on a small file like this? Because gifs are cool tho
            if (filesize($_FILES["picture"]["tmp_name"]) < 2200000) {
                $is_small = true;
            }
            //if one of the file types is true and it's below 2200kb, we are uploading the file
            if ($is_image && $is_small) {
                //if yes, we are renaming the file to, example: stream1.jpg
                $newfilename = 'article'.$row_id["id"].'.' . end(explode('.',$_FILES["picture"]["name"]));
                $sqlfilename = $target_dir . $newfilename;
                $sqlfilename = strtolower($sqlfilename);
                move_uploaded_file($_FILES["picture"]["tmp_name"], $sqlfilename);
            }
        } 

        $form_title = $_POST['title'];
        if(mb_strlen($form_title) > 100) {
            $form_title = "Nem fog menni.";
        }
        $form_summary = $_POST['summary'];
        if(mb_strlen($form_summary) > 250) {
            $form_summary = "Nem fog menni.";
        }
        $form_category = $_POST['category'];
        if(intval($form_category) == 0 ) {
            $form_category = 1;
        }
        $form_link = $_POST['link'];
        if(mb_strlen($form_link) > 100) {
            $form_link = "nem-fog-menni";
        }
        $form_published = date("Y-m-d H:i:s");
        $form_author = 1;
        $form_hidden = 0;
        $form_picture = $sqlfilename;
        $form_content = $_POST['content'];

        $sql_write = $conn->prepare("INSERT INTO `pb_articles`(`title`, `summary`, `published`, `category`, `author`, `picture`, `content`, `link`, `hidden`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $sql_write->bind_param("sssiisssi", $form_title, $form_summary, $form_published, $form_category, $form_author, $form_picture, $form_content, $form_link, $form_hidden);
        if ($sql_write->execute() === TRUE) {
            header("Location: index.php");
        }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Új cikk / <?php echo $sitedatasql_data["sitename"]." ".$sitedatasql_data["siteversion"]; ?></title>
        <meta charset="UTF-8">
    </head>
    <body>
        <form autocomplete="off" action="addArticle.php" method="post" enctype="multipart/form-data">
            <input type="text" name="title" maxlength="100" placeholder="Cikk címének helye"><br>
            <input type="text" name="summary" maxlength="250" placeholder="Rövid leírása a cikknek"><br>
            <select name="category">
                <?php
                    while($row = $categories_result->fetch_assoc()) {
                        echo "<option value=\"".$row["id"]."\">".$row["title"]."</option>";
                    }
                ?>
             </select><br>
             <input type="file" accept=".png, .jpg, .gif" name="picture"><br>
             <textarea name="content"></textarea><br>
             <input type="text" name="link" maxlength="100" placeholder="Link helye"><br>
             <button type="submit">Küldés</button>
        </form>
    </body>
</html>
