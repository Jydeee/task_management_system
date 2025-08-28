<?php
	session_start();
	if(isset($_SESSION['role']) && isset($_SESSION['id'])) {
		// include "app/Model/task.php";
		include "app/Model/notification.php";
		include "DB_connection.php";

		if (isset($_POST['clear_notifications'])) {
			$sql = "DELETE FROM notifications WHERE recipient = ?";
			$stmt = $conn->prepare($sql);
			$stmt->execute([$_SESSION['id']]);
    // Optional redirect to prevent resubmission
			header("Location: notifications.php?success=All notifications cleared!");
			exit();
}


		$notifications = get_all_my_notifications($conn, $_SESSION['id']);
		mark_all_notifications_as_read($conn, $_SESSION['id']);

?>
<!DOCTYPE html>
<html>
<head>
	<title> Notifications </title>
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
            <h4 class = "title"> All Notifications </h4>

			<?php if (isset($_GET['success'])) { ?>
                <div class="success" role="alert">
                    <?php echo stripcslashes($_GET['success']); ?> 
                </div>
			<?php } ?>
				
			<?php if ($notifications != 0) { ?>
				<form method="post" onsubmit="return confirm('Are you sure you want to clear all your notifications?');">
					<button type="submit" name="clear_notifications" class="btn-clear">Clear All Notifications</button>
				</form>

				<table class = "main-table">
					<tr> 
						<th> # </th>
						<th> Message </th>
						<th> Type </th>
						<th> Date </th>
					</tr>
					<?php $i = 0; foreach ($notifications as $notification) { ?>					
					<tr> 
						<td> <?=++$i?> </td>
						<td> <?=$notification['message']?> </td>
						<td> <?=$notification['type']?> </td>
                        <td> <?=$notification['date']?> </td>
					</tr>
					<?php } ?>
				</table> 
			<?php } else { ?>
				<h3> You have zero notifications </h3>
			<?php }?>
		</section>
	</div>

<script type = "text/javascript">
    var active = document.querySelector("#navList li:nth-child(5)");
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




