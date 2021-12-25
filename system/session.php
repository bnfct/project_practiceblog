<?php
    require("connection.php");
    session_start();

    if(isset($_SESSION["login_user"])) {
        $user_check=$_SESSION["login_user"];

        $get_user = $conn->prepare("SELECT username, displayname, role FROM pb_users WHERE username=?");
        $get_user->bind_param("s", $user_check);
        $get_user->execute();
        $result_user = $get_user->get_result();
        $row_user = $result_user->fetch_assoc();
        $login_session_username = $row_user["username"];
        $login_session_displayname = $row_user["displayname"];
        $login_session_role = $row_user["role"];
    }
?>