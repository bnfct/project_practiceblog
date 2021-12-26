<?php
include_once("system/session.php");
$get_articles = $conn->prepare("SELECT pb_articles.id, pb_articles.title, pb_articles.summary, date_format(pb_articles.published, \"%Y-%m-%d %H:%i\") AS published, pb_articles.picture, pb_articles.content, pb_articles.link, pb_users.displayname, pb_categories.title AS categoryname FROM pb_articles INNER JOIN pb_categories ON pb_articles.category = pb_categories.id INNER JOIN pb_users ON pb_articles.author = pb_users.id WHERE pb_articles.hidden=0 ORDER BY pb_articles.id DESC");
$get_articles->execute();
$articles_result = $get_articles->get_result();
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $sitedatasql_data["sitename"]." ".$sitedatasql_data["siteversion"]; ?></title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="styles/main.css">
    </head>

    <body>
        <div class="main-contents">
            <?php
                include_once("components/header.php");
            ?>
            <div class="articles-container">
                <?php
                    if($articles_result->num_rows>0) {
                        while($row=$articles_result->fetch_assoc()) {
                            echo "<div class=\"article\">";
                                echo "<div class=\"article-header\"><a class=\"article-title\" href=\"article.php?id=".$row["id"]."\">".$row["title"]."</a>";
                                echo "<p class=\"article-data\">";
                                    echo "<span>".$row["published"]."</span>";
                                    echo "<span class=\"separator\">::</span>";
                                    echo "<span>".$row["categoryname"]."</span>";
                                    echo "<span class=\"separator\">::</span>";
                                    echo "<span>".$row["displayname"]."</span>";
                                    if(isset($_SESSION["login_user"])) {
                                        echo "<span class=\"separator\">::</span>";
                                        echo "<a href=\"editArticle.php?id=".$row["id"]."\">Cikk szerkesztése</a>";
                                    }
                                echo "</p></div>";
                                echo "<div class=\"article-content\"><img class=\"article-image\" src=\"".$row["picture"]."\"/>";
                                echo "<p class=\"article-summary\">".$row["summary"]."</p></div>"; 
                            echo "</div>";
                        }
                    }
                ?>    
            </div>
                <?php
                include_once("components/footer.php");
                ?>  
        </div>          
    </body>

</html>