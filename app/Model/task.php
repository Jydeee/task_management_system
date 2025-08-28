<?php

function insert_task($conn, $data){
    $sql = "INSERT INTO tasks (title, description, assigned_to, due_date) VALUES(?,?,?,?)";
    $stmt = $conn -> prepare($sql);
    $stmt -> execute($data);
}

function get_all_tasks($conn){
        $sql = "SELECT * FROM tasks ORDER BY id DESC";
        $stmt = $conn -> prepare($sql);
        $stmt -> execute([]);

        if($stmt -> rowCount() > 0){
            $tasks = $stmt -> fetchAll();
        }
        else $tasks = 0;
        
        return $tasks;
}

function get_all_tasks_due_today($conn){
        $sql = "SELECT * FROM tasks WHERE due_date = CURDATE() AND status != 'completed' ORDER BY id DESC";
        $stmt = $conn -> prepare($sql);
        $stmt -> execute([]);

        if($stmt -> rowCount() > 0){
            $tasks = $stmt -> fetchAll();
        }
        else $tasks = 0;
        
        return $tasks;
}

function count_tasks_due_today($conn){
	$sql = "SELECT id FROM tasks WHERE due_date = CURDATE() AND status != 'completed'";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	return $stmt->rowCount();
}

function get_all_tasks_overdue($conn){
        $sql = "SELECT * FROM tasks WHERE due_date < CURDATE() AND status != 'completed' ORDER BY id DESC";
        $stmt = $conn -> prepare($sql);
        $stmt -> execute([]);

        if($stmt -> rowCount() > 0){
            $tasks = $stmt -> fetchAll();
        }
        else $tasks = 0;
        
        return $tasks;
}

function count_tasks_overdue($conn){
	$sql = "SELECT id FROM tasks WHERE due_date < CURDATE() AND status != 'completed'";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	return $stmt->rowCount();
}

function get_all_tasks_NoDeadline($conn){
    $sql = "SELECT * FROM tasks WHERE status != 'completed' AND due_date IS NULL OR due_date = '0000-00-00'";
    $stmt = $conn -> prepare($sql);
    $stmt -> execute([]);

    if($stmt -> rowCount() > 0){
        $tasks = $stmt -> fetchAll();
    }
    else $tasks = 0;
    
    return $tasks;
}

function count_tasks_NoDeadline($conn){
	$sql = "SELECT id FROM tasks WHERE status != 'completed' AND due_date IS NULL OR due_date = '0000-00-00'";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	return $stmt->rowCount();
}

function delete_task($conn, $data){
$sql = "DELETE FROM tasks WHERE id=?";
$stmt = $conn -> prepare($sql);
$stmt -> execute($data);
}

function get_task_by_id($conn, $id){
	$sql = "SELECT * FROM tasks WHERE id =? ";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

	if($stmt->rowCount() > 0){
		$task = $stmt->fetch();
	}else $task = 0;

	return $task;
}

function count_tasks($conn){
	$sql = "SELECT id FROM tasks";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	return $stmt->rowCount();
}

// function update_task($conn, $data){
//     $sql = "UPDATE tasks SET title=?, description=?, assigned_to=?, due_date=? WHERE id=?";
//     $stmt = $conn -> prepare($sql);
//     $stmt -> execute($data);
//}

function update_task($conn, $data){
    // $data = [$title, $description, $assigned_to, $due_date, $id]
    $sql = "UPDATE tasks 
            SET title = ?, 
                description = ?, 
                assigned_to = ?, 
                due_date = ?, 
                status = 'pending'  /* reset status to pending */ 
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute($data);
}

function update_task_status($conn, $data){
    $sql = "UPDATE tasks SET status=? WHERE id=?";
    $stmt = $conn -> prepare($sql);
    $stmt -> execute($data);
}

function get_all_tasks_by_id($conn, $id){
        $sql = "SELECT * FROM tasks WHERE assigned_to=?";
        $stmt = $conn -> prepare($sql);
        $stmt -> execute([$id]);

        if($stmt -> rowCount() > 0){
            $tasks = $stmt -> fetchAll();
        }
        else $tasks = 0;
        
        return $tasks;
}

function count_pending_tasks($conn){
	$sql = "SELECT id FROM tasks WHERE status = 'pending'";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	return $stmt->rowCount();
}

function count_in_progress_tasks($conn){
	$sql = "SELECT id FROM tasks WHERE status = 'in_progress'";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	return $stmt->rowCount();
}

function count_completed_tasks($conn){
	$sql = "SELECT id FROM tasks WHERE status = 'completed'";
	$stmt = $conn->prepare($sql);
	$stmt->execute([]);

	return $stmt->rowCount();
}

function count_my_tasks($conn, $id){
	$sql = "SELECT id FROM tasks WHERE assigned_to=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

	return $stmt->rowCount();
}

function count_my_tasks_overdue($conn, $id){
	$sql = "SELECT id FROM tasks WHERE due_date < CURDATE() AND status != 'completed' AND assigned_to=? AND due_date != '0000-00-00'";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

	return $stmt->rowCount();
}

function count_my_tasks_NoDeadline($conn, $id){
	$sql = "SELECT id FROM tasks WHERE assigned_to=? AND status != 'completed' AND due_date IS NULL OR due_date = '0000-00-00'";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

	return $stmt->rowCount();
}

function count_my_pending_tasks($conn, $id){
	$sql = "SELECT id FROM tasks WHERE status = 'pending' AND assigned_to=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

	return $stmt->rowCount();
}

function count_my_in_progress_tasks($conn, $id){
	$sql = "SELECT id FROM tasks WHERE status = 'in_progress' AND assigned_to=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

	return $stmt->rowCount();
}

function count_my_completed_tasks($conn, $id){
	$sql = "SELECT id FROM tasks WHERE status = 'completed' AND assigned_to=?";
	$stmt = $conn->prepare($sql);
	$stmt->execute([$id]);

	return $stmt->rowCount();
}

// --- Mark employee-completed -> Pending Approval
function requestTaskCompletion($taskId, PDO $conn) {
    $sql = "UPDATE tasks SET status = 'pending_approval' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([$taskId]);
}

// --- Admin approves -> Completed
function approveTaskCompletion($taskId, $adminId, PDO $conn) {
    // If you added approved_by/approved_at columns, use this:
    $sql = "UPDATE tasks 
            SET status = 'completed', approved_by = ?, approved_at = NOW() 
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([$adminId, $taskId]);

    // If you DID NOT add audit columns, use this instead:
    // $sql = "UPDATE tasks SET status = 'Completed' WHERE id = ?";
    // $stmt = $conn->prepare($sql);
    // return $stmt->execute([$taskId]);
}

// --- (Optional) Admin rejects -> Back to In Progress
function rejectTaskCompletion($taskId, PDO $conn) {
    $sql = "UPDATE tasks SET status = 'in_progress' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([$taskId]);
}

// --- Get tasks pending approval (for admin queue)
function getPendingApprovalTasks(PDO $conn) {
    $sql = "SELECT * FROM tasks WHERE status = 'pending_approval' ORDER BY id DESC";
    return $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}
