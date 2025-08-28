<?php
session_start();
if(isset($_SESSION['role']) && isset($_SESSION['id'])) {
    include "../DB_connection.php";
    include "Model/notification.php";

    $role = $_SESSION['role'];
    $user_id = $_SESSION['id'];

    if($role === 'admin') {
        // Count tasks pending admin approval
        $stmt = $conn->prepare("SELECT COUNT(*) FROM tasks WHERE status = 'pending_approval'");
        $stmt->execute();
        $count_notification = $stmt->fetchColumn();
    } else {
        // Count notifications for this employee
        $count_notification = count_notification($conn, $user_id);
    }

    echo $count_notification;
} else { 
    echo "0"; // return 0 if not logged in
}
?>
