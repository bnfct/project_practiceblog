<header>
    <h1><?php echo $sitedatasql_data["sitename"]." ".$sitedatasql_data["siteversion"]; ?></h1>
    <?php
    if(isset($_SESSION["login_user"])) {
        echo "<p>Üdv ".$login_session_displayname."!</p>";
        echo "<div class=\"header-links\">";
            echo "<a href=\"index.php\">Főoldal</a>";
            echo "<a href=\"addArticle.php\">Cikk hozzáadása</a>";
            echo "<a href=\"addCategory.php\">Kategória hozzáadása</a>";
            echo "<a href=\"logout.php\">Kijelentkezés</a>";
        echo "</div>";
    } else {
        echo "<div class=\"header-links\">";
            echo "<a href=\"login.php\">Bejelentkezés</a>";
            echo "<a href=\"registration.php\">Regisztráció</a>";
        echo "</div>";
    }
    ?>
</header>