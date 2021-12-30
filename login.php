<?php
    require("system/session.php");
    if(isset($_SESSION["login_user"])) {
        header("Location: /");
    }

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $form_username = $_POST["username"];
        $form_password = $_POST["password"];

        $get_exist = $conn->prepare("SELECT count(id) AS countid FROM pb_users WHERE username=?");
        $get_exist->bind_param("s", $form_username);
        $get_exist->execute();
        $result_exist = $get_exist->get_result();
        $row_exist = $result_exist->fetch_assoc();
        if ($row_exist["countid"] == 1) {
            $get_login = $conn->prepare("SELECT username, password, active FROM pb_users WHERE username=?");
            $get_login->bind_param("s", $form_username);
            $get_login->execute();
            $result_login = $get_login->get_result();
            $row_login = $result_login->fetch_assoc();

            if ($row_login["active"] == 1) {
                if (mb_strlen($row_login["password"]) < 2) {
                    $row_login["password"] = "";
                }
            
                if(password_verify($form_password, $row_login["password"])) {
                    $_SESSION["login_user"] = $form_username;
                    header("Location: /");
                } else {
                    $error = "Nem megfelelő belépési adatok!";
                } 
            } else {
                $error = "Ez a felhasználói fiók még nem került aktiválásra!";
            }
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
        <link rel="stylesheet" href="/styles/main.css">
        <link rel="stylesheet" href="/styles/form.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body onload="loginCheck()">
        <div class="main-contents">
            <?php
                include_once("components/header.php");
            ?>
            <form class="input-form login-form" autocomplete="off" action="login.php" method="post" enctype="multipart/form-data">
                <?php
                    if(isset($error)) {
                        echo "<p class=\"error-title\">".$error."</p>";
                    }
                    if(isset($_GET["login"])) {
                        if ($_GET["login"] == "success") {
                            echo "<p class=\"reg-title\">Sikeres regisztráció!</p>";
                        }
                    }
                ?>
                <p class="input-title">Felhasználónév</p>
                <input type="text" name="username" id="username" oninput="loginCheck()" maxlength="50" placeholder="Felhasználónév">
                <p class="input-title">Jelszó</p>
                <input type="password" name="password" id="password" oninput="loginCheck()" placeholder="Jelszó">
                <button type="submit" id="login_button" disabled>Belépés</button>
            </form>
            <?php
                include_once("components/footer.php");
            ?>
        </div>
    </body>
    <script src="/scripts/login_check.js" ></script>
</html>