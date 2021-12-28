<?php
include_once("system/session.php");
$get_link = $_GET["link"];
$get_articles = $conn->prepare("SELECT pb_articles.id, pb_articles.title, pb_articles.summary, date_format(pb_articles.published, \"%Y-%m-%d %H:%i\") AS published, pb_articles.picture, pb_articles.content, pb_articles.link, pb_users.displayname, pb_categories.title AS categoryname FROM pb_articles INNER JOIN pb_categories ON pb_articles.category = pb_categories.id INNER JOIN pb_users ON pb_articles.author = pb_users.id WHERE pb_articles.hidden=0 AND pb_articles.link=?");
$get_articles->bind_param("s",$get_link);
$get_articles->execute();
$articles_result = $get_articles->get_result();
$row = $articles_result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $row["title"];?> / <?php echo $sitedatasql_data["sitename"]." ".$sitedatasql_data["siteversion"]; ?></title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="/styles/main.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta property="og:title" content="<?php echo htmlspecialchars($row["title"]);?> / <?php echo $sitedatasql_data["sitename"]." ".$sitedatasql_data["siteversion"]; ?>">
        <meta property="og:type" content="website">
        <meta property="og:image" content="https://www.test.benfact.hu/<?php echo $row["picture"]; ?>">
        <meta property="og:description" content="<?php echo htmlspecialchars($row["summary"]); ?>">
        <meta name="Description" content="<?php echo htmlspecialchars($row["summary"]); ?>"> 
    </head>

    <body>
        <div class="main-contents">
            <?php
                include_once("components/header.php");
            ?>
            <div class="articles-container">
                <?php
                echo "<div class=\"article\">";
                    echo "<div class=\"article-header\"><h2>".$row["title"]."</h2>";
                    echo "<p class=\"article-data\">";
                        echo "<span>".$row["published"]."</span>";
                        echo "<span class=\"separator\">::</span>";
                        echo "<span>".$row["categoryname"]."</span>";
                        echo "<span class=\"separator\">::</span>";
                        echo "<span>".$row["displayname"]."</span>";
                        if(isset($_SESSION["login_user"])) {
                            echo "<span class=\"separator\">::</span>";
                            echo "<a href=\"/editArticle/".$row["id"]."\">Cikk szerkesztése</a>";
                        }
                    echo "</p></div>";
                    echo "<div class=\"article-content\">";
                    if(mb_strlen($row["picture"]) > 2) {
                        echo "<img class=\"article-image\" src=\"/".$row["picture"]."\"/>";
                    } else {
                        echo "<img class=\"article-image\" src=\"/img/nopic.jpg\"/>";
                    }
                    echo "<p class=\"article-summary\">".$row["summary"]."</p></div>"; 
                    echo "<pre>".$row["content"]."</pre>";
                echo "</div>";
                ?>    
            </div>
                <?php
                include_once("components/footer.php");
                ?>  
        </div>          
    </body>

</html>