<?php
    header('Content-Type: application/json');
    error_reporting(0);
    include('../config/database.php');

    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
    $password = $_POST['password'];

    //finding the user
    $stmt = $conn->prepare('SELECT * FROM member_tbl WHERE member_user = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    //if the user is found we succesfully login to dashboard
    if($user && password_verify($password, $user['member_pass'])){
        $ip = $_SERVER['REMOTE_ADDR'];
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        $logStmt = $conn->prepare('INSERT INTO login_logs (member_id, ip_address, user_agent) VALUES (?, ?, ?)');
        $logStmt->bind_param('iss', $user['member_id'], $ip, $user_agent);
        $logStmt->execute();
        $logStmt->close();

        session_start();
        $_SESSION['member'] = $user['member_id'];
        echo json_encode(['success' => true]);
    }else{
        echo json_encode(['success' => false, 'message' => 'Invalid password or username']);
    }

    $stmt->close();
?>