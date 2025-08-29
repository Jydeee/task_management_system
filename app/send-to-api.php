<?php
function send_task_to_api($title, $description, $full_name, $username, $due_date) {
    $url = "https://hooks.zapier.com/hooks/catch/15603921/uh897nu/"; // Replace with your actual API endpoint
    $data = array(
        'title' => $title,
        'description' => $description,
        'full_name' => $full_name,
        'username' => $username,
        'due_date' => $due_date
    );

    $json_data = json_encode($data);
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($json_data)
    ));

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    curl_close($ch);
    
    return array(
        'success' => $http_code >= 200 && $http_code < 300,
        'response' => $response
    );
}

function send_task_approval_to_api($task_id, $status, $title, $description, $assigned_to, $admin_id, $admin_full_name, $admin_username, $full_name, $username) {
    $url = "https://hooks.zapier.com/hooks/catch/15603921/uh8533j/"; // Replace with your actual API endpoint

    $data = array(
        'task_id'        => $task_id,
        'status'         => $status,
        'title'          => $title,
        'description'    => $description,
        'assigned_to'    => $assigned_to,
        'admin_id'       => $admin_id,
        'admin_full_name'=> $admin_full_name,
        'admin_username' => $admin_username,
        'user_full_name' => $full_name,
        'user_username'  => $username
    );

    $json_data = json_encode($data);
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($json_data)
    ));

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    curl_close($ch);
    
    return array(
        'success' => $http_code >= 200 && $http_code < 300,
        'response' => $response
    );
}

?>