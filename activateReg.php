<?php
    require("system/session.php");
    if(isset($_SESSION["login_user"])) {
        header("Location: /");
    }
    if(isset($_GET["code"])) {
        $form_activate = 1;
        $form_code = $_GET["code"];
        $sql_write = $conn->prepare("UPDATE `pb_users` SET `active`= ? WHERE code = ?");
        $sql_write->bind_param("is", $form_activate, $form_code);
        if ($sql_write->execute() === TRUE) {
            header("Location: /login/success");
        }
    } else {
        header("Location: /");
    }
?>