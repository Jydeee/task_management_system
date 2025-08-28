<?php
function get_all_my_notifications($conn, $id){
    $sql = "SELECT * FROM notifications WHERE recipient = ? ORDER BY id DESC LIMIT 5";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        return $stmt->fetchAll();
    } else {
        return 0;
    }
}

    // function get_all_my_notifications($conn, $id){
    //     $sql = "SELECT * FROM notifications WHERE recipient=? LIMIT 5";
    //     $stmt = $conn -> prepare($sql);
    //     $stmt -> execute([$id]);

    //     if($stmt -> rowCount() > 0){
    //         $notifications = $stmt -> fetchAll();
    //     }
    //     else $notifications = 0;
        
    //     return $notifications;
    // }

function count_notification($conn, $id){
$sql = "SELECT id FROM notifications WHERE recipient=? AND is_read=0";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);

return $stmt->rowCount();
}

function insert_notification($conn, $data){
    $sql = "INSERT INTO notifications (message, recipient, type) VALUES(?,?,?)";
    $stmt = $conn -> prepare($sql);
    $stmt -> execute($data);
}

function notifications_make_read($conn, $recipient_id, $notification_id){
    $sql = "UPDATE notifications SET is_read=1 WHERE id=? AND recipient=?";
    $stmt = $conn -> prepare($sql);
    $stmt -> execute([$notification_id, $recipient_id]);
}

function mark_all_notifications_as_read($conn, $id) {
    $sql = "UPDATE notifications SET is_read = 1 WHERE recipient = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
}
