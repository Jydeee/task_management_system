<!-- <header class="header">
  <div class="head">
    <img src="img/logo.png" alt="Logo" class="logo"/>
    <div class="header-title">
      <h2 class="u-name">Task Management System</h2>
      <label for="checkbox" class="menu-toggle">
        <i id="navbtn" class="fa fa-bars" aria-hidden="true"></i>
      </label>
    </div>
  </div>
  <span class="notification" id="notificationBtn">
    <i class="fa fa-bell" aria-hidden="true"></i>
    <span id="notificationNum"></span>
  </span>
</header>

<div class = "notification-bar" id = "notificationBar">
    <ul id="notifications">
        
    </ul>
</div>

<script type="text/javascript">
    var openNotification = false;

    const notification = ()=> {
        let notificationBar = document.querySelector("#notificationBar");
        if (openNotification) {
            notificationBar.classList.remove('open-notification');
            openNotification = false;
        }

        else {
            notificationBar.classList.add('open-notification');
            openNotification = true;
        }
    }
    let notificationBtn = document.querySelector("#notificationBtn");
    notificationBtn.addEventListener("click", notification);
</script>

<script>
  document.addEventListener("click", function (e) {
    const notificationBar = document.getElementById("notificationBar");
    const notificationBtn = document.getElementById("notificationBtn");

    if (
      openNotification &&
      !notificationBar.contains(e.target) &&
      !notificationBtn.contains(e.target)
    ) {
      notificationBar.classList.remove("open-notification");
      openNotification = false;
    }
  });
</script>

<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(document).ready(function(){
        
        $("#notificationNum").load("app/notification-count.php");
        $("#notifications").load("app/notification.php");
    });
</script> -->

<header class="header">
  <div class="head">
    <img src="img/logo.png" alt="Logo" class="logo"/>
    <div class="header-title">
      <h2 class="u-name">Task Management System</h2>
      <label for="checkbox" class="menu-toggle">
        <i id="navbtn" class="fa fa-bars" aria-hidden="true"></i>
      </label>
    </div>
  </div>
  <span class="notification" id="notificationBtn">
    <i class="fa fa-bell" aria-hidden="true"></i>
    <span id="notificationNum"></span>
  </span>
</header>

<div class="notification-bar" id="notificationBar">
  <ul id="notifications"></ul>
</div>

<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    let openNotification = false;
    let lastNotificationCount = 0;

    const notificationBtn = document.getElementById("notificationBtn");
    const notificationBar = document.getElementById("notificationBar");

    // Toggle notification panel
    const toggleNotification = () => {
        if (openNotification) {
            notificationBar.classList.remove('open-notification');
            openNotification = false;
        } else {
            notificationBar.classList.add('open-notification');
            openNotification = true;
        }
    };

    // Request browser notification permission when user clicks bell
    notificationBtn.addEventListener("click", function() {
        if ("Notification" in window && Notification.permission === "default") {
            Notification.requestPermission().then(permission => {
                console.log("Notification permission:", permission);
            });
        }
        toggleNotification();
    });

    // Close notification panel when clicking outside
    document.addEventListener("click", function(e) {
        if (openNotification && !notificationBar.contains(e.target) && !notificationBtn.contains(e.target)) {
            notificationBar.classList.remove("open-notification");
            openNotification = false;
        }
    });

    // Fetch notifications from server
    function fetchNotifications() {
        // Load the notification count
        $("#notificationNum").load("app/notification-count.php", function(count){
            const num = parseInt(count.trim()) || 0;

            // Show browser notification only if new notifications exist
            if (num > lastNotificationCount && Notification.permission === "granted") {
                new Notification("You have " + num + " new notification(s)", {
                    body: "Check your Task Management System for details.",
                    icon: "img/logo.png"
                });
            }
            lastNotificationCount = num;
        });

        // Load the in-app notifications
        $("#notifications").load("app/notification.php");
    }

    // Initial fetch
    fetchNotifications();

    // Poll every 10 seconds for updates
    setInterval(fetchNotifications, 10000);
});
</script>
