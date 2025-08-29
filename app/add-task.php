<?php 
session_start();
if(isset($_SESSION['role']) && isset($_SESSION['id'])) {

if (isset($_POST['title']) && isset($_POST['description'])  && isset($_POST['assigned_to']) && $_SESSION['role'] == 'admin' && isset($_POST['due_date'])) {
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
	$due_date = validate_input($_POST['due_date']);

	// Prepare the SQL query
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
	    header("Location: ../create_task.php?error=$em");
	    exit();
	}
    
    else if (empty($description)) {
		$em = "Please add a description";
	    header("Location: ../create_task.php?error=$em");
	    exit();
	}

	else if ($assigned_to == 0) {
		$em = "Please select an employee";
	    header("Location: ../create_task.php?error=$em");
	    exit();
	}

    else {
        include "Model/task.php";
		include "Model/notification.php";

		$sql = "SELECT id FROM user";
		$api_result = send_task_to_api($title, $description, $full_name, $username, $due_date);
		if ($api_result['success']) {
			$data = array($title, $description, $assigned_to, $due_date);
			insert_task($conn, $data);
			$notif_data = array("$title has been assigned to you. Please review and start working on it", $assigned_to, 'task');
			insert_notification($conn, $notif_data);
			$em = "Task created successfully";
			header("Location: ../create_task.php?success=$em");
			exit();
		} else {
			$em = "Failed to send task to API";
			header("Location: ../create_task.php?error=$em");
			exit();
		}	

	}
}else {
   $em = "Unknown error occurred";
   header("Location: ../create_task.php?error=$em");
   exit();
}

}else{ 
	$em = "First Login";
	header("Location: ../create_task.php?error=$em");
	exit();
}