<?php
    include_once("system/connection.php");
    $get_link_id = $_GET["id"];
    $get_article = $conn->prepare("SELECT pb_articles.id, pb_articles.title, pb_articles.summary, pb_articles.author, pb_articles.published, pb_articles.picture, pb_articles.content, pb_articles.link, pb_categories.title AS categoryname, pb_categories.id AS categoryid FROM pb_articles INNER JOIN pb_categories ON pb_articles.category = pb_categories.id INNER JOIN pb_users ON pb_articles.author = pb_users.id WHERE pb_articles.id = ?");
    $get_article->bind_param("i", $get_link_id);
    $get_article->execute();
    $result_article = $get_article->get_result();
    $row_article = $result_article->fetch_assoc();
    

    $get_categories = $conn->prepare("SELECT * FROM pb_categories");
    $get_categories->execute();
    $categories_result = $get_categories->get_result();

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $target_dir = "img/articles/";

        //uploading a picture is not required, so we are checking here is there's an image present at the upload or not
        if ($_FILES["picture"]["name"] == NULL) {
            $sqlfilename = $row_article["picture"];
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
                $newfilename = 'article'.$row_article["id"].'.' . end(explode('.',$_FILES["picture"]["name"]));
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
        $form_published = $row_article["published"];
        $form_author = $row_article["author"];
        $form_hidden = 0;
        $form_picture = $sqlfilename;
        $form_content = $_POST['content'];

        $sql_write = $conn->prepare("UPDATE `pb_articles` SET `title`= ?,`summary`= ?,`published`= ?,`category`= ?,`author`= ?,`picture`= ?,`content`= ?,`link`= ?,`hidden`= ? WHERE id = ?");
        $sql_write->bind_param("sssiisssii", $form_title, $form_summary, $form_published, $form_category, $form_author, $form_picture, $form_content, $form_link, $form_hidden, $get_link_id);
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
        <form autocomplete="off" method="post" enctype="multipart/form-data">
            <input type="text" name="title" maxlength="100" placeholder="Cikk címének helye" value="<?php echo $row_article["title"];?>"><br>
            <input type="text" name="summary" maxlength="250" placeholder="Rövid leírása a cikknek" value="<?php echo $row_article["summary"];?>"><br>
            <select name="category">
                <option value="<?php echo $row_article["categoryid"] ?>" selected><?php echo $row_article["categoryname"] ?></option>
                <option disabled>-----</option>
                <?php
                    while($row = $categories_result->fetch_assoc()) {
                        echo "<option value=\"".$row["id"]."\">".$row["title"]."</option>";
                    }
                ?>
             </select><br>
             <input type="file" accept=".png, .jpg, .gif" name="picture"><br>
             <?php
                if (mb_strlen($row_article["picture"]) > 3) {
                    echo "<img src=\"".$row_article["picture"]."\"/>";
                }
             ?>
             <textarea name="content"><?php echo $row_article["content"];?></textarea><br>
             <input type="text" name="link" maxlength="100" placeholder="Link helye" value="<?php echo $row_article["link"];?>"><br>
             <button type="submit">Küldés</button>
        </form>
    </body>
</html>
