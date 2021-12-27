<?php
    require("system/session.php");
    if(isset($_SESSION["login_user"])) {
        header("Location: /");
    }

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $form_username = $_POST["username"];
        $form_password = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $form_email = $_POST["email"];
        $form_displayname = $_POST["displayname"];
        $form_profilepic = "";
        $form_motto = "Lórum ipse";
        $form_role = 2;
        $form_active = 1;
        $form_regdate = date("Y-m-d H:i:s");

        $get_login = $conn->prepare("SELECT count(id) AS countid FROM pb_users WHERE username=?");
        $get_login->bind_param("s", $form_username);
        $get_login->execute();
        $result_login = $get_login->get_result();
        $row_login = $result_login->fetch_assoc();
        
        if($row_login["countid"] == 0) { 
            $userreg = $conn->prepare("INSERT INTO `pb_users`(`username`, `password`, `displayname`, `email`, `profilepic`, `motto`, `role`, `active`, `regdate`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $userreg->bind_param("ssssssiis", $form_username, $form_password, $form_displayname, $form_email, $form_profilepic, $form_motto, $form_role, $form_active, $form_regdate);
            if($userreg->execute() === true) {
                header("Location: login.php");
            }
        } else {
            $error = "Van már ilyen nevű felhasználónév!";
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Regisztráció / <?php echo $sitedatasql_data["sitename"]." ".$sitedatasql_data["siteversion"]; ?></title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="styles/main.css">
        <link rel="stylesheet" href="styles/form.css">
    </head>
    <body>
        <div class="main-contents">
            <?php
                include_once("components/header.php");
            ?>
            <form class="input-form login-form" autocomplete="off" action="registration.php" method="post" enctype="multipart/form-data">
                <?php
                    if(isset($error)) {
                        echo "<p class=\"error-title\">".$error."</p>";
                    }
                ?>
                <p class="input-title">Felhasználónév</p>
                <input type="text" name="username" maxlength="50" placeholder="Felhasználónév">
                <p class="input-title">Jelszó</p>
                <input type="password" name="password" placeholder="Jelszó">
                <p class="input-title">Email</p>
                <input type="text" name="email" maxlength="100" placeholder="Email cím">
                <p class="input-title">Kijelzett felhasználónév</p>
                <input type="text" name="displayname" maxlength="50" placeholder="Kijelzett felhasználónév">
                <button type="submit">Regisztrálás</button>
            </form>
            <?php
                include_once("components/footer.php");
            ?>
        </div>
    </body>
</html>

