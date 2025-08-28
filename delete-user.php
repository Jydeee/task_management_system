<?php
	session_start();
	if(isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
		include "DB_connection.php";
        include "app/Model/user.php";

        if (!isset($_GET['id'])) {
            header("Location: user.php");    
            exit();
        }

		$id = $_GET['id'];
        $user = get_user_by_id($conn, $id);

        
        if ($user == 0) {
            header("Location: user.php");    
            exit();
        }

        $data = array($id, "employee");
        $result = delete_user($conn, $data);

        if ($result === true) {
            $sm = "Deleted Successfully";
            header("Location: user.php?success=$sm");
            exit();
        } elseif ($result === "HAS_TASKS") {
            $em = "⚠️ Please delete this user's tasks first.";
            header("Location: user.php?error=$em");
            exit();
        } else {
            $em = "❌ Unable to delete user.";
            header("Location: user.php?error=$em");
            exit();
        }
    }
    
    else{ 
	$em = "First Login";
	header("Location: /login.php?error=$em");
	exit();
}
?>