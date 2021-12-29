<?php
    require("system/session.php");
    if(isset($_SESSION["login_user"])) {
        header("Location: /");
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Bejelentkezés / <?php echo $sitedatasql_data["sitename"]." ".$sitedatasql_data["siteversion"]; ?></title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="/styles/main.css">
        <link rel="stylesheet" href="/styles/form.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <div class="main-contents">
            <?php
                include_once("components/header.php");
            ?>
            <h3 class="check-email">A regisztráció befejezéséhez kérjük ellenőrizd az email fiókod!</h3>
            <?php
                include_once("components/footer.php");
            ?>
        </div>
    </body>
</html>