<?php
    require("system/session.php");
    if(isset($_SESSION["login_user"])) {
        header("Location: /");
    }

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $form_username = $_POST["username"];
        $form_username = str_replace(' ', '-', $form_username);
        $form_username = preg_replace('/[^A-Za-z0-9\-]/', '', $form_username);
        $form_password = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $form_email = $_POST["email"];
        $form_displayname = $_POST["displayname"];
        $form_profilepic = "";
        $form_motto = "Lórum ipse";
        $form_role = 2;
        $form_active = 0;
        $form_regdate = date("Y-m-d H:i:s");

        function generateRandomString() {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < 32; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }
        $form_code = generateRandomString();

        $get_login = $conn->prepare("SELECT count(id) AS countid FROM pb_users WHERE username=?");
        $get_login->bind_param("s", $form_username);
        $get_login->execute();
        $result_login = $get_login->get_result();
        $row_login = $result_login->fetch_assoc();
        
        if($row_login["countid"] == 0) { 
            $userreg = $conn->prepare("INSERT INTO `pb_users`(`username`, `password`, `displayname`, `email`, `profilepic`, `motto`, `role`, `active`, `regdate`,`code`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $userreg->bind_param("ssssssiiss", $form_username, $form_password, $form_displayname, $form_email, $form_profilepic, $form_motto, $form_role, $form_active, $form_regdate, $form_code);
            if($userreg->execute() === true) {
                $subject = "Aktiváld a PracticeBlog regisztrációdat";
                $message = "
                <html>
                <head>
                <title>Aktiváld a PracticeBlog regisztrációdat</title>
                </head>
                <body>
                <div style=\"max-width:800px;margin:auto;\">
                <h2 style=\"text-align:center;\">Kedves júzer!</h2>
                <h3 style=\"text-align:center;\">A regisztráció befejezéséhez kérjük kattints az alábbi linkre:</h3>
                <a style=\"display:block;margin:auto;width:max-content;\" href=\"http://test.benfact.hu/activateReg/".$form_code."\">Kattints ide a regisztrációhoz!</a>
                <p style=\"text-align:center;\">Üdvözlettel,<br>PracticeBlog.</p>
                </div>
                </body>
                </html>
                ";
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: "PracticeBlog" <noreply@benfact.hu>' . "\r\n";
                mail($form_email,$subject,$message,$headers);
                header("Location: /checkEmail");
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
        <meta name="viewport" content="width=device-width, initial-scale=1">
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
<?php
    $conn->close();
?>

