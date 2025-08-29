<?php
function send_task_to_api($title, $description, $assigned_to, $due_date) {
    $url = "{}"; // Replace with your actual API endpoint
    $data = array(
        'title' => $title,
        'description' => $description,
        'assigned_to' => $assigned_to,
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

function send_task_approval_to_api($task_id, $status, $title, $admin_id) {
    $url = "{}"; // Replace with your actual API endpoint
    $data = array(
        'task_id' => $task_id,
        'status' => $status,
        'title' => $title,
        'admin_id' => $admin_id
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