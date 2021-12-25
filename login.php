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
        
        if($row_login["password"] == $form_password) {
            $_SESSION["login_user"] = $form_username;
            header("Location: index.php");
        } else {
            echo "Nem megfelelő jelszó!";
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Bejelentkezés / <?php echo $sitedatasql_data["sitename"]." ".$sitedatasql_data["siteversion"]; ?></title>
        <meta charset="UTF-8">
    </head>
    <body>
        <form autocomplete="off" action="login.php" method="post" enctype="multipart/form-data">
            <input type="text" name="username" maxlength="50" placeholder="Felhasználónév">
            <input type="password" name="password" placeholder="Jelszó">
            <button type="submit">Belépés</button>
        </form>
    </body>
</html>