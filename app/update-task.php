<?php 
session_start();
if(isset($_SESSION['role']) && isset($_SESSION['id'])) {

    if (isset($_POST['id']) && isset($_POST['title']) && isset($_POST['description']) && isset($_POST['assigned_to']) && $_SESSION['role'] == 'admin' && isset($_POST['due_date'])) {
        include "../DB_connection.php";
        include "send-to-api.php";

        function validate_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $title = validate_input($_POST['title']);
        $description = validate_input($_POST['description']);
        $assigned_to = validate_input($_POST['assigned_to']);
        $id = validate_input($_POST['id']);
        $due_date = validate_input($_POST['due_date']);

        $sql = "SELECT full_name, username FROM users WHERE id = :id";
        $stmt = $conn->prepare($sql);

        // Bind parameter
        $stmt->bindParam(':id', $assigned_to, PDO::PARAM_INT);

        // Execute
        $stmt->execute();

        // Fetch row and assign to variables
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $full_name = $row['full_name'];
            $username = $row['username'];
        } else {
            $full_name = null;
            $username = null;
	    }


        if (empty($title)) {
            $em = "Title is required";
            header("Location: ../edit-task.php?error=$em&id=$id");
            exit();
        } else if (empty($description)) {
            $em = "Please add a description";
            header("Location: ../edit-task.php?error=$em&id=$id");
            exit();
        } else if ($assigned_to == 0) {
            $em = "Please select an employee";
            header("Location: ../edit-task.php?error=$em&id=$id");
            exit();
        } else {
            include "Model/task.php";
            $api_result = send_task_to_api($title, $description, $full_name, $username, $due_date);
            if ($api_result['success']) {
                $data = array($title, $description, $assigned_to, $due_date, $id);
                update_task($conn, $data);
                $em = "Task updated successfully";
                header("Location: ../edit-task.php?success=$em&id=$id");
                exit();
            } else {
                $em = "Failed to send task to API";
                header("Location: ../edit-task.php?error=$em&id=$id");
                exit();
            }
        }
    } else {
        $em = "Unknown error occurred";
        header("Location: ../edit-task.php?error=$em");
        exit();
    }

} else { 
    $em = "First Login";
    header("Location: ../login.php?error=$em");
    exit();
}
?>