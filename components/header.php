<header>
    <h1><?php echo $sitedatasql_data["sitename"]." ".$sitedatasql_data["siteversion"]; ?></h1>
    <?php
    if(isset($_SESSION["login_user"])) {
        echo "Üdv ".$login_session_displayname."!";
        echo "<a href=\"addArticle.php\">Cikk hozzáadása</a>";
        echo "<a href=\"logout.php\">Kijelentkezés</a>";
    } else {
        echo "<a href=\"login.php\">Bejelentkezés</a>";
    }
    ?>
</header>