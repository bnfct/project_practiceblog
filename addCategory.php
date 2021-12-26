<?php
    require_once("system/session.php");
    require_once("system/checks/check_login.php");

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        //we want matching id's to the article cover picture, so we get the latest id from the DB and adding +1 to it
        $row_id_sql = $conn->prepare("SELECT id+1 AS id FROM pb_categories ORDER BY id DESC LIMIT 1");
        $row_id_sql->execute();
        $result = $row_id_sql->get_result();
        $row_id = $result->fetch_assoc();
        //this "if" is usually used once, when the table is empty, it's automatically returns 1
        if ($row_id["id"] == "" || $row_id["id"] == null) {
            $row_id["id"] = 1;
        }
        //we are storing all article covers in one folder
        //any other pictures in the articles are stored separately in the user's directory
        $target_dir = "img/categories/";

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
                $newfilename = 'category'.$row_id["id"].'.' . end(explode('.',$_FILES["picture"]["name"]));
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
        $form_link = $_POST['link'];
        if(mb_strlen($form_link) > 100) {
            $form_link = "nem-fog-menni";
        }
        $form_picture = $sqlfilename;
        
        $sql_write = $conn->prepare("INSERT INTO `pb_categories`(`title`, `summary`, `picture`, `link`) VALUES (?, ?, ?, ?)");
        $sql_write->bind_param("ssss", $form_title, $form_summary, $form_picture, $form_link);
        if ($sql_write->execute() === TRUE) {
            header("Location: index.php");
        }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Új kategória hozzáadása / <?php echo $sitedatasql_data["sitename"]." ".$sitedatasql_data["siteversion"]; ?></title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="styles/main.css">
        <link rel="stylesheet" href="styles/form.css">
    </head>
    <body>
        <div class="main-contents">
            <?php
                include_once("components/header.php");
            ?>
            <form class="input-form" autocomplete="off" action="addCategory.php" method="post" enctype="multipart/form-data">
                <p class="input-title">Kategória</p>
                <input type="text" name="title" maxlength="100" placeholder="Kategória címe"><br>
                <p class="input-title">Leírás</p>
                <input type="text" name="summary" maxlength="250" placeholder="Kategória rövid leírása"><br>
                <p class="input-title">Kép</p>
                <input type="file" accept=".png, .jpg, .gif" name="picture"><br>
                <p class="input-title">Link</p>
                <input type="text" name="link" maxlength="100" placeholder="Link helye"><br>
                <button type="submit">Küldés</button>
            </form>
            <?php
                include_once("components/footer.php");
            ?> 
        </div>     
    </body>
</html>
