<?php
$dataDir = __DIR__ . '/data';
if (!is_dir($dataDir)) mkdir($dataDir, 0777, true);

$messageFile = $dataDir . '/message.txt';
$userMessagesFile = $dataDir . '/user_messages.txt';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'user_message') {
        $msg = trim($_POST['message']);
        if ($msg !== '') {
            file_put_contents($userMessagesFile, $msg . "\n", FILE_APPEND | LOCK_EX);
            echo 'ok';
        }
        exit;
    }

    if ($_POST['action'] === 'admin_save') {
        $msg = trim($_POST['new_message'] ?? '');
        if ($msg !== '') {
            file_put_contents($messageFile, $msg);
        }
        echo 'ok';
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($_GET['action'] === 'get_message') {
        echo file_exists($messageFile) ? file_get_contents($messageFile) : 'پیامی ثبت نشده است.';
        exit;
    }

    if ($_GET['action'] === 'get_all') {
        $message = file_exists($messageFile) ? file_get_contents($messageFile) : '';
        $userMessages = file_exists($userMessagesFile)
            ? file($userMessagesFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)
            : [];
        header('Content-Type: application/json');
        echo json_encode([
            'message' => $message,
            'user_messages' => $userMessages
        ]);
        exit;
    }
}
?>
