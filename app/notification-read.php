<?php
	session_start();
	if(isset($_SESSION['role']) && isset($_SESSION['id'])) {
		include "Model/notification.php";
		include "../DB_connection.php";

        if (isset($_GET['notification_id'])) {
            $notification_id = $_GET['notification_id'];
            notifications_make_read($conn, $_SESSION['id'], $notification_id);
            header("Location: ../notifications.php");
            exit();
        }
        else{
            header("Location: index.php");
            exit();
        }

    }else{ 
        $em = "First Login";
        header("Location: login.php?error=$em");
        exit();
}
?>