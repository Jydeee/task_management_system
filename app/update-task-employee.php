<?php 
session_start();
if(isset($_SESSION['role']) && isset($_SESSION['id'])) {

    if (isset($_POST['id']) && isset($_POST['status']) && $_SESSION['role'] == 'employee') {
        include "../DB_connection.php";
        // include "Model/user.php";

        function validate_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $status = validate_input($_POST['status']);
        $id = validate_input($_POST['id']);

        if (empty($status)) {
            $em = "Status is required";
            header("Location: ../edit-task-employee.php?error=$em&id=$id");
            exit();
        } else {
            include "Model/task.php";
            include "Model/notification.php";
            include "Model/user.php"; // for fetching admin

            // Convert completed → pending_approval
            if ($status === "completed") {
                $status = "pending_approval";
            }

            // Update the task status
            $data = array($status, $id);
            update_task_status($conn, $data);

            // If task is pending approval, notify admin
            if ($status === "pending_approval") {
                // Get admin user(s)
                $admin = get_admin_user($conn); // you need a function in User.php
                if ($admin) {
                    $task = get_task_by_id($conn, $id); 
                    $notif_message = "Task '" . $task['title'] . "' has been completed by employee. Please review and approve.";
                    $notif_data = array($notif_message, $admin['id'], 'task_approval');
                    insert_notification($conn, $notif_data);
                }
            }

            $em = "Task updated successfully";
            header("Location: ../edit-task-employee.php?success=$em&id=$id");
            exit();
        }
    } else {
        $em = "Unknown error occurred";
        header("Location: ../edit-task-employee.php?error=$em");
        exit();
    }

} else { 
    $em = "First Login";
    header("Location: ../login.php?error=$em");
    exit();
}
?>
