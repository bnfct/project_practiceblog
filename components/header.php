<header>
    <h1><?php echo $sitedatasql_data["sitename"]." ".$sitedatasql_data["siteversion"]; ?></h1>
    <?php
    if(isset($_SESSION["login_user"])) {
        echo "<p>Üdv ".$login_session_displayname."!</p>";
        echo "<div class=\"header-links\">";
            echo "<a href=\"/\">Főoldal</a>";
            echo "<a href=\"/addArticle\">Cikk hozzáadása</a>";
            if ($login_session_role == 1)  {
                echo "<a href=\"/addCategory\">Kategória hozzáadása</a>";
            }
            echo "<a href=\"/logout\">Kijelentkezés</a>";
        echo "</div>";
    } else {
        echo "<div class=\"header-links\">";
            echo "<a href=\"/\">Főoldal</a>";
            echo "<a href=\"/login\">Bejelentkezés</a>";
            echo "<a href=\"/registration\">Regisztráció</a>";
        echo "</div>";
    }
    ?>
</header>