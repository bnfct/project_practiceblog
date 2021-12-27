<?php
    require_once("system/session.php");
    require_once("system/checks/check_login.php");
    $get_link_id = $_GET["id"];

    $get_article_exist = $conn->prepare("SELECT count(id) as countid FROM pb_articles WHERE id=?");
    $get_article_exist->bind_param("i", $get_link_id);
    $get_article_exist->execute();
    $result_article_exist = $get_article_exist->get_result();
    $row_article_exist = $result_article_exist->fetch_assoc();
    
    if ($row_article_exist["countid"] == 0) {
        header("Location: /");
    }

    $get_article = $conn->prepare("SELECT pb_articles.id, pb_articles.title, pb_articles.summary, pb_articles.author, pb_articles.published, pb_articles.picture, pb_articles.content, pb_articles.link, pb_articles.hidden, pb_categories.title AS categoryname, pb_categories.id AS categoryid FROM pb_articles INNER JOIN pb_categories ON pb_articles.category = pb_categories.id INNER JOIN pb_users ON pb_articles.author = pb_users.id WHERE pb_articles.id = ?");
    $get_article->bind_param("i", $get_link_id);
    $get_article->execute();
    $result_article = $get_article->get_result();
    $row_article = $result_article->fetch_assoc();
    
    if ($row_article["hidden"] == 1) {
        header("Location: /");
    }

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
            header("Location: /");
        }
    }

    if (isset($_POST['delete'])) {
        $form_hidden = 1;
        $sql_write = $conn->prepare("UPDATE `pb_articles` SET `hidden`= ? WHERE id = ?");
        $sql_write->bind_param("ii", $form_hidden, $get_link_id);
        if ($sql_write->execute() === TRUE) {
            header("Location: /");
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Cikk szerkesztése / <?php echo $sitedatasql_data["sitename"]." ".$sitedatasql_data["siteversion"]; ?></title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="/styles/main.css">
        <link rel="stylesheet" href="/styles/form.css">
    </head>
    <body onload="editCheck()">
        <div class="main-contents">
            <?php
                include_once("components/header.php");
            ?>
            <form class="input-form" autocomplete="off" method="post" enctype="multipart/form-data">
                <p class="input-title">Cikk cím<span class="input-counter"><span id="title_counter">0</span>/<span id="title_max"></span></span></p>
                <input type="text" name="title" maxlength="100" placeholder="Cikk címének helye" id="title" oninput="linkGenerator();editCheck()" value="<?php echo $row_article["title"];?>"><br>
                <p class="input-title">Rövid leírás<span class="input-counter"><span id="summary_counter">0</span>/<span id="summary_max"></span></span></p>
                <input type="text" name="summary" maxlength="250" placeholder="Rövid leírása a cikknek" id="summary" oninput="editCheck()" value="<?php echo $row_article["summary"];?>"><br>
                <p class="input-title">Kategória</p>
                <select name="category">
                    <option value="<?php echo $row_article["categoryid"] ?>" selected><?php echo $row_article["categoryname"] ?></option>
                    <option disabled>-----</option>
                    <?php
                        while($row = $categories_result->fetch_assoc()) {
                            echo "<option value=\"".$row["id"]."\">".$row["title"]."</option>";
                        }
                    ?>
                </select><br>
                <p class="input-title">Kép</p>
                <input type="file" accept=".png, .jpg, .gif" name="picture"><br>
                <?php
                    if (mb_strlen($row_article["picture"]) > 3) {
                        echo "<img src=\"/".$row_article["picture"]."\"/>";
                    }
                ?>
                <p class="input-title">Tartalom</p>
                <textarea name="content"><?php echo $row_article["content"];?></textarea><br>
                <p class="input-title">Link<span class="input-counter"><span id="link_counter">0</span>/<span id="link_max"></span></span></p>
                <input type="text" name="link" maxlength="100" placeholder="Link helye" id="link" oninput="editCheck()" value="<?php echo $row_article["link"];?>"><br>
                <button type="submit" id="edit_button" disabled>Szerkesztés</button>
                <button name="delete">Törlés</button>
            </form>
            <?php
                include_once("components/footer.php");
            ?>
        </div>    
    </body>
    <script src="/scripts/editArticle_check.js"></script>
</html>
