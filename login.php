<?php
    require("system/session.php");
    if(isset($_SESSION["login_user"])) {
        header("Location: index.php");
    }

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $form_username = $_POST["username"];
        $form_password = $_POST["password"];

        $get_login = $conn->prepare("SELECT username, password FROM pb_users WHERE username=?");
        $get_login->bind_param("s", $form_username);
        $get_login->execute();
        $result_login = $get_login->get_result();
        $row_login = $result_login->fetch_assoc();
        
        if(password_verify($form_password, $row_login["password"])) {
            $_SESSION["login_user"] = $form_username;
            header("Location: index.php");
        } else {
            $error = "Nem megfelelő belépési adatok!";
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Bejelentkezés / <?php echo $sitedatasql_data["sitename"]." ".$sitedatasql_data["siteversion"]; ?></title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="styles/main.css">
        <link rel="stylesheet" href="styles/form.css">
    </head>
    <body>
        <div class="main-contents">
            <?php
                include_once("components/header.php");
            ?>
            <form class="input-form login-form" autocomplete="off" action="login.php" method="post" enctype="multipart/form-data">
                <?php
                    if(isset($error)) {
                        echo "<p class=\"error-title\">".$error."</p>";
                    }
                ?>
                <p class="input-title">Felhasználónév</p>
                <input type="text" name="username" maxlength="50" placeholder="Felhasználónév">
                <p class="input-title">Jelszó</p>
                <input type="password" name="password" placeholder="Jelszó">
                <button type="submit">Belépés</button>
            </form>
            <?php
                include_once("components/footer.php");
            ?>
        </div>
    </body>
</html>