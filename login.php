<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Login | TMS </title>
    <link rel="icon" href="img/favicon.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class ="login-body">
    <div class="login-head">
        <img src="img/logo.png" class = "vnimage">
        <h1> Task Management System </h1>
    </div>
    <div id="center-loader-bar"></div>

    <form method= "POST" action = "app/login.php" class = "shadow p-4">
        
        <h3 class="display-4"> LOGIN </h3>
        <?php if (isset($_GET['error'])) { ?>
            <div class="alert alert-danger" role="alert">
                <?php echo stripcslashes($_GET['error']); ?> 
            </div>
        <?php } ?>

        <?php if (isset($_GET['success'])) { ?>
            <div class="alert alert-success" role="alert">
                <?php echo stripcslashes($_GET['success']); ?> 
            </div>
        <?php } 
  
            // $pass = "vnigtms";
            // $pass = password_hash($pass, PASSWORD_DEFAULT);
            // echo $pass;

            // if (password_verify('Vnigtms@234', $pass)) {
            //     echo "Password verified!";
            // } else {
            //     echo "Incorrect password.";
            // }
        ?>
    
       
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">User name</label>
            <input type="text" class="form-control" name = "user_name">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <div class="input-group">
                <input type="password" class="form-control" name="password" id="exampleInputPassword1">
                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                    Show
                </button>
            </div>
            <!-- <input type="password" class="form-control" name="password" id="exampleInputPassword1"> -->
        </div>
        <button type="submit" class="btn btn-primary" id="loginBtn">Login</button>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('exampleInputPassword1');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.textContent = type === 'password' ? 'Show' : 'Hide';
        });
    </script>

    <script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('exampleInputPassword1');

    togglePassword.addEventListener('click', function () {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.textContent = type === 'password' ? 'Show' : 'Hide';
    });

    // Show loading bar in center before submit
    const loginForm = document.querySelector("form");
    const loginBtn = document.getElementById("loginBtn");
    const centerLoader = document.getElementById("center-loader-bar");

    loginForm.addEventListener("submit", function (e) {
        centerLoader.style.display = "block";
        centerLoader.style.width = "0";

        // Animate the bar first
        setTimeout(() => {
            centerLoader.style.width = "200px";
        }, 10);

        // Optionally delay form submission (not ideal in production)
        e.preventDefault();
        setTimeout(() => {
            loginForm.submit();
        }, 1000);
    });
</script>



</body>
</html>
