<?php
session_start();
if(isset($_SESSION['role']) && isset($_SESSION['id'])) {
	include "app/Model/task.php";
	include "app/Model/user.php";
	include "DB_connection.php";

require_once __DIR__ . '/DB_connection.php';
require_once __DIR__ . '/app/Model/task.php';

// Example session structure:
// $_SESSION['user'] = ['id' => 5, 'name' => 'Jane', 'role' => 'employee']; // or admin

function is_admin() {
    return isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin';
}

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		// Employee clicks "Mark as Completed" -> actually requests approval
		if (isset($_POST['request_complete']) && isset($_POST['task_id'])) {
			requestTaskCompletion((int)$_POST['task_id'], $conn);
			$_SESSION['flash'] = 'Task set to Pending Approval.';
			header('Location: index.php'); exit;
		}

		// Admin approves
		if (isset($_POST['approve_task']) && isset($_POST['task_id'])) {
			if (!is_admin()) { http_response_code(403); exit('Forbidden'); }
			$adminId = $_SESSION['user']['id'] ?? null;
			approveTaskCompletion((int)$_POST['task_id'], $adminId, $conn);
			$_SESSION['flash'] = 'Task approved as Completed.';
			header('Location: index.php'); exit;
		}

		// (Optional) Admin rejects
		if (isset($_POST['reject_task']) && isset($_POST['task_id'])) {
			if (!is_admin()) { http_response_code(403); exit('Forbidden'); }
			rejectTaskCompletion((int)$_POST['task_id'], $conn);
			$_SESSION['flash'] = 'Task sent back to In Progress.';
			header('Location: index.php'); exit;
		}
	}


	if ($_SESSION['role'] == "admin") {
		$todaydue_task = count_tasks_due_today($conn);
		$overdue_task = count_tasks_overdue($conn);
		$nodeadline_task = count_tasks_NoDeadline($conn);
		$num_task = count_tasks($conn);
		$num_users = count_users($conn);
		$pending = count_pending_tasks($conn);
		$in_progress = count_in_progress_tasks($conn);
		$completed = count_completed_tasks($conn);
	}

	else {
		$num_my_task = count_my_tasks($conn, $_SESSION['id']);
		$overdue_task = count_my_tasks_overdue($conn, $_SESSION['id']);
		$nodeadline_task = count_my_tasks_NoDeadline($conn, $_SESSION['id']);
		$pending = count_my_pending_tasks($conn, $_SESSION['id']);
		$in_progress = count_my_in_progress_tasks($conn, $_SESSION['id']);
		$completed = count_my_completed_tasks($conn, $_SESSION['id']);
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Dashboard</title>
	<link rel="icon" href="img/favicon.png" type="image/png">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="https://fonts.cdnfonts.com/css/satoshi" rel="stylesheet">
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<input type="checkbox" id="checkbox">
	<?php include "inc/header.php" ?>
	<div class="body">
		<?php include "inc/nav.php" ?>
		<section class="section-1">
			<?php if ($_SESSION['role'] == "admin") { ?>
				<div class = "dashboard">
					<a href = "user.php" style = "text-decoration: none;"> <div class = "dashboard-item"> 
						<i class="fa fa-users"></i>
						<span> (<?=$num_users?>) Employee(s) </span>
					</div> </a>

					<a href = "tasks.php" style = "text-decoration: none;"> <div class = "dashboard-item"> 
						<i class="fa fa-tasks"></i>
						<span> (<?=$num_task?>) All Tasks </span>
					</div> </a>

					<div class = "dashboard-item"> 
						<i class="fa fa-window-close-o"></i>
						<span> (<?=$overdue_task?>) Overdue </span>
					</div>

					<div class = "dashboard-item"> 
						<i class="fa fa-clock-o"></i>
						<span> (<?=$nodeadline_task?>) No Deadline </span>
					</div>

					<div class = "dashboard-item"> 
						<i class="fa fa-exclamation-triangle"></i>
						<span> (<?=$todaydue_task?>) Due Today </span>
					</div>

					<div class = "dashboard-item"> 
						<i class="fa fa-bell"></i>
						<span> (<?=$overdue_task?>) Notifications </span>
					</div>

					<div class = "dashboard-item"> 
						<i class="fa fa-square-o"></i>
						<span> (<?=$pending?>) Pending </span>
					</div>

					<div class = "dashboard-item"> 
						<i class="fa fa-spinner"></i>
						<span> (<?=$in_progress?>) In progress </span>
					</div>

					<div class = "dashboard-item"> 
						<i class="fa fa-check-square-o"></i>
						<span> (<?=$completed?>) Completed </span>
					</div>
				</div>
			<?php } else { ?>
				<div class = "dashboard">
					<div class = "dashboard-item"> 
						<i class="fa fa-tasks"></i>
						<span> (<?=$num_my_task?>) My Tasks </span>
					</div>

					<div class = "dashboard-item"> 
						<i class="fa fa-window-close-o"></i>
						<span> (<?=$overdue_task?>) Overdue </span>
					</div>

					<div class = "dashboard-item"> 
						<i class="fa fa-clock-o"></i>
						<span> (<?=$nodeadline_task?>) No Deadline </span>
					</div>

					<div class = "dashboard-item"> 
						<i class="fa fa-square-o"></i>
						<span> (<?=$pending?>) Pending </span>
					</div>

					<div class = "dashboard-item"> 
						<i class="fa fa-spinner"></i>
						<span> (<?=$in_progress?>) In progress </span>
					</div>

					<div class = "dashboard-item"> 
						<i class="fa fa-check-square-o"></i>
						<span> (<?=$completed?>) Completed </span>
					</div>
				</div>
			<?php } ?>
		</section>
	</div>

	<?php if (!empty($_SESSION['flash'])): ?>
		<div style="padding:8px; background:#e7f7ee; border:1px solid #b8e0c7; margin-bottom:10px;">
			<?= htmlspecialchars($_SESSION['flash']) ?>
		</div>
		<?php unset($_SESSION['flash']); ?>
	<?php endif; ?>


<script type = "text/javascript">
    var active = document.querySelector("#navList li:nth-child(1)");
    active.classList.add("active");
</script>

</body>
</html>
<?php }else{ 
	$em = "First Login";
	header("Location: login.php?error=$em");
	exit();
}
?>