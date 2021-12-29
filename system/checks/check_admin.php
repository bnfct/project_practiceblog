<?php
    $get_admin = $conn->prepare("SELECT role FROM pb_users WHERE username=?");
    $get_admin->bind_param("s", $login_session_username);
    $get_admin->execute();
    $result_admin = $get_admin->get_result();
    $row_admin = $result_admin->fetch_assoc();

    if ($row_admin["role"] != 1) {
        header("Location: /");
    }
?>