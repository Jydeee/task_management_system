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

?>
<!DOCTYPE html>
<html>
<head>
	<title> Edit User </title>
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
            <h4 class = "title"> Edit Users <a href="user.php"> Users </a></h4>
			<form class="form-1" method = "POST" action = "app/update-user.php">
                <?php if (isset($_GET['error'])) { ?>
                <div class="danger" role="alert">
                    <?php echo stripcslashes($_GET['error']); ?> 
                </div>
                <?php } ?>

                <?php if (isset($_GET['success'])) { ?>
                    <div class="success" role="alert">
                        <?php echo stripcslashes($_GET['success']); ?> 
                    </div>
                <?php }  ?>
                
                <div class = "input-holder">
                    <Label> Full Name </label>
                    <input type = "text" name = "full_name" class="input-1" placeholder="Full Name" value = "<?=$user['full_name']?>" ><br>
                </div>

                <div class = "input-holder">
                    <Label> Username </label>
                    <input type = "text" name = "user_name" value = "<?=$user['username']?>" class="input-1" placeholder="Username"><br>
                </div>

                <div class = "input-holder">
                    <Label> Password </label>
                    <input type = "text" name = "password" value = "********"  class="input-1" placeholder="password"><br>
                </div>
                <input type = "text" name = "id" value = "<?=$user['id']?>" hidden>

                <button class="edit-btn"> Update </button>
            </form>
		</section>
	</div>

<script type = "text/javascript">
    var active = document.querySelector("#navList li:nth-child(2)");
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