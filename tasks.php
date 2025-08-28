<?php
	session_start();

		if(!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
			$em = "First Login";
			header("Location: ../login.php?error=$em");
			exit();
		}

		if(isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "admin") {
			include "app/Model/task.php";
			include "app/Model/user.php";
			include "DB_connection.php";

		// Handle Approve/Reject actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_id'])) {
    $taskId = intval($_POST['task_id']);

    if (isset($_POST['approve_task'])) {
        // Admin approves the task
        $stmt = $conn->prepare("UPDATE tasks SET status = 'Completed' WHERE id = ?");
        $stmt->execute([$taskId]);
        header("Location: tasks.php?success=Task approved successfully!");
        exit;
    }

    if (isset($_POST['reject_task'])) {
        // Admin rejects the task → send it back to In Progress
        $stmt = $conn->prepare("UPDATE tasks SET status = 'in_progress' WHERE id = ?");
        $stmt->execute([$taskId]);
        header("Location: tasks.php?success=Task rejected and sent back to In Progress!");
        exit;
    }
}

		
		$text = "All Task";
		if (isset($_GET['due_date']) && $_GET['due_date'] == "Due Today") {
			$text = "Due Today";
			$tasks = get_all_tasks_due_today($conn);
			$num_task = count_tasks_due_today($conn);
		}

		else if (isset($_GET['due_date']) && $_GET['due_date'] == "Overdue") {
			$text = "Overdue";
			$tasks = get_all_tasks_overdue($conn);
			$num_task = count_tasks_overdue($conn);
		}

		else if (isset($_GET['due_date']) && $_GET['due_date'] == "No Deadline") {
			$text = "No Deadline";
			$tasks = get_all_tasks_NoDeadline($conn);
			$num_task = count_tasks_NoDeadline($conn);
		}
		
		else {
			$tasks = get_all_tasks($conn);
			$num_task = count_tasks($conn);
		}

		$users = get_all_users($conn);
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title> All Tasks </title>
	<link rel="icon" href="img/favicon.png" type="image/png">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<input type="checkbox" id="checkbox">
	<?php include "inc/header.php" ?>
	<div class="body">
		<?php include "inc/nav.php" ?>
		<section class="section-1">
			<!-- Filters -->
			<h4 class="title-2">
				<a href="create_task.php" class="btn <?= basename($_SERVER['PHP_SELF']) == 'create_task.php' ? 'active' : '' ?>"> Create Task</a>
				<a href="tasks.php" class="btn <?= !isset($_GET['due_date']) && basename($_SERVER['PHP_SELF']) == 'tasks.php' ? 'active' : '' ?>">All Tasks</a>
				<a href="tasks.php?due_date=Due Today" class="btn <?= ($_GET['due_date'] ?? '') == 'Due Today' ? 'active' : '' ?>">Due Today</a>
				<a href="tasks.php?due_date=Overdue" class="btn <?= ($_GET['due_date'] ?? '') == 'Overdue' ? 'active' : '' ?>">Overdue</a>
				<a href="tasks.php?due_date=No Deadline" class="btn <?= ($_GET['due_date'] ?? '') == 'No Deadline' ? 'active' : '' ?>">No Deadline</a>				
			</h4>

			<!-- ✅ Approval Queue Section (Step 6) -->
			<?php if ($_SESSION['role'] == 'admin'): ?>
				<h4 class="title-2">Pending Approval (<?=count(getPendingApprovalTasks($conn))?>)</h4>
				<?php $pending = getPendingApprovalTasks($conn); ?>
				<?php if (empty($pending)) { ?>
					<p>No tasks are waiting for approval.</p>
				<?php } else { ?>
					<table class="main-table">
						<tr>
							<th>#</th>
							<th>Title</th>
							<th>Assigned To</th>
							<th>Due Date</th>
							<th>Action</th>
						</tr>
						<?php $j = 0; foreach ($pending as $task) { ?>
							<tr>
								<td><?=++$j?></td>
								<td><?=$task['title']?></td>
								<td>
									<?php 
									foreach ($users as $user) {
										if ($user['id'] == $task['assigned_to']) {
											echo $user['full_name'];
										}
									}
									?>
								</td>
								<td><?=$task['due_date'] ?: 'No Deadline'?></td>
								<td>
									<form method="POST" action="tasks.php" style="display:inline;">
										<input type="hidden" name="task_id" value="<?=$task['id']?>">
										<button type="submit" name="approve_task" class="edit-btn">Approve</button>
									</form>
									<form method="POST" action="tasks.php" style="display:inline;">
										<input type="hidden" name="task_id" value="<?=$task['id']?>">
										<button type="submit" name="reject_task" class="delete-btn">Reject</button>
									</form>
								</td>
							</tr>
						<?php } ?>
					</table>
				<?php } ?>
			<?php endif; ?>

			<?php if (isset($_GET['success'])) { ?>
				<div class="success" role="alert">
					<?php echo stripcslashes($_GET['success']); ?> 
				</div>
			<?php } ?>
				
			<?php if ($tasks != 0) { ?>
				<table class = "main-table">
					<tr> 
						<th> # </th>
						<th> Title </th>
						<th> Description </th>
						<th> Assigned to </th>
						<th> Due Date </th>
						<th> Status </th>
						<th> Action </th>
					</tr>
			<?php $i = 0; foreach ($tasks as $task) { ?>			
			<tr> 
				<td> <?=++$i?> </td>
				<td> <?=$task['title']?> </td>
				<td> <?=$task['description']?>  </td>
				<td> 
			<?php 
			
			foreach ($users as $user) {
				if ($user ['id'] == $task['assigned_to']) {
					echo $user ['full_name'];
				} 
			} ?>
			</td>
			
			<td> 
				<?php if ($task['due_date'] == "") echo "No Deadline";
					else echo $task['due_date'];
				?> 
			</td>
			
			<!-- Status column -->
			<td> 
				<?php if ($task['status'] == 'pending_approval') { ?>
					<span style="color: #e49311ff; font-weight: bold;">Pending Approval</span>
				<?php } elseif ($task['status'] == 'completed') { ?>
					<span style="color: green; font-weight: bold;">Completed</span>
				<?php } elseif ($task['status'] == 'in_progress') { ?>
        			<span>In Progress</span>
				<?php } else { ?>
					<?=$task['status']?>
				<?php } ?>
			</td>

			<!-- Actions column -->
			<td> 
				<a href="edit-task.php?id=<?=$task['id']?>" class="edit-btn"> Edit </a>
				<a href="delete-task.php?id=<?=$task['id']?>" class="delete-btn"> Delete </a>
			</td>
		</tr>
		<?php } ?>

		</section>
	</div>

<script type = "text/javascript">
    var active = document.querySelector("#navList li:nth-child(4)");
    active.classList.add("active");
</script>
</body>
</html>
<?php } ?>

